<?php

namespace App\Livewire;

use App\Models\DropboxToken;
use App\Models\MyDocuments;
use App\Traits\HasSubscriptionCheck;
use Auth;
use Http;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Log;
use Spatie\Dropbox\Client as DropboxClient;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\File;
use Storage;
class MyDocumentList extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    public $path = DEFAULT_FOLDER_DROPBOX;
    public $actualPath = DEFAULT_FOLDER_DROPBOX;
    public $contents = [];
    public $breadcrumbs = [];
    public $loading = true;
    
    protected $dropbox;
    
    public $viewingFile = null;
    public $viewingFileContents = null;
    public $restrictedMode = false;
    public $baseRestrictedPath;
     public $searchQuery = '';
    public $searchResults = [];
    public $isLoading = false;
    public $searchPath = ''; // Optional: limit search to a specific folder
    public $selectedExtensions = [];
    public $newFolderName = '';
    public $uploadedFile = [];
    public $isUploading = false;
    public $uploadProgress = 0;
    public $successMessage = '';
    public $errorMessage = '';
    public $folderSize;
    public $folderStats = [];
    public $spaceLeft = 0;
    public $totalSpace = 0;
    public $recentFolders = [];
    public $favoriteFolders = [];
        
    public $editingItem = null;
    public $newItemName = '';
    public $showDeleteConfirm = false;
    public $itemToDelete = null;
    
    public $selectedDestinationPath = '';
    public $isMoving = false;
    public $moveModalVisible = false;
    public $itemToMove = null;
    public $availableFolders = [];
    public $myFirstPath;
    public $loadingTime = 0;
    
    public $creatingFolder = false;
    public $uploads = [];
    public $isDragOver = false;
     public $targetUploadPath = null;
    public $currentPath = '';
    public $rootPath;
    protected function rules()
    {
        return [
            'uploadedFile.0' => 'required|file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,xls,xlsx,ppt,pptx,txt,csv,zip,rar|max:10240',
        ];
    }
    protected $messages = [
        'uploadedFile.0.required' => 'The file must be a valid file upload.',
        'uploadedFile.0.mimes' => 'The file must be a type of: jpeg,png,jpg,gif,svg,doc,docx,pdf,xls,xlsx,ppt,pptx,txt,csv,zip,rar',
        'uploadedFile.0.max' => 'The file may not be greater than 50MB.',
    ];

    public function mount($id)
    {
        $this->spaceLeft = env('DEFAULT_DRIVE_SIZE');
        $this->totalSpace = env('DEFAULT_DRIVE_SIZE') * 1024 * 1024 * 1024; // 2GB in bytes
        
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        $myFileLocation=MyDocuments::find(base64_decode($id))->document_title;
        $this->rootPath=Auth::user()->email;
        $this->path=Auth::user()->email."/".$myFileLocation;
        $this->myFirstPath=Auth::user()->email."/".$myFileLocation;
        $this->baseRestrictedPath=$this->path;
        $this->initDropboxClient();
        $this->updateBreadcrumbs();
        
    }

    public function openMoveModal($path)
    {
        $this->itemToMove = $path;
        $this->isMoving = true;
        $this->moveModalVisible = true;
        
        // Get the filename from the path
        $this->newItemName = basename($path);
        
        // Load available folders for destination selection
        $this->loadAvailableFolders();
    }

    public function loadAvailableFolders($parentFolder = null)
    {
        // Set the starting root folder
        $rootFolder = "/".$this->rootPath; // Using slash prefix for Dropbox API
        
        // If parentFolder is null (initial call), use the root folder
        // Otherwise, use the provided parentFolder for recursive calls
        $currentFolder = $parentFolder === null ? $rootFolder : $parentFolder;
        
        try {
            $list = $this->dropbox->listFolder($currentFolder);
            $folders = [];
            
            // Add the starting folder as "Home" only on the initial call
            if ($parentFolder === null) {
                $folders[] = [
                    'path' => $rootFolder,
                    'name' => $this->rootPath,
                    'level' => 0
                ];
            }
            
            foreach ($list['entries'] as $entry) {
                // Only include folders, not files
                if ($entry['.tag'] === 'folder') {
                    // Skip the folder we're trying to move (can't move a folder into itself)
                    if ($this->itemToMove && $entry['path_lower'] !== strtolower($this->itemToMove)) {
                        // Calculate the display level relative to our root
                        $relativePath = str_replace($rootFolder, '', $entry['path_display']);
                        $level = substr_count($relativePath, '/') + 1; // +1 because we're already one level in
                        
                        $folders[] = [
                            'path' => $entry['path_display'],
                            'name' => basename($entry['path_display']),
                            'level' => $level
                        ];
                        
                        // Recursively get subfolders (limit depth to avoid performance issues)
                        if ($level < 1) { // Increased to 4 to account for starting one level deeper
                            $subfolders = $this->loadAvailableFolders($entry['path_display']);
                            $folders = array_merge($folders, $subfolders);
                        }
                    }
                }
            }
            
            // Set available folders only on the initial call
            if ($parentFolder === null) {
                $this->availableFolders = $folders;
            }
            
            return $folders;
        } catch (\Exception $e) {
            $this->errorMessage = 'Error loading folders: ' . $e->getMessage();
            return [];
        }
    }
    public function cancelMove()
    {
        $this->moveModalVisible = false;
        $this->itemToMove = null;
        $this->selectedDestinationPath = '';
        $this->newItemName = '';
        $this->isMoving = false;
    }

    public function moveItem()
    {
        $this->validate([
            'newItemName' => 'required|min:1|max:255',
            'selectedDestinationPath' => 'required'
        ]);
        
        // Get the current path and the destination path
        $currentPath = $this->itemToMove;
        $itemName = $this->newItemName;
        
        // Get the destination full path 
        $destinationPath = $this->selectedDestinationPath;
        
        // Create the target path (destination + filename)
        $targetPath = rtrim($destinationPath, '/') . '/' . trim($itemName, '/');
        
        // Make sure we have proper formatting for Dropbox API
        $currentPath = '/' . ltrim($currentPath, '/');
        $targetPath = '/' . ltrim($targetPath, '/');
        
        // Don't process if source and target are the same
        if ($currentPath === $targetPath) {
            $this->errorMessage = 'The item is already in this location with this name.';
            return;
        }
        
        try {
            $this->dropbox->move($currentPath, $targetPath);
            
            // Determine if this was a move or just a rename
            $wasRenamed = (basename($currentPath) !== $itemName);
            $wasMoved = (dirname($currentPath) !== dirname($targetPath));
            
            if ($wasRenamed && $wasMoved) {
                $this->successMessage = "Item moved and renamed successfully";
            } elseif ($wasMoved) {
                $this->successMessage = "Item moved successfully";
            } else {
                $this->successMessage = "Item renamed successfully";
            }
            
            // Clear cache for both source and destination paths
            $this->clearCache(dirname($currentPath));
            $this->clearCache(dirname($targetPath));
            // Refresh the folder contents
            $this->refreshFolderContents();
        } catch (\Exception $e) {
            $this->errorMessage = 'Error moving item: ' . $e->getMessage();
        }
        
        // Reset the form
        $this->cancelMove();
    }


    public function startCreateFolder()
    {
        $this->creatingFolder = true;
        $this->newFolderName = '';
        
        // Emit event to frontend to disable keyboard navigation
        $this->dispatch('creatingFolderStarted');
    }
    
    public function cancelCreateFolder()
    {
        $this->creatingFolder = false;
        $this->newFolderName = '';
        
        // Emit event to frontend to re-enable keyboard navigation
        $this->dispatch('creatingFolderEnded');
    }
    
        
    public function createFolder()
    {
        $this->errorMessage='';
        $this->successMessage='';
        $this->validate([
            'newFolderName' => 'required|min:1|max:255'
        ]);
    
        $folderPath = $this->path . '/' . trim($this->newFolderName, '/');
        $folderPath = ltrim($folderPath, '/');
    
        try {
            $this->dropbox->createFolder('/' . $folderPath);
            $this->successMessage = "Folder '{$this->newFolderName}' created successfully";
            $this->newFolderName = '';
            $this->clearCache($folderPath);
            $this->loadContents();
            $this->refreshFolderContents();
        
            $this->creatingFolder = false;
            $this->dispatch('creatingFolderEnded');
            
        } catch (\Exception $e) {
            // Check if folder already exists
            if (strpos($e->getMessage(), 'conflict') !== false) {
                $this->errorMessage = "Folder '{$this->newFolderName}' already exists";
            } else {
                $this->errorMessage = 'Error creating folder: ' . $e->getMessage();
            }
            
            $this->creatingFolder = false;
            $this->dispatch('creatingFolderEnded');
            return; // Stop execution
        }
    }
    // public function createFolder()
    // {
    //     $this->validate([
    //         'newFolderName' => 'required|min:1|max:255|regex:/^[^\/\\\\:*?"<>|]+$/'
    //     ], [
    //         'newFolderName.regex' => 'Folder name cannot contain special characters: / \ : * ? " < > |'
    //     ]);
        
    //     $folderName = trim($this->newFolderName);
    //     $currentPath = $this->currentPath ?? '';
        
    //     // Create the full path for the new folder
    //     $newFolderPath = rtrim($currentPath, '/') . '/' . $folderName;
        
    //     // Format for Dropbox API
    //     $newFolderPath = '/' . ltrim($newFolderPath, '/');
        
    //     try {
    //         // Create folder in Dropbox
    //         $this->dropbox->createFolder($newFolderPath);
            
    //         $this->successMessage = "Folder '{$folderName}' created successfully";
    //         $this->refreshFolderContents();
            
    //     } catch (\Exception $e) {
    //         $this->errorMessage = 'Error creating folder: ' . $e->getMessage();
    //     }
        
    //     $this->creatingFolder = false;
    //     $this->newFolderName = '';
        
    //     // Emit event to frontend to re-enable keyboard navigation
    //     $this->dispatch('creatingFolderEnded');
    // }
    
