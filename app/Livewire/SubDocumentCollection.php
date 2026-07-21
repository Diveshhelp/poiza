<?php
namespace App\Livewire;

use App\Mail\DocumentAttachmentsMail;
use App\Models\DocSelections;
use App\Models\Documents;
use App\Models\DocAttachment;
use App\Models\DocCategory;
use App\Models\EmailAttachments;
use App\Models\EmailLogs;
use App\Models\Ownership;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Log,DB;
use Mail;


class SubDocumentCollection extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $parentId;
    public $parentDocument;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = PER_PAGE;
    public $moduleTitle = DOCUMENT_SUB_TITLE;
    public $doc_categories;
    public $ownerships;

    public $docTitle = '';
    public $filterDocTitle = '';
    public $filterDocName = '';
    public $filterOwnerName = '';
    public $filterDocValidity = '';
    public $filterRenewalDate = '';
    public $filterDocNote = '';
    public $filterDocCategory = '';
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;

    public $files = [];
    public $doc_validity_list = DOC_VALIDITY;

    public $doc_validity = 1;
    public $emailAddress = '';
    public $emailSubject = '';
    public $emailMessage = '';
    public $selectedDocumentTitle;
    public $document_attachments=[];
    public $bulk_document_attachments=[];
    public $selectedAttachments = [];
    public $emailLogs=[];
    public $document_id_for_log;
    protected $listeners = [
        'documentDeleted' => '$refresh',
        'document-updated' => '$refresh',
        'resetPage' => '$refresh'
    ];
    public $user_id;
    public $selectedDocLists;
    public $selectAll = false;
    public $selectedDocuments = [];
    public $documentsOnCurrentPage = [];
    public $doc_renewal_dt;
    public $masterSelection=[];
    public $selectallbutton;
    
    public $showExpiringOnly = false;
    public $expiringCount = 0;
    
    public $search = '';
    public $results = [];
    public $alreadySelectedDocs=[];
    public $selectedItems = [];
    
    public $team_id;
    public function mount($parentId)
    {
        $this->user_id = Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->parentId = $parentId;
        $this->parentDocument = Documents::where('uuid', $parentId)->firstOrFail();
        $this->moduleTitle =  $this->parentDocument->doc_categories->category_title ." > ". $this->parentDocument->doc_title;
        $this->doc_categories = DocCategory::where("team_id",$this->team_id)->get();
        $this->ownerships = Ownership::where("team_id",$this->team_id)->get();
        

        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    public function loadDocuments()
    {

        $this->selectedDocuments=[];
        $query = Documents::with(['attachments', 'owner', 'ownership']);

        // First apply the parent_id and created_by constraints
        $query->where("created_by", $this->user_id)
            ->where('parent_id', $this->parentDocument->id);

        // Then apply the filters within this constrained set
        $query->when($this->filterDocTitle, function ($query) {
            $query->where(function($q) {
                $q->where('doc_title', 'like', '%' . $this->filterDocTitle . '%')
                ->orWhere('doc_number', 'like', '%' . $this->filterDocTitle . '%')
                ->orWhere('doc_info', 'like', '%' . $this->filterDocTitle . '%')
                ->orWhere('doc_validity', 'like', '%' . $this->filterDocTitle . '%')
                ->orWhere('doc_year', 'like', '%' . $this->filterDocTitle . '%');
            });
        });

         // Apply expiring documents filter if enabled
         if ($this->showExpiringOnly) {
            $today = Carbon::now();
            $oneMonthLater = Carbon::now()->addMonth();
            
            $query->where(function ($q) use ($today, $oneMonthLater) {
                $q->whereBetween('doc_expire_date', [$today, $oneMonthLater])
                ->orWhereBetween('doc_renewal_dt', [$today, $oneMonthLater]);
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);
        return $query->paginate($this->perPage);

        

    }

    public function toggleExpiringFilter()
    {
        $this->countExpiringDocuments();
        $this->showExpiringOnly = !$this->showExpiringOnly;
        $this->resetPage(); // Reset pagination when filter changes
    }

    public function countExpiringDocuments()
    {
        $today = Carbon::now();
        $oneMonthLater = Carbon::now()->addMonth();
        
        $this->expiringCount = Documents::where('created_by', $this->user_id)
            ->where(function ($q) use ($today, $oneMonthLater) {
                $q->whereBetween('doc_expire_date', [$today, $oneMonthLater])
                ->orWhereBetween('doc_renewal_dt', [$today, $oneMonthLater]);
            })
            ->where('parent_id', $this->parentDocument->id)
            ->count();
    }
    
    public function updated($property)
    {
        if ($property === 'search') {
            $this->searchEmails();
        }
    }
    
    public function searchEmails()
    {
        $this->emailAddress = $this->search;

        if (strlen($this->search) < 2) {
            $this->results = [];
            return;
        }
        
        // Adjust table and column names according to your database
        $this->results = DB::table('email_logs')
            ->where('recipient_email', 'like', '%' . $this->search . '%')
            ->limit(5)
            ->pluck('recipient_email')
            ->toArray();
    }
    
    public function selectEmail($email)
    {
        $this->emailAddress = $email;
        $this->search = $email;
        $this->results = [];
    }
    
    
    public function createSubDocument($formData)
    {
        $parent = Documents::where('uuid', $this->parentId)->first();
        if($formData['title']!=""){
            $subDocument = new Documents();
            $subDocument->uuid = Str::uuid();
            $subDocument->doc_title = $formData['title'];
            
            $subDocument->doc_validity = $formData['validity'];
            $subDocument->parent_id = $parent->id;
            $subDocument->doc_number = $formData['doc_number'];
            $subDocument->doc_info = $formData['doc_info'];
            $subDocument->doc_year = $formData['doc_year'];


            if(!empty($formData['doc_update_date'])){
                $subDocument->doc_update_date = $formData['doc_update_date']??"";
            }else{
                $subDocument->doc_update_date = null;
            }

            if(!empty($formData['doc_renewal_dt'])){
                $subDocument->doc_renewal_dt = $formData['doc_renewal_dt']??"";
            }else{
                $subDocument->doc_renewal_dt = null;
            }
            $subDocument->created_by=$this->user_id;
            $subDocument->save();
            
            // Handle file uploads
            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file in a folder named by document_id
                    $path = $file->storeAs(
                        "documents/{$parent->id}", 
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
            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->resetPage();
            $this->dispatch('document-updated');
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

    public function updateSubDocument($uuid, $formData)
    {
        try {
            \DB::beginTransaction();
            
            $document = Documents::where('uuid', $uuid)->lockForUpdate()->firstOrFail();
            
            $document->update([
                'doc_title' => $formData['title'],
                'doc_number' => $formData['doc_number'],
                'doc_update_date' => !empty($formData['doc_update_date']) 
                ? $formData['doc_update_date']
                : null,
                'doc_validity' => $formData['validity'],
                'doc_info' => $formData['doc_info'],
                'doc_year' => $formData['doc_year'],
                'doc_renewal_dt' => !empty($formData['doc_renewal_dt']) 
                ? $formData['doc_renewal_dt']
                : null
            ]);

            // Handle new file uploads if any
            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    
                    $path = $file->storeAs(
                        "documents/{$document->parent_id}", 
                        $fileName, 
                        'public'
                    );

                    DocAttachment::create([
                        'uuid' => Str::uuid()->toString(),
                        'documents_id' => $document->id,
                        'file_name' => $fileName,
                        'original_file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize()
                    ]);
                }

                $this->files = [];
            }

            \DB::commit();
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
            $this->dispatch('document-updated');
            $this->resetPage();
            
            return [
                'success' => true,
                'document' => $document->fresh(['attachments'])
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error updating document: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Error updating document: ' . $e->getMessage());
            return [
                'success' => false
            ];
        }
    }

    public function removeAttachment($uuid)
    {
        try {
            $attachment = DocAttachment::where('uuid', $uuid)->firstOrFail();
            
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            // Delete record from database
            $attachment->delete();
            
            $this->dispatch('notify-success', 'File removed successfully');
            return true;
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error removing file: ' . $e->getMessage());
            return false;
        }
    }

    public function previewAttachment($attachmentId)
    {
        try {
            $attachment = DocAttachment::findOrFail($attachmentId);
            
            // Log detailed file information
            \Log::info('Attachment Preview Details', [
                'id' => $attachment->id,
                'file_path' => $attachment->file_path,
                'file_type' => $attachment->file_type,
                'original_name' => $attachment->original_file_name,
                'storage_url' => Storage::url($attachment->file_path)
            ]);
            
            // Rest of your preview logic
        } catch (\Exception $e) {
            \Log::error('Attachment Preview Error: ' . $e->getMessage());
        }
    }
    
    public function downloadAttachment($attachmentId)
    {
        try {
            $attachment = DocAttachment::findOrFail($attachmentId);
            if (!Storage::disk('public')->exists($attachment->file_path)) {
                $this->dispatch('notify-error', 'File not found');
                return;
            }

            return response()->streamDownload(function () use ($attachment) {
                echo Storage::disk('public')->get($attachment->file_path);
            }, $attachment->original_file_name);
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to download file: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteDocument($uuid)
    {
        try {
            $document = Documents::where('uuid', $uuid)->firstOrFail();
            
            // Delete associated attachments
            foreach ($document->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
                $attachment->delete();
            }
            
            $document->delete();
            
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
        }
    }

    public function resetPage()
    {
        $this->reset(['files']);
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadDocuments();
    }

    public function openSendAttachmentsModal($documentUuid)
    {
        // Reset form fields
        $this->emailAddress = '';
        $this->emailSubject = '';
        $this->emailMessage = '';
        $this->selectedDocumentUuid = $documentUuid;
        $this->selectedAttachments = []; // Array to track selected attachments
    
        // Get document with all related attachments
        $document = Documents::with(['attachments', 'owner', 'subattachments', 'doc_categories', 'ownership'])
            ->where('uuid', $documentUuid)
            ->first();
            
        if ($document) {
            $this->selectedDocumentTitle = $document->doc_title;
            // Pre-populate subject with document title
            $this->emailSubject = "Document of {$document->doc_title}";
            
            // Combine all attachments
            $this->document_attachments = collect([]);
            
            
            // Add sub-document attachments if they exist
            if ($document->subattachments && $document->subattachments->count() > 0) {
                $this->document_attachments = $this->document_attachments->concat($document->subattachments);
            }
            $this->document_id_for_log=$document->id;
        }
        
        $this->dispatch('open-email-modal');
    }

    public function openBulkSendAttachmentsModal()
    {

        $this->emailAddress = '';
        $this->emailSubject = '';
        $this->emailMessage = '';

        $this->emailSubject = "Bulk Documents";
         // Get document with all related attachments
         $document = Documents::with(['attachments', 'owner', 'subattachments', 'doc_categories', 'ownership'])
         ->whereIn('id', $this->masterSelection)
         ->get();
        if ($document) {
            
            $this->bulk_document_attachments = collect([]);
            foreach($document as $key=>$val)
                // Add sub-document attachments if they exist
                if ($val->subattachments && $val->subattachments->count() > 0) {
                    $this->bulk_document_attachments = $this->bulk_document_attachments->concat($val->subattachments);
                }
                
        }
        $this->dispatch('open-bulk-email-modal');
        
    }

    public function toggleAllAttachments($selectAll)
    {
        if ($selectAll) {
            $this->selectedAttachments = $this->bulk_document_attachments->pluck('uuid')->toArray();
        } else {
            $this->selectedAttachments = [];
        }
    }
    public function selectAllRecords(){
        $documents = $this->loadDocuments();
        
        if($this->selectallbutton){
            $this->masterSelection = $documents->pluck('id')->toArray();
        }else{
            $this->masterSelection = [];
        }
        
    }
 
    public function sendAttachments()
    {
        $this->resetMessage();
        $this->validate([
            'emailAddress' => 'required|email',
            'emailSubject' => 'required|string|max:100',
            'emailMessage' => 'nullable|string|max:500',
        ]);

       
        $documents = DocAttachment::whereIn('uuid', $this->selectedAttachments)->get();
        
        if (!$documents || $documents->isEmpty()) {
            session()->flash('error', 'No attachments found for this document.');
            return;
        }
       
        try {
            // Store the uploaded files
           $attachmentPaths = [];
           $originalNames = [];
            
           foreach ($documents as $sub_attachment) {
                $path = $sub_attachment->file_path;
                $attachmentPaths[] = Storage::path("public/".$path);
                $originalNames[] = $sub_attachment->original_file_name;
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
            $emailLog->documents_id = $documents->documents_id;
            $emailLog->recipient_email = $this->emailAddress;
            $emailLog->subject = $this->emailSubject;
            $emailLog->message = $this->emailMessage;
            $emailLog->sent_at = now();
            $emailLog->sent_by = auth()->id();
            $emailLog->attachments_count = $documents->count();
            $emailLog->save();

            // Log attachment details
            foreach ($documents as $attachment) {
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

    public function sendBulkAttachments()
    {
        $this->resetMessage();
        $this->validate([
            'emailAddress' => 'required|email',
            'emailSubject' => 'required|string|max:100',
            'emailMessage' => 'nullable|string|max:500',
        ]);

       
        try {

            $attachmentPaths = [];
            $originalNames = [];

            $documentWiseData=[];
            $documentLogData=[];
            $subDocCount=0;
            foreach($this->selectedAttachments as $selectedKey=>$selectedData){
                $selectedSubDocument = DocAttachment::where('uuid', $selectedData)->first(); 
                if(in_array($selectedSubDocument->uuid,$this->selectedAttachments)){
                    $subDocCount++;
                    $path = $selectedSubDocument->file_path;
                    $attachmentPaths[] = Storage::path("public/".$path);
                    $originalNames[] = $selectedSubDocument->original_file_name .' ['.$selectedSubDocument->file_name.'] (Sub Document)';
                    $documentLogData[$selectedSubDocument->documents_id][]=$selectedSubDocument;
                }
    
                $documentWiseData[$selectedSubDocument->documents_id]=$subDocCount;
            }

          
           Log::info(json_encode($attachmentPaths));
    
        //    // Send the email
           Mail::to($this->emailAddress)->send(new DocumentAttachmentsMail(
               $this->emailSubject,
               $this->emailMessage,
               $attachmentPaths,
               $originalNames
           ));

           
           foreach($documentLogData as $logKey=>$logVal){

                $emailLog = new EmailLogs();
                $emailLog->documents_id = $logKey;
                $emailLog->recipient_email = $this->emailAddress;
                $emailLog->subject = $this->emailSubject;
                $emailLog->message = $this->emailMessage;
                $emailLog->sent_at = now();
                $emailLog->sent_by = auth()->id();
                $emailLog->attachments_count = count($logVal);
                $emailLog->save();
                
                // Log attachment details
                foreach ($logVal as $attachment) {
                    $emailAttachmentsLog = new EmailAttachments();
                    $emailAttachmentsLog->email_logs_id = $emailLog->id;
                    $emailAttachmentsLog->file_size = $attachment->file_size;
                    $emailAttachmentsLog->attachment_id = $attachment->id;
                    $emailAttachmentsLog->file_name = $attachment->original_file_name;
                    $emailAttachmentsLog->save();
                    
                }
            }
            
            // Reset form fields
            $this->emailAddress = '';
            $this->emailSubject = '';
            $this->emailMessage = '';
            
            // Close modal
            $this->dispatch('close-bulk-email-modal');
            
            // Show success message
            session()->flash('success', 'Bulk Document attachments sent successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send attachments: ' . $e->getMessage());
        }
    }
    public function resetMessage(){
        session()->forget('error');
        session()->forget('success');
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
    public function updatedDocTitle()
    {
        $this->loadDocuments();
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
    }
    public function bulkAction($action)
    {
        if (empty($this->selectedDocuments)) {
            // Show an error or notification that no documents are selected
            return;
        }
        
        switch ($action) {
            case 'delete':
                // Delete selected documents
                break;
            case 'archive':
                // Archive selected documents
                break;
            // Add more actions as needed
        }
        
        // Reset selection after action
        $this->selectedDocuments = [];
    }
    public function updatedDocValidity()
    {
        if ($this->doc_validity === 1) {
            $this->doc_renewal_dt = 'NO';
        } 
    }
    public function setValidaity($validity){
        $this->doc_validity=$validity;
    }
    public function loadSelection()
    {
        $this->alreadySelectedDocs=DocSelections::where('doc_status', 0)->where('user_id', $this->user_id)->where('team_id', $this->team_id)->pluck('document_id')->toArray();
    }
    public function toggleSelection($id, $isChecked)
    {
        Log::info($id);
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

    public function clearSelection()
    {
        $this->alreadySelectedDocs=DocSelections::where('doc_status', 0)->where('user_id', $this->user_id)->where('team_id', $this->team_id)->delete();
    }
    public function render()
    {
        $documents = $this->loadDocuments();
        $this->loadSelection();
        $this->dispatch('documents-loaded', [
            'count' => $documents->count()
        ]);
        return view('livewire.Document.sub-document-collection', [
            'documents' => $this->loadDocuments(),
        ])->layout('layouts.app');
    }
}