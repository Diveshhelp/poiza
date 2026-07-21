<?php

namespace App\Livewire;

use App\Mail\DocumentAttachmentsMail;
use App\Models\DocSelections;
use App\Models\DocumentNotes;
use App\Models\Documents;
use App\Models\DocAttachment;
use App\Models\EmailAttachments;
use App\Models\EmailLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Str;
use Auth;
use Log;
use Mail;
use Illuminate\Mail\Mailables\Attachment;
use App\Models\DocCategory;
use App\Models\Ownership;

class DocumentCollection extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $userRoles;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $searchQuery = '';
    public $moduleTitle = DOCUMENT_TITLE;
    
    public $moduleInnerTitle=NOTE_TITLE;
    public $perPage = PER_PAGE;
    public $doc_categories;

    public $filterDocTitle = '';
    public $filterDocName = '';
    public $filterOwnerName = '';
    public $filterDocValidity = '';
    public $filterRenewalDate = '';
    public $filterDocNote = '';
    public $filterDocCategory = '';
    public $ownerships = [];
    
    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;
    
    public $commonNoteCreateSuccess;
    public $commonNoteUpdateSuccess;
    public $commonNoteDeleteSuccess;
    public $addingNote;
    
    public $showModal = false;
    public $selectedDocument;
    public $selectedSubDocument;

    protected $listeners = ['documentDeleted' => '$refresh'];
    public $doc_validity_list = DOC_VALIDITY;
    protected $documents = [];
    public $files=[];
    public $parentUUID;
    public $users = [];
    public $selectedDocumentUuid = null;
    public $emailAddress = '';
    public $emailSubject = '';
    public $emailMessage = '';
    public $selectedDocumentTitle;
    public $emailLogs = [];
    public $user_id;
    protected $rules = [
        'emailAddress' => 'required|email',
        'emailSubject' => 'required|string|max:100',
        'emailMessage' => 'nullable|string|max:500',
    ];
    public $team_id;
    
    public $filterGlobalData;
    public $showExpiringOnly = false;
    public $expiringCount = 0;
    public $team_id_uuid;
    
    public $selectedItems = [];
    public $masterSelection = false;

    public $document_id;
    public $created_by;
    public $doc_status;    
    public $alreadySelectedDocs=[];
    public function mount()
    {
        $this->team_id_uuid = Auth::user()->currentTeam->uuid;
        $this->user_id = Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->team_name = Auth::user()->currentTeam->name;
        $this->users = User::where("current_team_id",$this->team_id)->get();
        $this->doc_categories = DocCategory::where("team_id",$this->team_id)->get();
        $this->ownerships = Ownership::where("team_id",$this->team_id)->get();

        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
        $this->commonNoteCreateSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_CREATE_SUCCESS);
        $this->commonNoteUpdateSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_UPDATE_SUCCESS);
        $this->commonNoteDeleteSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_DELETE_SUCCESS);
      
        $this->loadDocuments();

        $this->userRoles = !empty(Auth::User()->user_role) 
        ? explode(',', Auth::User()->user_role) 
        : [];
        
    }

   
    public function downloadAttachment($attachmentId)
    {
        try {
            // Using the correct model DocAttachment instead of Attachment
            $attachment = DocAttachment::findOrFail($attachmentId);
            if (!Storage::disk('public')->exists($attachment->file_path)) {
                $this->dispatch('notify-error', 'File not found');
                return;
            }
    
            return response()->streamDownload(function () use ($attachment) {
                echo Storage::disk('public')->get($attachment->file_path);
            }, $attachment->original_file_name);
            
        } catch (\Exception $e) {
            session()->flash('notify-error', 'Failed to download file: ' . $e->getMessage());
            return null;
        }
    }

    public function showDocumentDetails($uuid)
    {

        $this->selectedDocument = Documents::with(['attachments', 'owner','subattachments','doc_categories','ownership'])->where('uuid', $uuid)->first();
        $this->selectedSubDocument = Documents::with(['attachments', 'owner'])->where('parent_id', $this->selectedDocument->id)->get();

        $this->dispatch('show-document-modal', ['document' => $this->selectedDocument,'subdocument' => $this->selectedSubDocument]);
        $this->loadDocuments();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDocument = null;
    }

    public function deleteDocument($uuid)
    {
        try {
            $document = Documents::where('uuid', $uuid)->firstOrFail();
            
            // Delete associated attachments
            if ($document->attachments) {
                foreach ($document->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
            
            $document->delete();
            
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
        }
    }

    public function updateStatus($uuid, $status)
    {
        try {
            $document = Documents::where('uuid', $uuid)->firstOrFail();
            $document->update(['status' => $status]);
            
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error updating status');
        }
    }

    
    protected function loadDocuments()
    {
        $query = Documents::with(['attachments', 'owner', 'ownership']);

        
        if($this->filterGlobalData!=""){
            $query->when($this->filterGlobalData, function ($query) {
                $query->where(function($q) {
                    $q->where('doc_title', 'like', '%' . $this->filterGlobalData . '%')
                    ->orWhere('doc_number', 'like', '%' . $this->filterGlobalData . '%')
                    ->orWhere('doc_info', 'like', '%' . $this->filterGlobalData . '%')
                    ->orWhere('doc_validity', 'like', '%' . $this->filterGlobalData . '%')
                    ->orWhere('doc_year', 'like', '%' . $this->filterGlobalData . '%');
                });
            });
    
        }else{
            // Apply filters
            $query->when($this->filterDocTitle, function ($query) {
                $query->where('doc_title', 'like', '%' . $this->filterDocTitle . '%');
            })
            ->when($this->filterDocCategory, function ($query) {
                $query->where('doc_categories_id', $this->filterDocCategory);
            })
            ->when($this->filterDocName, function ($query) {
                $query->where('doc_name', 'like', '%' . $this->filterDocName . '%');
            })
            ->when($this->filterOwnerName, function ($query) {
                $query->where('ownership_name', $this->filterOwnerName);
            })
            ->when($this->filterDocValidity, function ($query) {
                $query->where('doc_validity', $this->filterDocValidity);
            })
            ->when($this->filterRenewalDate, function ($query) {
                $query->whereDate('doc_renewal_dt', $this->filterRenewalDate);
            })
            ->when($this->filterDocNote, function ($query) {
                $query->where('doc_note', 'like', '%' . $this->filterDocNote . '%');
            });
        }

            
        // Apply expiring documents filter if enabled
        if ($this->showExpiringOnly) {
            $today = Carbon::now();
            $oneMonthLater = Carbon::now()->addMonth();
            
            $query->where(function ($q) use ($today, $oneMonthLater) {
                $q->whereBetween('doc_expire_date', [$today, $oneMonthLater])
                ->orWhereBetween('doc_renewal_dt', [$today, $oneMonthLater]);
            });
        }
        
        // Validate sort field
        $this->sortField = in_array($this->sortField, ['created_at', 'doc_title', 'doc_name', 'doc_validity', 'doc_categories_id']) 
        ? $this->sortField 
        : 'created_at';

        $query->where('team_id',$this->team_id);
        $query->whereNull("parent_id");
        $userRoles = $this->userRoles;
        $query->where(function($query) use($userRoles) {
            // Multiple safety checks
            $isAdmin = false;
            if (!empty($userRoles) && is_array($userRoles)) {
                $isAdmin = array_intersect(['1', '2'], $userRoles);
            }
            
            if ($isAdmin) {
                // Admin/Super Admin: Show shared documents from all users + their own documents
                $query->where(function($q) {
                    $q->where('share_with_firm', 1)  // All shared documents
                      ->orWhere('created_by', $this->user_id); // Plus their own documents
                });
            } else {
                // Regular users: Show only their own documents
                $query->where("created_by", $this->user_id);
            }
        });
        
        $query->orderBy($this->sortField, $this->sortDirection);

        // Get paginated results
        $this->documents = $query->paginate($this->perPage);
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadDocuments(); // Reload documents with new sorting
    }

    public function updatedPage()
    {
        $this->loadDocuments(); // Reload documents when page changes
    }
    public function toggleExpiringFilter()
    {
        $this->countExpiringDocuments();
        $this->showExpiringOnly = !$this->showExpiringOnly;
        $this->resetPage(); // Reset pagination when filter changes
    }

    // Add this method to count expiring documents (call this in mount() or hydrate())
    public function countExpiringDocuments()
    {
        $today = Carbon::now();
        $oneMonthLater = Carbon::now()->addMonth();
        
        $this->expiringCount = Documents::where('created_by', $this->user_id)
            ->where(function ($q) use ($today, $oneMonthLater) {
                $q->whereBetween('doc_expire_date', [$today, $oneMonthLater])
                ->orWhereBetween('doc_renewal_dt', [$today, $oneMonthLater]);
            })
            ->whereNull("parent_id")
            ->count();
    }
    public function searchDocuments($filters)
    {
        // Update filter values
        $this->filterDocCategory = $filters['docCategory'];
        $this->filterDocTitle = $filters['docTitle'];
        $this->filterDocName = $filters['docName'];
        $this->filterOwnerName = $filters['ownerName'];
        $this->filterDocValidity = $filters['docValidity'];
        $this->filterRenewalDate = $filters['docRenewalDate'];
        $this->filterDocNote = $filters['docNote'];
    }
    public function resetSearch()
    {
        // Reset all filters
        $this->filterDocCategory = '';
        $this->filterDocTitle = '';
        $this->filterDocName = '';
        $this->filterOwnerName = '';
        $this->filterDocValidity = '';
        $this->filterRenewalDate = '';
        $this->filterDocNote='';

        // // Reset pagination
        // $this->resetPage();

        // // Reload documents without filters
        // $this->loadDocuments();
    }


    public function getParentDocument($uuid)
    {
        $this->parentUUID = $uuid;
        
        // Load parent document with subdocuments and their attachments using nested eager loading
        $document = Documents::with(['subDocuments.attachments'])
            ->where('uuid', $uuid)
            ->first();
        
        if (!$document) {
            return [
                'doc_title' => 'Document not found',
                'sub_documents' => []
            ];
        }
        
        // Format the subdocuments to include attachment information
        $formattedSubdocs = $document->subDocuments->map(function($subDoc) {
            // Format each attachment to include URL and other necessary details
            $formattedAttachments = $subDoc->attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id,
                    'name' => $attachment->original_file_name,
                    'type' => $attachment->file_type,
                    'size' => $attachment->file_size,
                    'url' => url('storage/' . $attachment->file_path)
                ];
            });
            
            // Create a new array with the subdocument properties we need
            return [
                'id' => $subDoc->id,
                'uuid' => $subDoc->uuid,
                'doc_title' => $subDoc->doc_title,
                'doc_name' => $subDoc->doc_name,
                'doc_number' => $subDoc->doc_number,
                'doc_info' => $subDoc->doc_info,
                'doc_update_date' => $subDoc->doc_update_date,
                'doc_validity' => $subDoc->doc_validity,
                'attachments' => $formattedAttachments
            ];
        });
        
        return [
            'doc_title' => $document->doc_title,
            'sub_documents' => $formattedSubdocs
        ];
    }

    public function addSubDocument($parentId, $formData)
    {
        $parent = Documents::where('uuid', $parentId)->first();
        if($formData['title']!=""){
            $subDocument = new Documents();
            $subDocument->uuid = Str::uuid();
            $subDocument->doc_title = $formData['title'];
            $subDocument->doc_name = $formData['doc_name'];
            $subDocument->doc_update_date = Carbon::parse($formData['doc_update_date'])->format('Y-m-d');
            $subDocument->doc_validity = $formData['validity'];
            $subDocument->parent_id = $parent->id;
            $subDocument->ownership_name = auth()->id();
            $subDocument->doc_number = $formData['doc_number'];
            $subDocument->doc_info = $formData['doc_info'];
            
            $subDocument->save();
            
            // Handle file uploads
            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file in a folder named by document_id
                    $path = $file->storeAs(
                        "documents/{$this->team_id_uuid}/{$parent->id}", 
                        $fileName, 
                        'public'
                    );

                    // Create attachment record
                    $attachment = DocAttachment::create([
                        'uuid' => Str::uuid()->toString(),
                        'documents_id' => $subDocument->id,
                        'file_name' => $fileName,
                        'original_file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                        'is_parent' => true
                    ]);
                }

                // Reset files array after upload
                $this->files = [];
            }
            // Show success message
            $this->dispatch('notify-success', 'Document saved successfully!');
            return [
                'success' => true,
                'document' => $subDocument
            ];
        }else{
           
            $this->dispatch('notify-error', 'Please fill the required details!');
            return [
                'success' => false
            ];
        }
    }
    public function deleteSubDocument($uuid)
    {
        $document = Documents::where('uuid', $uuid)->first();
        if ($document) {
            $document->delete();
            $this->dispatch('notify-success', 'Document deleted successfully!');
            return ['success' => true];
        }
        return ['success' => false];
    }
    public function deleteAttachment($id)
    {
        $attachment = DocAttachment::findOrFail($id);
        
        // Delete the physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        // Delete the database record
        $attachment->delete();
        $this->dispatch('notify-success', 'Attachment deleted successfully!');
        return ['success' => true];
    }
    public function openSendAttachmentsModal($documentUuid)
    {

        $this->emailAddress = '';
        $this->emailSubject = '';
        $this->emailMessage = '';

        $this->selectedDocumentUuid = $documentUuid;
        // Get document for title
        $document = Documents::where('uuid', $documentUuid)->first();
        if ($document) {
            $this->selectedDocumentTitle = $document->doc_title;
            // Pre-populate subject with document title
            $this->emailSubject = "Document of {$document->doc_title}";
        }

        $this->dispatch('open-email-modal');
        
    }
    public function sendAttachments()
    {
        $this->resetMessage();
        $this->validate([
            'emailAddress' => 'required|email',
            'emailSubject' => 'required|string|max:100',
            'emailMessage' => 'nullable|string|max:500',
        ]);

        $documents = Documents::where('uuid', $this->selectedDocumentUuid)->first();
        
        if (!$documents || $documents->attachments->isEmpty()) {
            session()->flash('error', 'No attachments found for this document.');
            return;
        }
        $selectedSubDocument='';
        $selectedSubDocument = Documents::with(['attachments', 'owner'])->where('parent_id', $documents->id)->first();
       
        try {
            $filePath=[];
            $document = Documents::where('uuid', $this->selectedDocumentUuid)->first();
            $attachments = $document->attachments;
            $subattachments = $selectedSubDocument->attachments;

            // Store the uploaded files
           $attachmentPaths = [];
           $originalNames = [];
            
           foreach ($attachments as $attachment) {
               $path = $attachment->file_path;
               $attachmentPaths[] = Storage::path("public/".$path);
               $originalNames[] = $attachment->original_file_name .' ['.$attachment->file_name.']';
           }

           foreach ($subattachments as $sub_attachment) {
                $path = $sub_attachment->file_path;
                $attachmentPaths[] = Storage::path("public/".$path);
                $originalNames[] = $sub_attachment->original_file_name .' ['.$sub_attachment->file_name.'] (Sub Document)';
            }
           
           Log::info(json_encode($attachmentPaths));
    
           // Send the email
           Mail::to($this->emailAddress)->send(new DocumentAttachmentsMail(
               $this->emailSubject,
               $this->emailMessage,
               $attachmentPaths,
               $originalNames
           ));

            $emailLog = new EmailLogs();
            $emailLog->documents_id = $documents->id;
            $emailLog->recipient_email = $this->emailAddress;
            $emailLog->subject = $this->emailSubject;
            $emailLog->message = $this->emailMessage;
            $emailLog->sent_at = now();
            $emailLog->sent_by = auth()->id();
            $emailLog->attachments_count = $attachments->count();
            $emailLog->save();
            
            // Log attachment details
            foreach ($attachments as $attachment) {
                $emailAttachmentsLog = new EmailAttachments();
                $emailAttachmentsLog->email_logs_id = $emailLog->id;
                $emailAttachmentsLog->file_size = $attachment->file_size;
                $emailAttachmentsLog->attachment_id = $attachment->id;
                $emailAttachmentsLog->file_name = $attachment->original_file_name;
                $emailAttachmentsLog->save();
                 
            }
            
            
            // Reset form fields
            $this->emailAddress = '';
            $this->emailSubject = '';
            $this->emailMessage = '';
            
            // Close modal
            $this->dispatch('close-email-modal');
            
            // Show success message
            session()->flash('success', 'Document attachments sent successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send attachments: ' . $e->getMessage());
        }
    }
    public function viewSentEmails($documentUuid)
    {
        $this->selectedDocumentUuid = $documentUuid;
        $document = Documents::where('uuid', $documentUuid)->first();
        
        if (!$document) {
            session()->flash('error', 'Document not found.');
            return;
        }
        
        $this->selectedDocumentTitle = $document->doc_title;
        $this->emailLogs = $document->emailLogs()
            ->with(['sentBy', 'emailAttachments'])
            ->orderBy('sent_at', 'desc')
            ->get();
        
        $this->dispatch('open-email-history-modal');
    }

    public function resetMessage(){
        session()->forget('error');
        session()->forget('success');
    }

    public function selectAllRecords()
    {
        if ($this->masterSelection) {
            // Get all document IDs
            $this->selectedItems = Documents::whereNull("parent_id")->where("created_by",$this->user_id)->pluck('id')->toArray();
        } else {
            // Clear selection
            $this->selectedItems = [];
        }
        $this->storeSelection();
    }
    public function toggleSelection($id, $isChecked)
    {
        if ($isChecked) {
            // Add to selectedItems if not already present
            if (!in_array($id, $this->selectedItems)) {
                $this->selectedItems[] = $id;
            }
            $this->storeSelection();
        } else {
            // Remove from selectedItems if present
            $key = array_search($id, $this->selectedItems);
            if ($key !== false) {
                $this->removeSelection($this->selectedItems[$key]);
                unset($this->selectedItems[$key]);
                // Reindex array to avoid gaps
                $this->selectedItems = array_values($this->selectedItems);
            }
        }
      
    }
    
    // Handle master checkbox changes
    public function updatedMasterSelection()
    {
        if ($this->masterSelection) {
            // Get all document IDs
            $this->selectedItems = Documents::pluck('id')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }
    
    // Update master checkbox when selectedItems changes
    public function updatedSelectedItems()
    {
        $totalDocuments = Documents::count();
        $this->masterSelection = count($this->selectedItems) === $totalDocuments;
    }

    // Clear selection
    public function clearSelection()
    {
        $this->alreadySelectedDocs=DocSelections::where('doc_status', 0)->where('user_id', $this->user_id)->where('team_id', $this->team_id)->delete();
    }
    
    // Process selected documents
    public function processSelectedDocuments()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'No documents selected.');
            return;
        }
        
        // Process the documents
        // Your processing logic here
        
        session()->flash('message', count($this->selectedItems) . ' documents processed successfully.');
        $this->clearSelection();
    }
    
    public function storeSelection()
    {
        foreach ($this->selectedItems as $doc) {
            $data = [
                'document_id' => $doc,
                'user_id' => $this->user_id,
                'team_id' => $this->team_id,
                'created_by' => Auth::id(),
                'doc_status' => 0,
            ];
            
            // Check if this combination already exists
            $exists = DocSelections::where('document_id', $doc)
                                ->where('user_id', $data['user_id'])
                                ->where('team_id', $data['team_id'])
                                ->exists();
            
            // Only create if it doesn't exist
            if (!$exists) {
                DocSelections::create($data);
            }
        }
    }
    public function removeSelection($docid)
    {
        DocSelections::where('document_id', $docid)->where('user_id', $this->user_id)->where('team_id', $this->team_id)->delete();
    }

    public function loadSelection()
    {
        $this->alreadySelectedDocs=DocSelections::where('doc_status', 0)->where('user_id', $this->user_id)->where('team_id', $this->team_id)->pluck('document_id')->toArray();
    }
    public function toggleCompletion($documentUuid)
    {
        if (!array_intersect(['1', '2'], $this->userRoles)) {
            $this->dispatch('notify-error', 'You are not authorized to perfom this action!');
            return; // Not authorized
        }

        $document = Documents::where('uuid', $documentUuid)->first();
        
        if ($document) {
            $document->update([
                'is_completed' => !$document->is_completed
            ]);
            
            $this->dispatch('notify-success', 'Document entry successfully recorded!');
        }
    }
    public function addNote($docUuid, $content)
    {
        $Documents = Documents::where('uuid', $docUuid)->firstOrFail();
        DocumentNotes::create([
            'documents_id' => $Documents->id,
            'user_id' => auth()->id(),
            'team_id' => $this->team_id,
            'content' => $content
        ]);

        
        $this->dispatch('notify-success', $this->commonNoteCreateSuccess);
    }

    public function deleteNote($noteId)
    {
        $note = DocumentNotes::findOrFail($noteId);
        
        if ($note->user_id === auth()->id() || auth()->user()->can('delete-any-note')) {
            $note->delete();
            $this->dispatch('notify-success',  $this->commonNoteDeleteSuccess);
        } else {
            $this->dispatch('notify-error', 'You cannot delete this note');
        }
    }
    public function render()
    {
        
        $this->loadDocuments();
        $this->loadSelection();
        return view('livewire.Document.document-collection', [
            'documents' => $this->documents,
            'ownerships' => $this->ownerships           
        ])->layout('layouts.app');
    }
}