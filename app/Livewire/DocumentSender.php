<?php

namespace App\Livewire;

use App\Mail\DocumentAttachmentsMail;
use App\Models\DocAttachment;
use App\Models\Documents;
use App\Models\EmailAttachments;
use App\Models\EmailLogs;
use App\Traits\HasSubscriptionCheck;
use File;
use Livewire\Component;
use App\Models\DocSelections;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Log;
use ZipArchive;

class DocumentSender extends Component
{
    use HasSubscriptionCheck;
    public $selectedItems = [];
    public $document_id;
    public $user_id;
    public $team_id;
    
    // For email functionality
    public $emailAddress;
    public $emailSubject = 'Your Requested Documents';
    public $emailMessage = 'Please find attached the documents you requested.';
    public $showEmailForm = false;
    public $bulk_document_attachments;
    public $selectedAttachments;
    protected $rules = [
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'emailMessage' => 'required|string',
    ];

    public function mount()
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        $this->user_id = Auth::id();
        $this->team_id = Auth::user()->currentTeam->id;
    }

    public function render()
    {
        $docSelections = DocSelections::where('user_id', $this->user_id)
                                     ->where('team_id', $this->team_id)
                                     ->where('doc_status', 0)
                                     ->pluck('document_id')->toArray();
        $documents= Documents::with(['attachments','parent'])->whereIn("id",$docSelections)->get();

        return view('livewire.document-sender', [
            'docSelections' => $documents
        ])->layout('layouts.app');
    }

   
    public function downloadAllAttachments()
    {
        try {
            // Get all document selections for this user and team
            $docSelections = DocSelections::where('user_id', $this->user_id)
                                         ->where('team_id', $this->team_id)
                                         ->pluck('document_id')
                                         ->toArray();
            
            // Get all documents with their attachments
            $documents = Documents::with(['attachments'])
                                            ->whereIn('id', $docSelections)
                                            ->whereNull('parent_id')
                                            ->get();
            


                $zip = new ZipArchive;
                $fileName = time().'.zip';
                if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
                    foreach ($documents as $document) {
                        foreach ($document->attachments as $key => $value) {
                            $filePath = Storage::path('public/documents/'.$document->id.'/'.$value->file_name);
                            $relativeNameInZipFile = basename($filePath);
                            $zip->addFile($filePath, $relativeNameInZipFile);
                        }
                    }
                    
                    $zip->close(); // Don't forget to close the zip file
                    return response()->download(public_path($fileName));
                }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
            return;
        }
    }

    public function showEmailModal()
    {
        $this->showEmailForm = true;

        
        $this->emailAddress = '';
        $this->emailSubject = '';
        $this->emailMessage = '';

        $this->emailSubject = "Bulk Documents";
         // Get document with all related attachments

         $docSelections = DocSelections::where('user_id', $this->user_id)
         ->where('team_id', $this->team_id)
         ->where('doc_status', 0)
         ->pluck('document_id')->toArray();

         $document = Documents::with(['attachments', 'owner', 'subattachments', 'doc_categories', 'ownership'])
         ->whereIn('id', $docSelections)
         ->get();

        if ($document) {
            
            $this->bulk_document_attachments = collect([]);
            foreach($document as $key=>$val)
                // Add sub-document attachments if they exist
                if ($val->subattachments && $val->subattachments->count() > 0) {
                    $this->bulk_document_attachments = $this->bulk_document_attachments->concat($val->subattachments);
                }
        }
    }

    public function sendBulkAttachments()
    {

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
            foreach($this->bulk_document_attachments as $selectedKey=>$selectedData){
                $selectedSubDocument = DocAttachment::where('uuid', $selectedData['uuid'])->first(); 
              
                $subDocCount++;
                $path = $selectedSubDocument->file_path;
                $attachmentPaths[] = Storage::path("public/".$path);
                $originalNames[] = $selectedSubDocument->original_file_name .' ['.$selectedSubDocument->file_name.']';
                $documentLogData[$selectedSubDocument->documents_id][]=$selectedSubDocument;
    
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
            
            // Close modal            // Show success message
            session()->flash('success', 'Bulk Document attachments sent successfully!');
            $this->doneSelection();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send attachments: ' . $e->getMessage());
        }
    }
    public function doneSelection(){
        $docSelections = DocSelections::where('user_id', $this->user_id)
         ->where('team_id', $this->team_id)
         ->where('doc_status', 0)
         ->get();

         foreach($docSelections as $k=>$v){
            $dataUpdate=DocSelections::find($v->id);
            $dataUpdate->doc_status=1;
            $dataUpdate->save();
         }

    }
    public function closeEmailModal()
    {
        $this->showEmailForm = false;
        $this->resetEmailForm();
    }

    public function resetEmailForm()
    {
        $this->email = '';
        $this->subject = 'Your Requested Documents';
        $this->emailMessage = 'Please find attached the documents you requested.';
    }

    public function sendDocumentsViaEmail()
    {
        $this->validate();
        
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Please select documents to send.');
            return;
        }
        
        $documents = DocSelections::whereIn('id', $this->selectedItems)->get();
        
        // Create a zip file for email attachment
        $zipName = 'documents_' . time() . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($documents as $document) {
                $documentDetails = \App\Models\Documents::find($document->document_id);
                $fileName = $documentDetails->file_name ?? 'document_' . $document->document_id . '.pdf';
                $filePath = Storage::path('documents/' . $document->document_id);
                
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
            
            // Send email with attachment
            Mail::send('emails.documents', ['message' => $this->emailMessage], function ($mail) use ($zipPath, $zipName) {
                $mail->to($this->email)
                     ->subject($this->subject)
                     ->attach($zipPath, [
                         'as' => $zipName,
                         'mime' => 'application/zip',
                     ]);
            });
            
            // Delete the temporary zip file
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
            
            session()->flash('message', 'Documents have been sent successfully.');
            $this->closeEmailModal();
        } else {
            session()->flash('error', 'There was an error creating the email attachment.');
        }
    }

    public function deleteSelected($id)
    {
        DocSelections::where('id', $id)->delete();
        session()->flash('message', 'Selected documents have been removed from your selection.');
    }
}