<?php

namespace App\Livewire;

use App\Jobs\DropboxBackupJob;
use App\Models\Department;
use App\Models\DocAttachment;
use App\Models\Documents;
use App\Models\DropboxToken;
use App\Models\User;
use App\Services\SubscriptionCheckerService;
use Auth;
use Carbon\Carbon;
use Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use App\Models\DocCategory;
use App\Models\Ownership;
use Log;
use App\Traits\HasSubscriptionCheck;
use Spatie\Dropbox\Client as DropboxClient;
class DocumentManager extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    public $ownership_name;

    public $moduleTitle = DOCUMENT_TITLE;

    public $department_id;

    public $doc_title;

    public $doc_name;

    public $doc_validity = 1;

    public $doc_validity_list = DOC_VALIDITY;

    public $doc_renewal_dt;

    public $doc_update_date;

    public $doc_file;
    public $doc_note;
    public $doc_year;
    public $doc_number;
    public $doc_info;
    public $users;

    public $departments;

    public $doc_files = [];

    public $temp_files = [];
    
    public $doc_categories;
    
    public $doc_categories_id;

    public $iterations = 0; // Add this to help reset the file input

    public $isEditing = false;

    public $document;

    public $existing_attachments = [];

    public $ownerships;

    public $new_category_title;
    
    public $new_ownership_title;

    public $commonCreateSuccess;

    public $commonUpdateSuccess;

    public $commonDeleteSuccess;

    public $commonNotDeleteSuccess;
    public $new_category_id;
    public $new_owner_id;
    public $user_id;
    public $doc_expire_date;
    public $team_id_uuid;
    public $team_id;
    
    protected $dropbox;
    #[Rule('boolean')]
    public $share_with_firm = false;
    public $is_completed=0;
    public function mount($uuid = null)
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }

        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id=Auth::user()->id;
        $this->team_id_uuid = Auth::user()->currentTeam->uuid;
        $this->users = User::where("current_team_id",$this->team_id)->get();
        $this->departments = Department::where("team_id",$this->team_id)->get();
        $this->doc_categories = DocCategory::where("team_id",$this->team_id)->get();
        $this->ownerships = Ownership::where("team_id",$this->team_id)->get();

        if ($uuid) {
            $this->isEditing = true;
            $this->document = Documents::with('attachments')->where('uuid', $uuid)->firstOrFail();
            $this->fillFormData();
            // $this->doc_categories_id = $this->document->doc_categories_id;
        }
        $this->initDropboxClient();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);

       
        $this->doc_categories_id=DocCategory::where("team_id",$this->team_id)->orderBy('id','ASC')->first()->id??'';
        $this->department_id= Department::where("team_id",$this->team_id)->orderBy('id','ASC')->first()->id??'';
        $this->ownership_name= Ownership::where("team_id",$this->team_id)->orderBy('id','ASC')->first()->id??'';
    
    }

    protected function fillFormData()
    {
        $this->ownership_name = $this->document->ownership_name;
        $this->department_id = $this->document->department_id;
        $this->doc_title = $this->document->doc_title;
        $this->doc_name = $this->document->doc_name;
        $this->doc_validity = $this->document->doc_validity;
        $this->doc_renewal_dt = $this->document->doc_renewal_dt;
        $this->doc_update_date = $this->document->doc_update_date;
        $this->existing_attachments = $this->document->attachments;
        $this->doc_year = $this->document->doc_year;
        $this->doc_number = $this->document->doc_number;
        $this->doc_note = $this->document->doc_note;
        $this->doc_categories_id = $this->document->doc_categories_id;
        $this->doc_expire_date=$this->document->doc_expire_date;
        $this->share_with_firm=(bool) $this->document->share_with_firm;
    }

    public function updatedDocValidity()
    {
        if ($this->doc_validity === 1) {
            $this->doc_renewal_dt = 'NO';
        } else {
            $this->doc_renewal_dt = null;
        }
    }

    protected function rules()
    {
        return [
            'ownership_name' => 'required',
            'department_id' => 'required|exists:departments,id',
            'doc_title' => 'required|string|max:255',
            'doc_validity' => 'required',
            'doc_renewal_dt' => 'nullable',
            // 'doc_update_date' => 'nullable|date',
            'doc_files.*' => 'nullable',
            'doc_categories_id' => $this->new_category_title ? 'nullable' : 'required|exists:doc_categories,id',
            'new_category_title' => $this->doc_categories_id ? 'nullable' : 'required|string|max:255',
            'doc_year' => 'nullable'
        ];
    }

    public function saveNewCategory()
    {
        $this->validate([
            'new_category_title' => 'required|string|max:255'
        ]);

        $category = DocCategory::create([
            'uuid' => Str::uuid(),
            'category_title' => $this->new_category_title,
            'row_status' => 1,
            'team_id' => $this->team_id,
            'created_by' => auth()->id()
        ]);

        // Update the categories list and select the new category
        $this->doc_categories = DocCategory::where("team_id",$this->team_id)->get();
        $this->doc_categories_id = $category->id;
        $this->new_category_title = '';

        // Dispatch event for UI updates
        $this->dispatch('category-added', [
            'id' => $category->id,
            'title' => $category->category_title
        ]);

        $this->new_category_id=$category->id;
    }

    private function handleFileUploads($document)
    {
        if($this->doc_file!=[]){
            foreach ($this->doc_file as $file) {
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Store file in a folder named by document_id
                $path = $file->storeAs(
                    "documents/{$this->team_id_uuid}/{$document->id}", 
                    $fileName, 
                    'public'
                );

                $folderPath = "{$this->team_id_uuid}/{$document->id}";
                $dropboxPath = "/{$folderPath}/{$fileName}";
                Log::info($dropboxPath);
                // Get file contents
                $fileContents = file_get_contents($file->getRealPath());
                
                // Upload to Dropbox using your existing client
                $this->dropbox->upload($dropboxPath, $fileContents);


                // Create attachment record
                $attachment = DocAttachment::create([
                    'uuid' => Str::uuid()->toString(),
                    'documents_id' => $document->id,
                    'file_name' => $fileName,
                    'original_file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);

            }
            
        }

    }

    public function saveDocument()
    {
        
        try {
            // Validate all inputs first
            $validatedData = $this->validate();
            
            // Process categories and ownership first (potential DB operations)
            $newCategoryId = $this->processCategory();
            $newOwnerId = $this->processOwnership();
       // Prepare common document data
            $documentData = [
                'ownership_name' => $newOwnerId,
                'department_id' => $this->department_id,
                'doc_title' => $this->doc_title,
                'doc_name' => $this->doc_name,
                'doc_validity' => $this->doc_validity,
                'doc_renewal_dt' => $this->doc_renewal_dt,
                'doc_update_date' => !empty($this->doc_update_date) 
                    ? $this->doc_update_date
                    : null,
                'doc_note' => $this->doc_note,
                'doc_year' => $this->doc_year,
                'doc_number' => $this->doc_number, 
                'doc_info' => $this->doc_info,
                'doc_categories_id' => $newCategoryId,
                'created_by'=>$this->user_id,
                'doc_expire_date' => !empty($this->doc_expire_date) 
                    ? $this->doc_expire_date
                    : null,
                'share_with_firm'=>(bool) $this->share_with_firm,
                'team_id'=>$this->team_id
            ];


            // DB::beginTransaction();
            
            // Create or update document
            if ($this->isEditing) {
                $this->document->update($documentData);
                $document = $this->document;
                $document_id=$document->id;
            } else {
                $documentData['uuid'] = Str::uuid()->toString();
                $document = Documents::create($documentData);
                $document_id=$document->id;
            }
            
            
            // Handle file uploads
            $this->handleFileUploads($document);
            
            // $this->upload($document);
            
            // Dispatch the Dropbox backup job
            DropboxBackupJob::dispatch($this->team_id_uuid,$document_id);
            
        

            // DB::commit();
            
            // Success notification
            $this->dispatch('notify-success', $this->commonCreateSuccess);
            
            // Reset and redirect
            $this->resetForm();
            
            return redirect()->route('document-collections');
            
        } catch (\Exception $e) {
            // DB::rollBack();
            
            // Log the error for debugging
            logger()->error('Document save error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'document_id' => $this->isEditing ? $this->document->id : null
            ]);
            
            $this->dispatch('notify-error', 'Error '.($this->isEditing ? 'updating' : 'creating').' document: '.$e->getMessage());
            return null;
        }
    }

    /**
     * Process category logic and return appropriate category ID
     * 
     * @return mixed
     */
    private function processCategory()
    {
        if (!empty($this->new_category_title)) {
            $this->saveNewCategory();
            return $this->new_category_id;
        } else {
            return $this->doc_categories_id;
        }
    }

    /**
     * Process ownership logic and return appropriate ownership ID
     * 
     * @return mixed
     */
    private function processOwnership()
    {
        if (!empty($this->new_ownership_title)) {
            $this->saveNewOwnership();
            return $this->new_ownership_id;
        } else {
            return $this->ownership_name;
        }
    }
    public function saveNewOwnership()
    {
        $this->validate([
            'new_ownership_title' => 'required|string|max:255'
        ]);
    
        $ownership = Ownership::create([
            'owner_title' => $this->new_ownership_title,
            'team_id' => $this->team_id
        ]);
    
        // Update the ownership list and select the new ownership
        $this->ownerships = Ownership::where("team_id",$this->team_id)->get();
        $this->ownership_name = $ownership->id;
        $this->new_ownership_title = '';
    
        // Dispatch events for UI updates
        $this->dispatch('ownership-added', [
            'id' => $ownership->id,
            'title' => $ownership->owner_title
        ]);
        $this->new_ownership_id=$ownership->id;
    }

    public function downloadAttachment($uuid)
    {
        $attachment = DocAttachment::where('uuid', $uuid)->firstOrFail();
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            $this->dispatch('notify-error', 'File not found');
            return;
        }

        return response()->streamDownload(function () use ($attachment) {
            echo Storage::disk('public')->get($attachment->file_path);
        }, $attachment->original_file_name);
    }
    public function resetForm()
    {
        // Reset all form fields to initial state
        $this->reset([
            'ownership_name',
            'department_id',
            'doc_title',
            'doc_name',
            'doc_validity',
            'doc_renewal_dt',
            'doc_update_date',
            'doc_note',
            'doc_year',
            'doc_number',
            'doc_info',
            'doc_expire_date'
        ]);

        // Reset file uploads
        $this->doc_file = [];
        $this->temp_files = [];

        // Reset validation errors
        $this->resetValidation();

        // Reset any error messages
        $this->resetErrorBag();

        // Emit event for JavaScript listeners if needed
        $this->dispatch('form-reset');
    }
    public function updatedTempFiles()
    {
        $this->validate();

        // If no existing files, initialize as empty array
        if (!is_array($this->doc_file)) {
            $this->doc_file = [];
        }

        // Add new files to existing collection
        if (is_array($this->temp_files) || $this->temp_files instanceof Collection) {
            foreach ($this->temp_files as $file) {
                $this->doc_file[] = $file;
            }
        }

        // Reset temporary files
        // $this->temp_files = [];
    }

    public function removeFile($index)
    {
        if (isset($this->doc_file[$index])) {
            unset($this->doc_file[$index]);
            // Reindex array
            $this->doc_file = array_values($this->doc_file);
        }
    }

    public function deleteAttachment($uuid)
    {
        try {
            $attachment = DocAttachment::where('uuid', $uuid)->firstOrFail();

            // Delete the file from storage
            Storage::disk('public')->delete($attachment->file_path);

            // Delete the record
            $attachment->delete();

            $this->existing_attachments = $this->existing_attachments->filter(function ($item) use ($uuid) {
                return $item->uuid !== $uuid;
            });

            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
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
    public function upload($document)
    {
        if($this->doc_file != []) {
            foreach ($this->doc_file as $file) {
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $folderPath = "{$this->team_id_uuid}/{$document->id}";
                $dropboxPath = "/{$folderPath}/{$fileName}";
                Log::info($dropboxPath);
                // Get file contents
                $fileContents = file_get_contents($file->getRealPath());
                
                // Upload to Dropbox using your existing client
                $this->dropbox->upload($dropboxPath, $fileContents);
                
                try {
                    // According to Dropbox API docs, the correct format for settings is:
                  // The correct format based on the curl example
                    // $settings = [
                    //     "settings" => [
                    //         "access" => "viewer",
                    //         "allow_download" => true,
                    //         "audience" => "public",
                    //         "requested_visibility" => "password",
                    //         "link_password" => "password"
                    //     ]
                    // ];
                    
                    // // If you want to add expiration
                    // $settings["settings"]["expires"] = date('Y-m-d\TH:i:s\Z', strtotime('+5 minutes'));
                    
                    // $shareResponse = $this->dropbox->createSharedLinkWithSettings($dropboxPath, $settings);
                    // $sharedUrl = $shareResponse['url'];
                    
                    Log::info($sharedUrl);
                    
                    // Create attachment record
                    // $attachment = DocAttachment::create([
                    //     'uuid' => Str::uuid()->toString(),
                    //     'documents_id' => $document->id,
                    //     'file_name' => $fileName,
                    //     'original_file_name' => $file->getClientOriginalName(),
                    //     'file_path' => $dropboxPath,
                    //     'file_type' => $file->getClientOriginalExtension(),
                    //     'file_size' => $file->getSize(),
                    //     'dropbox_shared_url' => $sharedUrl
                    // ]);
                } catch (\Exception $e) {
                    // Log the error for debugging
                    Log::error('Dropbox sharing error: ' . $e->getMessage());
                    
                    // Try a simpler sharing approach without password or expiration
                    try {
                        $simpleShareResponse = $this->dropbox->createSharedLinkWithSettings($dropboxPath);
                        $sharedUrl = $simpleShareResponse['url'];
                        Log::info('Simple share URL: ' . $sharedUrl);
                        
                        // Create attachment record without special sharing options
                        // $attachment = DocAttachment::create([
                        //     'uuid' => Str::uuid()->toString(),
                        //     'documents_id' => $document->id,
                        //     'file_name' => $fileName,
                        //     'original_file_name' => $file->getClientOriginalName(),
                        //     'file_path' => $dropboxPath,
                        //     'file_type' => $file->getClientOriginalExtension(),
                        //     'file_size' => $file->getSize(),
                        //     'dropbox_shared_url' => $sharedUrl
                        // ]);
                    } catch (\Exception $e2) {
                        Log::error('Dropbox simple sharing also failed: ' . $e2->getMessage());
                        
                        // Create attachment record even without shared URL
                        // $attachment = DocAttachment::create([
                        //     'uuid' => Str::uuid()->toString(),
                        //     'documents_id' => $document->id,
                        //     'file_name' => $fileName,
                        //     'original_file_name' => $file->getClientOriginalName(),
                        //     'file_path' => $dropboxPath,
                        //     'file_type' => $file->getClientOriginalExtension(),
                        //     'file_size' => $file->getSize()
                        // ]);
                    }
                }
            }
        }
    }
    public function hydrate()
    {
        // Re-initialize the dropbox client when the component is hydrated
        $this->initDropboxClient();
    }
    public function render()
    {
   
        return view('livewire.Document.document-manager')->layout('layouts.app');
    }
}