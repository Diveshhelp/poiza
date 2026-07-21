<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocAttachment;
use App\Models\Documents;
use App\Models\SubDepartments;
use App\Models\Task;
use App\Models\Todo;
use App\Models\TodoAttachment;
use App\Models\TodoNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;
use Str;
use URL;
use Validator;

class DocumentsController extends Controller
{
    public function __construct()
    {
    }

    public function loadDocuments(Request $request)
    {
        $userId = $request->user()->id;
        $perPage = $request->input('per_page', 50);
        $status = $request->input('status');
        $serchByTitle = $request->input('search');
        $showExpiringOnly = $request->input('showExpiringOnly');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection');
       
        $query = Documents::with(['attachments','subDocuments', 'owner', 'ownership']);
       
        if($serchByTitle!=""){
            $query->when($serchByTitle, function ($query) use ($serchByTitle) {
                $query->where(function($q) use ($serchByTitle) {
                    $q->where('doc_title', 'like', '%' . $serchByTitle . '%')
                    ->orWhere('doc_number', 'like', '%' . $serchByTitle . '%')
                    ->orWhere('doc_info', 'like', '%' . $serchByTitle . '%')
                    ->orWhere('doc_validity', 'like', '%' . $serchByTitle . '%')
                    ->orWhere('doc_year', 'like', '%' . $serchByTitle . '%');
                });
            });
        }
           
        // Apply expiring documents filter if enabled
        if ($showExpiringOnly) {
            $today = Carbon::now();
            $oneMonthLater = Carbon::now()->addMonth();
           
            $query->where(function ($q) use ($today, $oneMonthLater) {
                $q->whereBetween('doc_expire_date', [$today, $oneMonthLater])
                ->orWhereBetween('doc_renewal_dt', [$today, $oneMonthLater]);
            });
        }
       
        // Validate sort field
        $sortField = in_array($sortField, ['created_at', 'doc_title', 'doc_name', 'doc_validity', 'doc_categories_id'])
        ? $sortField
        : 'created_at';
         
        // Apply sorting      
        $query->whereNull("parent_id");
        $query->where("created_by",$userId);
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $documents = $query->paginate($perPage);
       
        // Process documents to add full URLs for attachments
        $processedDocuments = $documents->getCollection()->map(function($document) {
            // Convert document to array
            $documentArray = $document->toArray();
            
            // Format attachments with full URLs
            if (isset($documentArray['attachments']) && is_array($documentArray['attachments'])) {
                $documentArray['attachments'] = collect($document->attachments)->map(function($attachment) {
                    return [
                        'id' => $attachment->id,
                        'name' => $attachment->original_file_name,
                        'type' => $attachment->file_type,
                        'size' => $attachment->file_size,
                        'url' => $this->getFullImageUrl($attachment->file_path)
                    ];
                })->toArray();
            }
            
            // Process subDocuments if they also have attachments
            if (isset($documentArray['sub_documents']) && is_array($documentArray['sub_documents'])) {
                $documentArray['sub_documents'] = collect($document->subDocuments)->map(function($subDocument) {
                    $subDocArray = $subDocument->toArray();
                    
                    // If subDocuments also have attachments, process them too
                    if (isset($subDocArray['attachments']) && is_array($subDocArray['attachments'])) {
                        $subDocArray['attachments'] = collect($subDocument->attachments)->map(function($attachment) {
                            return [
                                'id' => $attachment->id,
                                'name' => $attachment->original_file_name,
                                'type' => $attachment->file_type,
                                'size' => $attachment->file_size,
                                'url' => $this->getFullImageUrl($attachment->file_path)
                            ];
                        })->toArray();
                    }
                    
                    return $subDocArray;
                })->toArray();
            }
            
            return $documentArray;
        });
        
        // Replace the collection in the paginator
        $documents->setCollection($processedDocuments);
        
        // Convert to array for response
        $documentsArray = $documents->toArray();
       
        return response()->json([
            'status' => 'success',
            'total_records' => $documents->total(), // Use total() instead of count() for paginated results
            'data' => $documentsArray
        ]);
    }
    private function getFullImageUrl($imagePath)
    {
        // Check if the image path already has a full URL
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // If using Laravel's public disk
        if(env('APP_ENV')=="production"){
            return url("public".Storage::url($imagePath));
        }else{
            return url(Storage::url($imagePath));
        }
        
        // If using S3 or other cloud storage, you might use:
        // return Storage::disk('s3')->url($imagePath);
        
        // Or if images are in public directory:
        // return asset($imagePath);
    }

    public function getParentDocument($uuid)
    {
        $this->parentUUID = $uuid;
        
        // Load parent document with subdocuments and their attachments using nested eager loading
        $document = Documents::with(['attachments'])
            ->where('uuid', $uuid)
            ->first();
        
            
        if (!$document) {
            return [
                'doc_title' => 'Document not found',
                'sub_documents' => []
            ];
        }
        
        // Format each attachment to include URL and other necessary details
        $formattedAttachments = $document->attachments->map(function($attachment) {
            return [
                'id' => $attachment->id,
                'name' => $attachment->original_file_name,
                'type' => $attachment->file_type,
                'size' => $attachment->file_size,
                'url' => $this->getFullImageUrl($attachment->file_path)
            ];
        });
        
        return [
            'doc_title' => $document->doc_title,
            'parent_documents' => $formattedAttachments
        ];
    }
    public function subDocumentList($parentId)
    {
        $this->parentUUID = $parentId;
        
        // Load parent document with subdocuments and their attachments using nested eager loading
        $document = Documents::with(['attachments'])
            ->where('uuid', $parentId)
            ->first();
        
            
        if (!$document) {
            return [
                'doc_title' => 'Document not found',
                'sub_documents' => []
            ];
        }
        
        // Format each attachment to include URL and other necessary details
        $formattedAttachments = $document->attachments->map(function($attachment) {
            return [
                'id' => $attachment->id,
                'name' => $attachment->original_file_name,
                'type' => $attachment->file_type,
                'size' => $attachment->file_size,
                'url' => $this->getFullImageUrl($attachment->file_path)
            ];
        });
        
        return response()->json([
            'status' => 'success',
            'sub_doc_details'=> $document,
            'documents_attachment_list' => $formattedAttachments
        ]);
    }

    public function downloadAttachment($uuid)
    {
        $attachment = DocAttachment::where('uuid', $uuid)->first();
        if (!$attachment) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found'
            ], 404);
        }
        // Check if the file exists in storage
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        }
        // Generate a temporary URL that expires in 1 hour
        $temporaryUrl = $this->getFullImageUrl($attachment->file_path);
        
        return response()->json([
            'success' => true,
            'file_path'=>$temporaryUrl,
            'file_object' => $attachment,
            'file_name' => $attachment->original_file_name
        ]);
    }
}