public function startRename($path)
{
    $this->editingItem = $path;
    $this->newItemName = basename($path);
    
    // Emit event to frontend to disable keyboard navigation
    $this->dispatch('editingStarted');
}

public function cancelRename()
{
    $this->editingItem = null;
    $this->newItemName = '';
    
    // Emit event to frontend to re-enable keyboard navigation
    $this->dispatch('editingEnded');
}

public function renameItem()
{
    $this->validate([
        'newItemName' => 'required|min:1|max:255'
    ]);
   
    $currentPath = $this->editingItem;
    $currentItemName = basename($currentPath);
    $newItemName = trim($this->newItemName, '/');
    
    // Check if the old and new names are the same
    if ($currentItemName === $newItemName) {
        // Names are the same, just close editing mode without making API call
        $this->editingItem = null;
        $this->newItemName = '';
        $this->dispatch('editingEnded');
        return;
    }
   
    // Get the parent directory path
    $parentPath = dirname($currentPath);
    // Create the new path with the new name
    $newPath = $parentPath . '/' . $newItemName;
    // Make sure we have proper formatting for Dropbox API
    $currentPath = '/' . ltrim($currentPath, '/');
    $newPath = '/' . ltrim($newPath, '/');
   
    try {
        $this->dropbox->move($currentPath, $newPath);
        $this->successMessage = "Item renamed successfully";
        $this->refreshFolderContents();
    } catch (\Exception $e) {
        $this->errorMessage = 'Error renaming item: ' . $e->getMessage();
    }
   
    $this->editingItem = null;
    $this->newItemName = '';
    
    // Emit event to frontend to re-enable keyboard navigation
    $this->dispatch('editingEnded');
}
    /**
     * Delete the file or folder
     */
    public function deleteItem($itemToDelete)
    {

        $path = $itemToDelete;
        $path = '/' . ltrim($path, '/');

        try {
            // Get item metadata before deletion
            $metadata = null;
            $itemType = 'unknown';
            $itemSize = null;
            
            try {
                $metadata = $this->dropbox->getMetadata($path);
                $itemType = $metadata['.tag'] ?? 'unknown'; // 'file' or 'folder'
                
                // Get file size if it's a file
                if ($itemType === 'file' && isset($metadata['size'])) {
                    $itemSize = $metadata['size'];
                }
            } catch (\Exception $e) {
                // If we can't get metadata, continue with deletion anyway
                $metadata = null;
            }
            
            // Log the deletion first
            $itemName = basename($path);
            
            \App\Models\DropboxDeletionLog::create([
                'user_id' => auth()->id(),
                'item_path' => $path,
                'item_name' => $itemName,
                'item_type' => $itemType,
                'item_size' => $itemSize,
                'metadata' => $metadata, // Store the full metadata
                'ip_address' => request()->ip(),
            ]);

            // Now perform the actual deletion
            $this->dropbox->delete($path);
            $this->successMessage = "Item deleted successfully";
            
              // Clear cache after successful deletion
            $this->clearCache($path);
        
            // If we're deleting the current folder, navigate up one level
            if ($path === $this->path) {
                $parentPath = dirname($this->path);
                $this->navigateToFolder($parentPath);
            } else {
                $this->refreshFolderContents();
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Error deleting item: ' . $e->getMessage();
        }
        
        $this->showDeleteConfirm = false;
        $this->itemToDelete = null;
    }
    public function refreshFolderStats($path=null)
    {
        if($path==null){
            $path=$this->path;
        }
        $this->folderStats = $this->countFolderContents($path);
        // Calculate space left
        if ($this->folderStats['success']) {
            $remainingBytes = max($this->totalSpace - $this->folderStats['total_size'], 0);
            $this->spaceLeft = $this->formatBytes($remainingBytes);
        }
    }
    
    public function countFolderContents($path)
    {
        $folderPath=$path;
        try {
            // Initialize counters
            $fileCount = 0;
            $folderCount = 0;
            $totalSize = 0;
            
            // Initial list request with recursive parameter to get all contents
            $listFolderResult = $this->dropbox->listFolder($folderPath);
            
            // Process the initial results
            foreach ($listFolderResult['entries'] as $entry) {
                if ($entry['.tag'] === 'file') {
                    $fileCount++;
                    $totalSize += $entry['size'];
                } elseif ($entry['.tag'] === 'folder') {
                    $folderCount++;
                }
            }
            
            // Check if there are more entries to fetch
            $cursor = $listFolderResult['cursor'];
            
            while ($listFolderResult['has_more']) {
                $listFolderResult = $this->dropbox->listFolderContinue($cursor);
                
                foreach ($listFolderResult['entries'] as $entry) {
                    if ($entry['.tag'] === 'file') {
                        $fileCount++;
                        $totalSize += $entry['size'];
                    } elseif ($entry['.tag'] === 'folder') {
                        $folderCount++;
                    }
                }
                
                $cursor = $listFolderResult['cursor'];
            }
            
            // Format size in human-readable format
            $formattedSize = $this->formatBytes($totalSize);
            
            return [
                'success' => true,
                'file_count' => $fileCount,
                'folder_count' => $folderCount,
                'total_size' => $totalSize,
                'formatted_size' => $formattedSize
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public function search()
    {
        if (empty($this->searchQuery) || strlen($this->searchQuery) < 3) {
            $this->addError('searchQuery', 'Search query must be at least 3 characters');
            return;
        }
        
        $this->isLoading = true;
        $this->searchResults = [];
        
        try {
            // Set up search options
            $searchOptions = [
                'path' => $this->searchPath ?: '',
                'file_status' => 'active',
                'max_results' => 100,
                'mode' => 'filename_and_content', // Search in both filename and content
            ];
            
            // Add file extension filters if selected
            if (!empty($this->selectedExtensions)) {
                $searchOptions['file_extensions'] = $this->selectedExtensions;
            }
            
            // Perform the search
            $response = $this->dropbox->search(
                $this->searchQuery,
            );
            
            if (isset($response['matches'])) {
                $this->searchResults = $this->processSearchResults($response['matches']);
            }
        } catch (\Exception $e) {
            $this->addError('search', 'Search error: ' . $e->getMessage());
        }
        
        $this->isLoading = false;
    }
    public function processSearchResults($matches)
{
    $results = [];
    foreach ($matches as $match) {
        // Extract metadata from the match
        $metadata = $match['metadata']['metadata'];
        
        // Add common properties
        $result = [
            'metadata' => [
                'id' => $metadata['id'],
                'name' => $metadata['name'],
                'path_display' => $metadata['path_display'],
                'path_lower' => $metadata['path_lower'],
                'type' => $metadata['.tag'] // 'folder' or 'file'
            ],
            'match_type' => $match['match_type']
        ];
        
        // Add file-specific properties if it's a file
        if ($metadata['.tag'] === 'file' && isset($metadata['size'])) {
            $result['metadata']['size'] = $metadata['size'];
            $result['metadata']['server_modified'] = $metadata['server_modified'] ?? null;
        }
        
        $results[] = $result;
    }
    return $results;
    }
    public function toggleExtension($extension)
    {
        if (in_array($extension, $this->selectedExtensions)) {
            $this->selectedExtensions = array_diff($this->selectedExtensions, [$extension]);
        } else {
            $this->selectedExtensions[] = $extension;
        }
    }
    
    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->selectedExtensions = [];
        $this->resetErrorBag();
    }
    
    public function getFileIconClass($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'text-red-500';
            case 'doc':
            case 'docx':
                return 'text-blue-500';
            case 'xls':
            case 'xlsx':
                return 'text-green-500';
            case 'ppt':
            case 'pptx':
                return 'text-orange-500';
            case 'txt':
            case 'md':
                return 'text-gray-500';
            default:
                return 'text-blue-400';
        }
    }

    
    
    public function uploadFiles($targetPath = null)
    {
        $this->validate();
        try {
            $this->isUploading = true;
            $this->uploadProgress = 0;
            $totalFiles = count($this->uploadedFile);
            $successCount = 0;
            $errorFiles = [];
            
            // Use target path if provided (for folder drops), otherwise use current path
            $uploadPath = $targetPath ?: $this->path;
           
            foreach ($this->uploadedFile as $index => $file) {
                try {
                    $fileName = $file->getClientOriginalName();
                    $tempPath = $file->getRealPath();
                    
                    // Handle file naming conflicts
                    $fileName = $this->getUniqueFileName($fileName, $uploadPath);
                   
                    $targetFilePath = $uploadPath . '/' . $fileName;
                    $targetFilePath = '/' . ltrim($targetFilePath, '/');
                   
                    // Read file content
                    $fileContents = file_get_contents($tempPath);
                   
                    // Upload to Dropbox - using the proper parameters
                    $this->dropbox->upload(
                        $targetFilePath,
                        $fileContents,
                        'add', true
                    );
                   
                    $successCount++;
                   
                    // Update progress
                    $this->uploadProgress = intval(($index + 1) / $totalFiles * 100);

               
                } catch (\Exception $e) {
                    $errorFiles[] = [
                        'name' => $fileName ?? 'Unknown file',
                        'error' => $e->getMessage()
                    ];
                   
                    // Log the error for debugging
                    Log::error('Dropbox upload error for file ' . ($fileName ?? 'Unknown') . ': ' . $e->getMessage());
                }


                $this->dispatch('fileProgress', ['progress' => $this->uploadProgress]);
            }
           
            // Set appropriate messages
            if ($successCount > 0) {
                $folderName = $targetPath ? basename($targetPath) : 'current folder';
                $this->successMessage = "{$successCount} of {$totalFiles} files uploaded successfully to {$folderName}";
            }
           
            if (count($errorFiles) > 0) {
                $this->errorMessage = count($errorFiles) . ' files failed to upload. Check logs for details.';
            }
           
            // Clear uploads and reset states
            $this->uploadedFile = [];
            $this->isUploading = false;
            $this->uploadProgress = 100;
            $this->isDragOver = false;
            $this->targetUploadPath = null;
           
            // Clear cache after successful upload
            $this->clearCache($uploadPath);
       
            // Refresh folder contents
            $this->refreshFolderContents();
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log the errors to debug
            logger()->error('Validation failed', $e->errors());
            throw $e; // Re-throw to let Livewire handle it normally
        }
    }
    // New method for folder-specific uploads
    public function uploadToFolder($folderPath)
    {
        if (empty($this->uploadedFile)) {
            return;
        }

        $this->targetUploadPath = $folderPath;
        $this->uploadFiles($folderPath);
    }

    // Method to cancel upload
    public function cancelUpload()
    {
        $this->uploadedFile = [];
        $this->uploadProgress = 0;
        $this->isDragOver = false;
        $this->isUploading = false;
        $this->targetUploadPath = null;
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    // Helper method to generate unique file names
    private function getUniqueFileName($fileName, $path)
    {
        $name = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $counter = 1;
        
        $uniqueName = $fileName;
        
        // Check if file exists in Dropbox
        while ($this->fileExistsInDropbox($path . '/' . $uniqueName)) {
            $uniqueName = $name . '_' . $counter . ($extension ? '.' . $extension : '');
            $counter++;
        }
        
        return $uniqueName;
    }

    // Helper method to check if file exists in Dropbox
    private function fileExistsInDropbox($filePath)
    {
        try {
            $filePath = '/' . ltrim($filePath, '/');
            $this->dropbox->getMetadata($filePath);
            return true;
        } catch (\Exception $e) {
            // File doesn't exist or error occurred
            return false;
        }
    }
    public function uploadFile()
    {
        $this->validate();
        try {
           
            $this->isUploading = true;
            $this->uploadProgress = 0;
            $totalFiles = count($this->uploadedFile);
            $successCount = 0;
            $errorFiles = [];
            
            foreach ($this->uploadedFile as $index => $file) {
                try {
                    $fileName = $file->getClientOriginalName();
                    $tempPath = $file->getRealPath();
                    
                    $targetPath = $this->path . '/' . $fileName;
                    $targetPath = '/' . ltrim($targetPath, '/');
                    
                    // Read file content
                    $fileContents = file_get_contents($tempPath);
                    
                    // Upload to Dropbox - using the proper parameters
                    $this->dropbox->upload(
                        $targetPath,
                        $fileContents,
                        'add',true
                    );
                    
                    $successCount++;
                    
                    // Update progress
                    $this->uploadProgress = intval(($index + 1) / $totalFiles * 100);
                    
                } catch (\Exception $e) {
                    $errorFiles[] = [
                        'name' => $fileName ?? 'Unknown file',
                        'error' => $e->getMessage()
                    ];
                    
                    // Log the error for debugging
                    Log::error('Dropbox upload error for file ' . ($fileName ?? 'Unknown') . ': ' . $e->getMessage());
                }
            }
            
            // Set appropriate messages
            if ($successCount > 0) {
                $this->successMessage = "{$successCount} of {$totalFiles} files uploaded successfully";
            }
            
            if (count($errorFiles) > 0) {
                $this->errorMessage = count($errorFiles) . ' files failed to upload. Check logs for details.';
            }
            
            $this->uploadedFiles = null;
            $this->isUploading = false;
            $this->uploadProgress = 100;
            
            // Clear cache after successful upload
            $this->clearCache($this->path);
        
            // Refresh folder contents
            $this->refreshFolderContents();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log the errors to debug
            logger()->error('Validation failed', $e->errors());
            throw $e; // Re-throw to let Livewire handle it normally
        }
    }
    
    public function refreshFolderContents()
    {
        $this->loading = true;
        
        try {
            $this->initDropboxClient();
            $response = $this->dropbox->listFolder($this->path);
            // print_r($response);exit;
            $this->contents = $response['entries'];
            $this->loading = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading Dropbox files: ' . $e->getMessage());
            $this->loading = false;
        }
    }

    protected function initDropboxClient()
    {
        if (!$this->dropbox) {
             // Get token record from database
            $tokenRecord = DropboxToken::latest()->first();
            
            // Check if token exists and if it's expired or about to expire
            if ($tokenRecord) {
                $needsRefresh = false;
                
                // Check if token has an expiration time and if it's expired or about to expire (within 5 minutes)
                if ($tokenRecord->expires_at && now()->addMinutes(5)->isAfter($tokenRecord->expires_at)) {
                    $needsRefresh = true;
                    Log::info('Dropbox token is expired or about to expire, refreshing');
                }
                
                // Refresh token if needed
                if ($needsRefresh && $tokenRecord->refresh_token) {
                    try {
                        $response = Http::timeout(15)->asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                            'grant_type' => 'refresh_token',
                            'refresh_token' => $tokenRecord->refresh_token,
                            'client_id' => config('services.dropbox.client_id'),
                            'client_secret' => config('services.dropbox.app_secret'),
                        ]);
                        
                        if ($response->successful()) {
                            $tokenData = $response->json();
                            
                            // Update access token in database
                            $tokenRecord->access_token = $tokenData['access_token'];
                            $tokenRecord->expires_at = isset($tokenData['expires_in'])
                                ? now()->addSeconds($tokenData['expires_in'] - 30)  // 30 second buffer
                                : null;
                                
                            // Update refresh token if provided
                            if (isset($tokenData['refresh_token'])) {
                                $tokenRecord->refresh_token = $tokenData['refresh_token'];
                            }
                            
                            $tokenRecord->save();
                            
                            Log::info('Dropbox token refreshed successfully', [
                                'expires_at' => $tokenRecord->expires_at
                            ]);
                        } else {
                            $errorDetails = $response->json();
                            $errorMessage = $errorDetails['error_description'] ?? $errorDetails['error'] ?? 'Unknown error';
                            Log::error("Failed to refresh Dropbox token: {$errorMessage}", [
                                'status' => $response->status(),
                                'error' => $errorDetails
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Exception while refreshing Dropbox token: ' . $e->getMessage());
                    }
                }
            }
            
            // Get the latest access token (either original or newly refreshed)
            $token = $tokenRecord->access_token ?? config('services.dropbox.token');
            
            // Create Dropbox client with the token
            $this->dropbox = new DropboxClient($token);
            
        }
    }
    
    public function loadContents()
    {
        // Record start time
        $startTime = microtime(true);
        
        // Create cache key based on path
        $cacheKey = 'dropbox_contents_' . md5($this->path);
        
        try {
            // Try to get from cache first
            $cachedData = \Cache::get($cacheKey);
            
            if ($cachedData) {
                // Load from cache
                $this->contents = $cachedData['contents'];
                $this->hasItems = $cachedData['hasItems'];
                $this->loading = false;
                
                // Calculate loading time (cache hit)
                $endTime = microtime(true);
                $loadingTime = round($endTime - $startTime, 3);
                $this->loadingTime = $loadingTime;
                
                // Log cache hit
                \Log::info("Dropbox folder loaded from server in {$loadingTime} seconds", [
                    'path' => $this->path,
                    'items_count' => count($this->contents),
                    'loading_time' => $loadingTime,
                    'cache_hit' => true
                ]);
                
                session()->flash('loading_time', "Loaded from server in {$loadingTime} seconds");
                
                return $this->contents;
            }
            
            // Cache miss - fetch from Dropbox API
            $results = $this->dropbox->listFolder($this->path);
        
            // Get all entries
            $contents = $results['entries'];
        
            // Check if there's more data to fetch
            $cursor = $results['cursor'];
            while ($results['has_more']) {
                $results = $this->dropbox->listFolderContinue($cursor);
                $contents = array_merge($contents, $results['entries']);
                $cursor = $results['cursor'];
            }
        
            // Separate folders and files
            $folders = [];
            $files = [];
        
            foreach ($contents as $item) {
                if ($item['.tag'] === 'folder') {
                    $folders[] = $item;
                } else {
                    $files[] = $item;
                }
            }
        
            // Sort files by client_modified (most recent first)
            usort($files, function($a, $b) {
                $dateA = strtotime($a['client_modified'] ?? 0);
                $dateB = strtotime($b['client_modified'] ?? 0);
                return $dateB - $dateA; // Most recent first
            });
        
            // Sort folders alphabetically
            usort($folders, function($a, $b) {
                return strcmp($a['path_lower'], $b['path_lower']);
            });
        
            // Combine folders first, then files
            $this->contents = array_merge($folders, $files);
            $this->loading = false;
            $this->hasItems = !empty($this->contents);
            
            // Cache the results for 10 minutes
            $dataToCache = [
                'contents' => $this->contents,
                'hasItems' => $this->hasItems,
                'cached_at' => now()->toDateTimeString()
            ];
            
            // \Cache::put($cacheKey, $dataToCache, now()->addMinutes(10));
            \Cache::forever($cacheKey, $dataToCache);
            
            $this->refreshFolderStats($this->path);
            
            // Calculate loading time
            $endTime = microtime(true);
            $loadingTime = round($endTime - $startTime, 3);
            $this->loadingTime = $loadingTime;
            
            // Log the loading time
            \Log::info("Dropbox folder loaded from API in {$loadingTime} seconds", [
                'path' => $this->path,
                'items_count' => count($this->contents),
                'loading_time' => $loadingTime,
                'cache_hit' => false
            ]);
            
            session()->flash('loading_time', "Loaded from API in {$loadingTime} seconds");
            
            return $this->contents;
            
        } catch (\Exception $e) {
            // Calculate loading time even for errors
            $endTime = microtime(true);
            $loadingTime = round($endTime - $startTime, 3);
            $this->loadingTime = $loadingTime;
            
            $this->errorMessage = 'Error loading contents: ' . $e->getMessage();
            $this->loading = false;
            
            // Log error with timing
            \Log::error("Dropbox folder loading failed in {$loadingTime} seconds", [
                'path' => $this->path,
                'error' => $e->getMessage(),
                'loading_time' => $loadingTime
            ]);
            
            return [];
        }
    }

    // Method to clear cache when data updates
    public function clearCache($path = null)
    {
        $pathToUse = $path ?? $this->path;
        $cacheKey = 'dropbox_contents_' . md5($pathToUse);
        
        \Cache::forget($cacheKey);
        
        // Also clear parent folder cache if needed
        $parentPath = dirname($pathToUse);
        if ($parentPath !== $pathToUse && $parentPath !== '.') {
            $parentCacheKey = 'dropbox_contents_' . md5($parentPath);
            \Cache::forget($parentCacheKey);
        }
    }

    // Method to refresh data (clear cache and reload)
    public function refreshContents()
    {
        $this->clearCache();
        $this->loadContents();
        
        session()->flash('message', 'Contents refreshed successfully!');
    }

    // Call this method when files/folders are created, deleted, or moved
    public function invalidateCache()
    {
        // Clear current path cache
        $this->clearCache();
        
        // Clear all related caches (you might want to be more specific)
        \Cache::flush(); // Use this carefully - it clears ALL cache
        
        // Or use tags if you want more control:
        // \Cache::tags(['dropbox'])->flush();
    }

    public function downloadSearchFile($path)
    {
        // Generate a temporary link and redirect to it
        try {
            $temporaryLink = $this->dropbox->getTemporaryLink($path);
            if (isset($temporaryLink)) {
                $this->dispatch('download-file', [
                    'url' => $temporaryLink,
                    'filename' => 'test'
                ]);
            }
        } catch (\Exception $e) {
            $this->addError('download', 'Download error: ' . $e->getMessage());
        }
    }
    
    public function downloadFile($path, $name)
    {
        try {
            $this->initDropboxClient();
            
            // Generate a temporary URL for the file
            $temporaryLink = $this->dropbox->getTemporaryLink($path);
            
            // Log the URL for debugging
            Log::debug('Generated temporary link', ['url' => $temporaryLink]);
            
            // Emit browser event to initiate the download
            $this->dispatch('download-file', [
                'url' => $temporaryLink,
                'filename' => $name
            ]);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error downloading file: ' . $e->getMessage());
            Log::error('Download error: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }
    }
    
    public function viewFile($path, $name)
{
    try {
        $this->initDropboxClient();
        
        // Get file metadata to check size
        $metadata = $this->dropbox->getMetadata($path);
        
        // Check if file is too large (for example, limit to 15MB for viewing)
        if (isset($metadata['size']) && $metadata['size'] > 15 * 1024 * 1024) {
            session()->flash('error', 'File is too large to preview (max 15MB)');
            return;
        }
        
        // Download file from Dropbox
        $fileResource = $this->dropbox->download($path);
        
        // Convert resource to string if needed
        $fileContent = '';
        if (is_resource($fileResource)) {
            $fileContent = stream_get_contents($fileResource);
            fclose($fileResource);
        } else {
            $fileContent = $fileResource; // In case it's already a string
        }
        
        // Get file extension from name
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        
        // Generate a unique filename with proper extension
        $filename = 'file_' . time() . '.' . $extension;
        
        // Create storage folder based on file type
        $fileType = $this->getFileType($name);
        $storageFolder = 'files/' . $fileType; // e.g., files/pdf, files/image, files/spreadsheet
        
        // Make sure directory exists
        if (!Storage::exists('public/' . $storageFolder)) {
            Storage::makeDirectory('public/' . $storageFolder);
        }
        
        // Store in local storage folder
        $storagePath = $storageFolder . '/' . $filename;
        Storage::put('public/' . $storagePath, $fileContent);
        
        // Create public URL to the file
        $publicUrl = Storage::url('public/' . $storagePath);
        
        $this->viewingFile = [
            'name' => $name,
            'path' => $path,
            'type' => $fileType,
            'storagePath' => $storagePath,
            'publicUrl' => $publicUrl,
            'extension' => $extension
        ];
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error viewing file: ' . $e->getMessage());
        \Log::error('View file error: ' . $e->getMessage());
    }
}

    public function closeFileViewer()
    {
        try {
            // Delete the file if it exists
            if ($this->viewingFile && isset($this->viewingFile['storagePath'])) {
                // Check if file exists before deleting
                if (Storage::exists("public/".$this->viewingFile['storagePath'])) {
                    // Delete the file
                    Storage::delete("public/".$this->viewingFile['storagePath']);
                    // Log successful deletion
                    Log::info('Deleted temporary file: ' . $this->viewingFile['storagePath']);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't show to user since modal is closing
            Log::error('Error deleting temporary file: ' . $e->getMessage());
        } finally {
            // Reset component state regardless of whether deletion succeeded
        
            $this->viewingFile = null;
            $this->viewingFileContents = null;
        }
        
    }
    
    protected function getFileType($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $textExtensions = ['txt', 'md', 'csv', 'json', 'xml', 'html', 'css', 'js', 'php', 'py', 'rb', 'java', 'c', 'cpp', 'h', 'log'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $pdfExtensions = ['pdf'];
        
        if (in_array(strtolower($extension), $textExtensions)) {
            return 'text';
        } elseif (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $pdfExtensions)) {
            return 'pdf';
        } else {
            return 'binary';
        }
    }

    public function navigateToFolder($path)
    {
        $this->path = $path;
        
        // Debug log
        \Log::debug('Navigating to folder: ' . $path);
        
        // Make sure Dropbox client is initialized
        $this->initDropboxClient();
        
        $this->loadContents();
        $this->updateBreadcrumbs();
    }
    
    public function navigateUp()
    {
        if (empty($this->path) || $this->path === '/') {
            $this->path = '';
        } else {
            $pathParts = explode('/', trim($this->path, '/'));
            
            // Check if we're at DEFAULT_FOLDER_DROPBOX
            // if (end($pathParts) === DEFAULT_FOLDER_DROPBOX) {
            //     return;
            // }
            
            // // Prevent navigation back from first and second level directories
            // if (count($pathParts) <= 2) {
            //     return;
            // }

            // Prevent navigation back from first and second level directories
            if (count($pathParts) <=1) {
                return;
            }

            
           
            array_pop($pathParts);
            $this->path = '/' . implode('/', $pathParts);
           
            if ($this->path === '/') {
                $this->path = '';
            }
        }
       
        $this->loadContents();
        $this->updateBreadcrumbs();
    }
    protected function updateBreadcrumbs()
    {
        $this->breadcrumbs = [];
        
        
        $currentPath = '';
        $pathParts = explode('/', trim($this->path, '/'));
        
        foreach ($pathParts as $index => $part) {
            $currentPath .= '/' . $part;
            $this->breadcrumbs[] = [
                'name' => $part,
                'path' => $currentPath,
                'active' => $index === count($pathParts) - 1
            ];
        }
    }

    // Example method for folder browser
    public function openFolderBrowser()
    {
        // Open a modal or navigate to a folder browser view
        $this->emit('openFolderBrowserModal');
    }
    public function render()
    {
        // Storage::delete('public/files/binary/file_1746889456.docx');exit;
        return view('livewire.my-documents-lists')->layout('layouts.app');
    }
    
    public function dehydrate()
    {
        // Ensure the dropbox client isn't serialized
        $this->dropbox = null;
        
        // Also clear file contents when dehydrating to avoid serializing large data
        if ($this->viewingFileContents) {
            // Check if it's a resource (shouldn't be, but just in case)
            if (is_resource($this->viewingFileContents)) {
                // Close the resource
                @fclose($this->viewingFileContents);
                $this->viewingFileContents = null;
            } else {
                // It's a string, so we can check its length
                if (strlen($this->viewingFileContents) > 100000) {
                    $this->viewingFileContents = null;
                }
            }
        }
        
        // If we cleared the contents, also clear the viewing file info
        if ($this->viewingFileContents === null) {
            $this->viewingFile = null;
        }
    }
    
    public function getFileTypeIcon($item)
    {
        if (isset($item['.tag']) && $item['.tag'] === 'folder') {
            return self::folderIcon();
        }
        
        $extension = pathinfo($item['name'] ?? '', PATHINFO_EXTENSION);
        return self::getIconByExtension(strtolower($extension));
    }
    
    private static function getIconByExtension($extension)
    {
        // Document types
        $documentTypes = ['doc', 'docx', 'rtf', 'odt', 'dot', 'dotx'];
        $pdfTypes = ['pdf'];
        $textTypes = ['txt', 'md', 'log', 'text'];
        
        // Spreadsheet types
        $spreadsheetTypes = ['xls', 'xlsx', 'csv', 'ods', 'xlsm'];
        
        // Presentation types
        $presentationTypes = ['ppt', 'pptx', 'odp', 'pps', 'ppsx'];
        
        // Image types
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'webp'];
        
        // Audio types
        $audioTypes = ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac', 'wma'];
        
        // Video types
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'flv', 'webm'];
        
        // Archive types
        $archiveTypes = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'iso'];
        
        // Code types
        $codeTypes = ['html', 'css', 'js', 'php', 'py', 'java', 'c', 'cpp', 'cs', 'rb', 'swift', 'json', 'xml', 'yml', 'yaml'];
        
        if (in_array($extension, $documentTypes)) {
            return self::wordDocumentIcon();
        } elseif (in_array($extension, $pdfTypes)) {
            return self::pdfDocumentIcon();
        } elseif (in_array($extension, $textTypes)) {
            return self::textDocumentIcon();
        } elseif (in_array($extension, $spreadsheetTypes)) {
            return self::spreadsheetIcon();
        } elseif (in_array($extension, $presentationTypes)) {
            return self::presentationIcon();
        } elseif (in_array($extension, $imageTypes)) {
            return self::imageIcon();
        } elseif (in_array($extension, $audioTypes)) {
            return self::audioIcon();
        } elseif (in_array($extension, $videoTypes)) {
            return self::videoIcon();
        } elseif (in_array($extension, $archiveTypes)) {
            return self::archiveIcon();
        } elseif (in_array($extension, $codeTypes)) {
            return self::codeIcon();
        } else {
            return self::genericFileIcon();
        }
    }
    
    public static function folderIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#FB8C00"/>
            <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="14" font-family="Arial" font-weight="bold">FOLDER</text>
        </svg>';
    }
    
    
    public static function genericFileIcon()
    {
        return '<svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
        </svg>';
    }
    
    public static function textDocumentIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#9E9E9E"/>
            <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="14" font-family="Arial" font-weight="bold">TXT</text>
        </svg>';
    }
    
    
    public static function pdfDocumentIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect width="64" height="64" rx="8" fill="#E53935"/>
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" 
                  fill="#fff" font-size="18" font-family="Arial, sans-serif" font-weight="bold">PDF</text>
        </svg>';
    }
    
    
    public static function wordDocumentIcon()
{
    return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
        <rect rx="8" width="64" height="64" fill="#1E88E5"/>
        <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">DOCX</text>
    </svg>';
}

    
public static function spreadsheetIcon()
{
    return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
        <rect rx="8" width="64" height="64" fill="#43A047"/>
        <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">XLSX</text>
    </svg>';
}

    
    public static function presentationIcon()
    {
        return '<svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2h14a1 1 0 100-2H3zm0 6a1 1 0 100 2h4a1 1 0 100-2H3zm0 3a1 1 0 100 2h4a1 1 0 100-2H3zm0 3a1 1 0 100 2h4a1 1 0 100-2H3zm7-9a1 1 0 100-2h-1a1 1 0 100 2h1zm0 3a1 1 0 100-2h-1a1 1 0 100 2h1zm0 3a1 1 0 100-2h-1a1 1 0 100 2h1zm0 3a1 1 0 100-2h-1a1 1 0 100 2h1zm7-6a1 1 0 100-2h-4a1 1 0 100 2h4zm0 3a1 1 0 100-2h-4a1 1 0 100 2h4zm-1 5a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1z" clip-rule="evenodd"></path>
        </svg>';
    }
    
    public static function imageIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#8E24AA"/>
            <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="14" font-family="Arial" font-weight="bold">IMG</text>
        </svg>';
    }
    
    
    
    public static function audioIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#7E57C2"/>
            <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">MP3</text>
        </svg>';
    }
    
    
    public static function videoIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#D32F2F"/>
            <text x="32" y="38" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">MP4</text>
        </svg>';
    }
    
    
    public static function archiveIcon()
    {
        return '<svg class="w-6 h-6 mr-2" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <rect rx="8" width="64" height="64" fill="#FDD835"/>
            <text x="32" y="38" text-anchor="middle" fill="#000" font-size="16" font-family="Arial" font-weight="bold">ZIP</text>
        </svg>';
    }
    
    
    public static function codeIcon()
    {
        return '<svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>';
    }
    public function downloadFolderAsZip($folderPath)
    {
        try {
            // Get access token
            $accessToken = $this->dropbox->getAccessToken();
            
            if (!$accessToken) {
                throw new \Exception("Dropbox not authenticated");
            }
            
            // Create a temporary file to store the ZIP
            $tempFile = tempnam(sys_get_temp_dir(), 'dropbox_zip_');
            
            // Create a cURL request to Dropbox API
            $ch = curl_init('https://content.dropboxapi.com/2/files/download_zip');
            
            // Set up headers with Dropbox API parameters
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Dropbox-API-Arg: ' . json_encode([
                    'path' => $folderPath
                ])
            ]);
            
            // Set up file to write to
            $fp = fopen($tempFile, 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            
            // Execute the request
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            // Close resources
            curl_close($ch);
            fclose($fp);
            
            // Check for errors
            if ($result === false || $httpCode !== 200) {
                @unlink($tempFile); // Delete the temporary file
                throw new \Exception("Error downloading folder: " . curl_error($ch) . " (HTTP code: $httpCode)");
            }
            
            // Get folder name for the ZIP file name
            $folderName = basename($folderPath);
            $zipFileName = $folderName . '.zip';
            
            // Return the ZIP file as a download
            return response()->download($tempFile, $zipFileName, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function masterFolderSize()
    {
        $this->loading = true;
        
        try {
            $folderPath=$this->rootPath;
            $sizeInfo = $this->getFolderSize($folderPath);
            $this->folderSize = $sizeInfo['formatted_size']??env('DEFAULT_DRIVE_SIZE');
            $this->fileCount = $sizeInfo['file_count'];
            $this->folderCount = $sizeInfo['folder_count'];
            $this->showSizeDetails = true;
        } catch (\Exception $e) {
            $this->errorMessage = 'Error calculating folder size: ' . $e->getMessage();
        }
        
        $this->loading = false;
    }
    public function showFolderSize($folderPath)
    {
        $this->loading = true;
        
        try {
            $folderPath=$this->rootPath;
            $sizeInfo = $this->getFolderSize($folderPath);
            $this->folderSize = $sizeInfo['formatted_size']??env('DEFAULT_DRIVE_SIZE');
            $this->fileCount = $sizeInfo['file_count'];
            $this->folderCount = $sizeInfo['folder_count'];
            $this->showSizeDetails = true;
        } catch (\Exception $e) {
            $this->errorMessage = 'Error calculating folder size: ' . $e->getMessage();
        }
        
        $this->loading = false;
    }
private function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}
public function getFolderSize($folderPath)
{
    try {
        $totalSize = 0;
        $fileCount = 0;
        $folderCount = 0;
        
        // Initial list request with recursive parameter
        $listFolderResult = $this->dropbox->listFolder($folderPath, true);
        
        // Process the initial results
        foreach ($listFolderResult['entries'] as $entry) {
            if (isset($entry['size']) && $entry['.tag'] === 'file') {
                $totalSize += $entry['size'];
                $fileCount++;
            } elseif ($entry['.tag'] === 'folder') {
                $folderCount++;
            }
        }
        
        // Check if there are more entries to fetch
        $cursor = $listFolderResult['cursor'];
        
        while ($listFolderResult['has_more']) {
            $listFolderResult = $this->dropbox->listFolderContinue($cursor);
            
            foreach ($listFolderResult['entries'] as $entry) {
                if (isset($entry['size']) && $entry['.tag'] === 'file') {
                    $totalSize += $entry['size'];
                    $fileCount++;
                } elseif ($entry['.tag'] === 'folder') {
                    $folderCount++;
                }
            }
            
            $cursor = $listFolderResult['cursor'];
        }
        
        // Format size in human-readable format
        $formattedSize = $this->formatBytes($totalSize);
        
        return [
            'success' => true,
            'total_size' => $totalSize,
            'formatted_size' => $formattedSize,
            'file_count' => $fileCount,
            'folder_count' => $folderCount
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
public function canNavigateUp()
{
    // Hide button at root level
    if (empty($this->path) || $this->path === '/') {
        return false;
    }
    
    $pathParts = explode('/', trim($this->path, '/'));
    
    // Hide button at first and second levels
    if (count($pathParts) <= 1) {
        return false;
    }
    
    
    // Otherwise, show the button
    return true;
}
    public function hydrate()
    {
        // Re-initialize the dropbox client when the component is hydrated
        $this->initDropboxClient();
    }
}