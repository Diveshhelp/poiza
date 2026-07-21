<?php

namespace App\Livewire;

use App\Models\MyDocuments;
use App\Traits\HasSubscriptionCheck;
use Http;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use League\Csv\Reader;
use Livewire\WithPagination;
use Log;
use Storage;
use Spatie\Dropbox\Client as DropboxClient;
use App\Models\DropboxToken;

class MyDocument extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    use WithPagination;
    private $documents;
    public $document_id;
    public $title;
    public $showModal = false;
    public $confirmingDocumentDeletion = false;
    public $documentToDelete;
    
    #[Rule('required|string|max:255')]
    public $documentTitle;
    
    #[Rule('nullable|file|mimes:csv|max:2048')]
    public $csvFile;
    public $other_info='';

    public $isImporting = false;
    public $importProgress = 0;
    public $totalRecords = 0;
    public $processedRecords = 0;
    public $searchTerm = '';
    public $privateFolder;
    public $path;
    protected $dropbox;
    public $errorMessage;
    public $team_id;
    public $user_id;
    public function mount()
    {
        $this->privateFolder=Auth::user()->email;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id = Auth::user()->id;
        $this->initDropboxClient();
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        $this->ensureStorageDirectoryExists($this->privateFolder);
        $this->loadDocuments();
        $this->createFolderIfNotExists();
        
        $this->path=Auth::user()->email;
        
    }
    
    public function createFolderIfNotExists()
    {
        $newFolderName = Auth::user()->email;
        $folderPath = ltrim($newFolderName, '/');
        $fullPath = '/' . $folderPath;
        
        try {
            // Try to get folder metadata to check if it exists
            $this->dropbox->getMetadata($fullPath);
            // Folder exists, no need to create
        } catch (\Exception $e) {
            // Folder doesn't exist, create it
            try {
                $this->dropbox->createFolder($fullPath);
            } catch (\Exception $createException) {
                $this->errorMessage = 'Error creating folder: ' . $createException->getMessage();
            }
        }
    }

    public function loadDocuments()
    {
        $query =  MyDocuments::query();
        $query->where('user_id', Auth::user()->id);
        $query->where('team_id', Auth::user()->currentTeam->id);
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('document_title', 'like', '%' . $this->searchTerm . '%');
            });
        }
       return $query->latest()->paginate(10);
    }

    public function createDocument()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editDocument(MyDocuments $document)
    {
        $this->document_id = $document->id;
        $this->documentTitle = $document->document_title;
        $this->other_info= $document->other_info;
        $this->showModal = true;
    }

    public function saveDocument()
    {
        $this->validate([
            'documentTitle' => 'required|string|max:255',
        ]);

       
        if ($this->document_id) {
            // Fetch the existing document to compare old and new values
            $document = MyDocuments::find($this->document_id);
            
            // Check if the document title (folder name) has changed
            $oldTitle = $document->document_title;
            $newTitle = $this->documentTitle;
            
            // Update document in database
            $document->other_info = $this->other_info;
            $document->document_title = $newTitle;
            $document->save();
            
            // Handle Dropbox folder renaming if the name has changed
            if ($oldTitle !== $newTitle) {
                try {
                    // Construct old and new folder paths
                    $oldFolderPath = $this->path . '/' . trim($oldTitle);
                    $newFolderPath = $this->path . '/' . trim($newTitle);
                    
                    // Check if the new folder name already exists
                    try {
                        $this->dropbox->getMetadata($newFolderPath);
                        // If we reach here, the new folder name already exists
                        $this->dispatch('notify-warning', "Document updated but folder name wasn't changed because '{$newTitle}' already exists in Dropbox");
                    } catch (\Exception $folderCheckException) {
                        // If not found exception, it means we can safely rename
                        if (strpos($folderCheckException->getMessage(), 'not_found') !== false) {
                            // Rename the folder in Dropbox
                            $this->dropbox->move($oldFolderPath, $newFolderPath);
                            $this->dispatch('notify-success', 'Document and folder name updated successfully!');
                        } else {
                            // Some other error occurred during checking
                            throw $folderCheckException;
                        }
                    }
                } catch (\Exception $e) {
                    // Handle any Dropbox-related exceptions
                    $this->dispatch('notify-error', 'Document updated but error renaming folder: ' . $e->getMessage());
                }
            } else {
                // Document title didn't change, so no need to update Dropbox
                $this->dispatch('notify-success', 'Document updated successfully!');
            }
        } else {

            if( $this->checkbeforeCreate()==true)
            {
                MyDocuments::create([
                    'document_title' => $this->documentTitle,
                    'other_info' => $this->other_info,
                    'user_id' => Auth::id(),
                    'team_id' => Auth::user()->currentTeam->id,
                ]);
                
                $nestedPath = $this->privateFolder."/".$this->documentTitle;
                if (!Storage::disk('public')->exists($nestedPath)) {
                    Storage::disk('public')->makeDirectory($nestedPath);
                }
                $folderPath = $this->path . '/' . trim($this->documentTitle);
                try {
                    $this->dropbox->createFolder($folderPath);
                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), 'path/conflict') === false) {
                        throw $e;
                    }
                }
                $this->dispatch('notify-success', 'Document created successfully!');
                        
                $this->resetForm();
                $this->loadDocuments();
            }else{
                $this->dispatch('notify-error', $this->errorMessage);

            }
        }

    }
    /**
 * Download a folder from Dropbox as a ZIP file
 * 
 * @param string $folderPath Path to the folder in Dropbox
 * @return \Illuminate\Http\Response
 */
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
    public function checkbeforeCreate(){
        try {
            $folderPath = $this->path . '/' . trim($this->documentTitle);
            
            // First check if the folder exists
            try {
                // Method to check if folder exists - could be getMetadata or similar
                $metadata = $this->dropbox->getMetadata($folderPath);
                
                // If we reach here without exception, folder exists
                $this->errorMessage = "Folder '{$this->documentTitle}' already exists! Please choose another name.";
                // Simply return to stop execution
                return false;
            } catch (\Exception $folderCheckException) {
                // If the exception contains "not_found", the folder doesn't exist
                if (strpos($folderCheckException->getMessage(), 'not_found') !== false) {
                    // Continue with folder creation since it doesn't exist
                    $this->dropbox->createFolder($folderPath);
                    $this->successMessage = "Folder '{$this->documentTitle}' created successfully";
                    return true;
                } else {
                    // Some other error occurred during checking
                    $this->errorMessage = "Folder '{$this->documentTitle}' already exists! Please choose another name.";
                    return false;
                }
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occurred
            $this->errorMessage = 'Error with folder operation: ' . $e->getMessage();
            return false;
        }
    }
    public function hydrate()
    {
        // Re-initialize the dropbox client when the component is hydrated
        $this->initDropboxClient();
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
    
    public function confirmDocumentDeletion($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->confirmingDocumentDeletion = true;
    }

    public function deleteDocument()
    {
        $document = MyDocuments::find($this->documentToDelete);
        
        if ($document && $document->user_id === Auth::id()) {
            $document->delete();
            
            $this->dispatch('notify-success', 'Document deleted successfully!');
        }

        $this->confirmingDocumentDeletion = false;
        $this->documentToDelete = null;
        $this->loadDocuments();
    }
    
    public function importCSV()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv|max:2048',
        ]);
        
        try {
            $csv = Reader::createFromPath($this->csvFile->path(), 'r');
            $csv->setHeaderOffset(0);
            
            $records = $csv->getRecords();
            $count = 0;
            
            foreach ($records as $record) {
                if (isset($record['title']) && !empty($record['title'])) {
                    MyDocuments::create([
                        'document_title' => $record['title'],
                        'other_info' => $record['other_info'],
                        'user_id' => Auth::id(),
                        'team_id' => Auth::user()->currentTeam->id,
                    ]);

                    $nestedPath = $this->privateFolder."/".$record['title'];
                    // Check if the nested directory exists
                    if (!Storage::disk('public')->exists($nestedPath)) {
                        // Create the nested directory
                        Storage::disk('public')->makeDirectory($nestedPath);
                    }
        
                    
                    $count++;
                }
            }
            
            $this->dispatch('notify-success',"{$count} documents imported successfully!");
            
            $this->csvFile = null;
            $this->loadDocuments();
        } catch (\Exception $e) {
            $this->dispatch('notify-error','Error importing CSV: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->document_id = null;
        $this->documentTitle = '';
        $this->other_info='';
        $this->showModal = false;
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_documents.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['title', 'other_info']);
            
            // Sample data
            fputcsv($file, ['Project Plan 2025', 'Detailed project timeline and resource allocation for 2025 fiscal year']);
            fputcsv($file, ['Financial Report Q1', 'First quarter financial summary with profit/loss statements']);
            fputcsv($file, ['Marketing Strategy', 'Digital marketing plan for upcoming product launch']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function updatedSearchTerm()
    {
        $this->loadDocuments();
    }

    public function resetSearch()
    {
        $this->searchTerm = '';
    }

    private function ensureStorageDirectoryExists($directory)
    {
        if (!Storage::disk('public')->exists($directory)) {
            return Storage::disk('public')->makeDirectory($directory);
        }
        
        return true;
    }
   
    public function render()
    {
        return view('livewire.my-documents', [
            'documents' => $this->loadDocuments(),
        ])->layout('layouts.app');
    }
}