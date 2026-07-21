<?php

namespace App\Livewire;

use App\Models\DocAttachment;
use App\Models\Documents;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubDocumentForm extends Component
{
    use WithFileUploads;

    public $parentId;

    public $documentId;

    public $parentDocument;

    public $isEditing = false;

    public $existingFiles = [];

    // Form fields
    public $formData = [
        'title' => '',
        'doc_number' => '',
        'doc_update_date' => '',
        'validity' => '1',
        'doc_info' => '',
        'doc_year' => '',
    ];

    // Success messages from parent component
    public $commonCreateSuccess;

    public $commonUpdateSuccess;

    public $commonDeleteSuccess;

    public $commonNotDeleteSuccess;
    
    
    #[Validate('required', message: 'Please provide a Document Title')]
    #[Validate('min:3', message: 'This Document Title is too short')]
    public $doc_title;

    #[Validate('nullable')] 
    public $doc_number;

    #[Validate('nullable|date', message: 'Please enter a valid date')]
    public $doc_update_date;

    #[Validate('required|in:1,2,3', message: 'Please select a valid status')]
    public $validity = '1';

    #[Validate('nullable')] 
    public $doc_info;

    // #[Validate('nullable|regex:/^\d{4}-\d{4}$/', message: 'Document year must be in YYYY-YYYY format')]
    // public $doc_year;
    #[Validate([
        'doc_year' => [
            'nullable',
            'regex:/^\d{4}-\d{4}$/',
            'max:9'
        ]
    ], message: [
        'doc_year.regex' => 'Document year must be in YYYY-YYYY format',
        'doc_year.max' => 'Document year cannot exceed 9 characters'
    ])]
    public $doc_year;

    #[Validate('array')]
    #[Validate([
        'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif',
    ])]
    public $files = [];

    


    public function mount($parentId, $documentId = null)
    {
        try {
            $this->parentId = $parentId;
            $this->documentId = $documentId;

            // Debug the IDs
            logger()->info("Loading documents with parentId: $parentId, documentId: $documentId");

            // Check if parent document exists
            $this->parentDocument = Documents::where('uuid', $parentId)->firstOrFail();
            logger()->info('Parent document found: '.$this->parentDocument->doc_title);

            if ($documentId) {
                $this->isEditing = true;

                // Check if document exists before loading
                $docExists = Documents::where('uuid', $documentId)->exists();
                logger()->info('Document exists check: '.($docExists ? 'Yes' : 'No'));

                if (! $docExists) {
                    throw new \Exception("No document found with UUID: $documentId");
                }

                $this->loadDocument();
            } else {
                // Initialize empty form for new document
                $this->formData = [
                    'title' => '',
                    'doc_number' => '',
                    'doc_update_date' => '',
                    'validity' => '1',
                    'doc_info' => '',
                    'doc_year' => '',
                ];
                $this->existingFiles = [];
            }

            // Set success messages
            $this->commonCreateSuccess = str_replace('{module-name}', 'Sub Document', COMMON_CREATE_SUCCESS);
            $this->commonUpdateSuccess = str_replace('{module-name}', 'Sub Document', COMMON_UPDATE_SUCCESS);
            $this->commonDeleteSuccess = str_replace('{module-name}', 'Sub Document', COMMON_DELETE_SUCCESS);
            $this->commonNotDeleteSuccess = str_replace('{module-name}', 'Sub Document', COMMON_DELETE_FAILURE);

        } catch (\Exception $e) {
            logger()->error('Error in mount method: '.$e->getMessage());
            $this->dispatch('notify-error', 'Error loading document: '.$e->getMessage());
        }
    }

    public function removeUploadedFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    private function safeParseDateToYmd($dateString)
    {
        if (empty($dateString)) {
            return '';
        }
        
        // Log the date string for debugging
        logger()->info('Parsing date: ' . $dateString);
        
        try {
            if ($dateString instanceof \Carbon\Carbon) {
                return $dateString->format('Y-m-d');
            }
            
            // Try d-m-Y format first
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateString)) {
                return Carbon::createFromFormat('d-m-Y', $dateString)->format('Y-m-d');
            }
            
            // Try other common formats
            foreach (['Y-m-d', 'Y/m/d', 'd/m/Y', 'm/d/Y'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $dateString)->format('Y-m-d');
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Last resort: try to parse the date without specifying a format
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            logger()->error('Date parsing error: ' . $e->getMessage());
            return '';
        }
    }

    public function loadDocument()
    {
        $document = Documents::with('attachments')->where('uuid', $this->documentId)->firstOrFail();

        $updateDate = $this->safeParseDateToYmd($document->doc_update_date);

                
        // Set individual properties
        $this->doc_title = $document->doc_title;
        $this->doc_number = $document->doc_number;
        $this->doc_update_date = $updateDate;
        $this->validity = (string) $document->doc_validity;
        $this->doc_info = $document->doc_info;
        $this->doc_year = $document->doc_year;

        $this->existingFiles = $document->attachments;        
    }

    
    public function removeExistingFile($uuid)
    {
        try {
            $attachment = DocAttachment::where('uuid', $uuid)->firstOrFail();

            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $attachment->delete();

            $this->dispatch('notify-success', 'file:'.$this->commonDeleteSuccess);
            $this->loadDocument(); // Reload to update files list
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error removing file: '.$e->getMessage());
        }
    }

    public function saveDocument()
    {
        $this->validate();

        try {
            \DB::beginTransaction();

            $formattedDate = null;
            
            if (!empty($this->doc_update_date)) {
                try {
                    $formattedDate = $this->doc_update_date;
                } catch (\Exception $e) {
                    logger()->error('Error formatting date: ' . $e->getMessage());
                    $formattedDate = null;
                }
            }


            $documentData = [
                'doc_title' => $this->doc_title,
                'doc_number' => $this->doc_number,
                'doc_update_date' => $formattedDate,
                'doc_validity' => $this->validity,
                'doc_info' => $this->doc_info,
                'doc_year' => $this->doc_year,
            ];

            if ($this->isEditing) {
                $document = Documents::where('uuid', $this->documentId)->firstOrFail();
                $document->update($documentData);
            } else {
                $documentData['uuid'] = Str::uuid();
                $documentData['parent_id'] = $this->parentDocument->id;
                $document = Documents::create($documentData);
            }

            // Handle file uploads
            if (! empty($this->files)) {
                foreach ($this->files as $file) {
                    $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();

                    $path = $file->storeAs(
                        "documents/{$this->parentDocument->id}",
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
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            \DB::commit();

            $message = $this->isEditing ? $this->commonUpdateSuccess : $this->commonCreateSuccess;
            $this->dispatch('notify-success', $message);

            return redirect()->route('sub-documents', ['parentId' => $this->parentDocument->uuid]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->dispatch('notify-error', 'Error saving document: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.Document.sub-document-form')->layout('layouts.app');
    }
}