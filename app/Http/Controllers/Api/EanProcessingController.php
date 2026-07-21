<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AiContentOfProductJob;
use App\Jobs\ManageCacheJob;
use App\Jobs\MergeResponseByClaudeJob;
use App\Jobs\ProductTypeJob;
use App\Jobs\ShoppingScraperJob;
use App\Jobs\SleepJob;
use App\Models\CollectionManagers;
use App\Models\HtmlMarkups;
use App\Models\Languages;
use App\Models\ProductAiContentRequests;
use App\Models\ProductAiContentResponses;
use App\Models\ProductAiContents;
use App\Models\ProductCollections;
use App\Models\ProductContents;
use App\Models\Products;
use App\Models\Sources;
use App\Models\TitleCollections;
use App\Models\ToneOfVoices;
use App\Models\UspCollections;
use Bus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class EanProcessingController extends Controller
{
    use HasApiTokens;

    protected $user;
    protected $sourceId;
    protected $apiBatchId;
    protected $team_id;

    protected $attributeId;
    protected $toneOfVoiceId;
    protected $htmlMarkupId;
    protected $languageId;
    protected $uspId;
    protected $titleFormatId;
    /**
     * Constructor to initialize user and team_id
     * Sets up the authenticated user and their current team ID
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->team_id = Auth::user()->currentTeam->id??'';
    }

    /**
     * Main method to initialize EAN content generation process
     * Validates incoming EANs, processes them, and queues jobs for further processing
     * Handles various exceptions and returns appropriate responses
     */
    public function initializeEanContentGeneration(Request $request)
    {

        try {
            $validatedData = $this->validateEans($request);
            $this->sourceId = $this->getSourceId();
            $this->apiBatchId=$this->getApiBatchId();
            $processedEans = $this->processEans($validatedData['eans']);
            $eansWithStatus=$this->displayEanWithStatus($processedEans);
            $processedQueue = $this->processedQueue($processedEans,$request->all());

            return response()->json([
                'message' => 'Task created successfully',
                'ean_count' => count($eansWithStatus),
                'id' => $this->apiBatchId,
                'status' => $eansWithStatus,

            ], 200);

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->errorResponse('A database error occurred. Please try again later.', [], 503);
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return $this->errorResponse('An unexpected error occurred. Our team has been notified.', [], 500);
        }
    }

    public function displayEanWithStatus($processedEans){
        $finalDisplay=[];
        foreach($processedEans as $key => $value){
            $finalDisplay[$key]['ean'] = $value['ean'];
            $finalDisplay[$key]['status'] = $value['status'];
        }
        return $finalDisplay;
    }
    /**
     * Validate the incoming EAN numbers
     * Ensures that the input contains an array of valid 13-digit EAN numbers
     */
    protected function validateEans(Request $request)
    {
        return $request->validate([
            'ai_request_title'=>'required|string|max:255',
            'eans' => 'required|array|min:1',
            'eans.*' => 'required|string|regex:/^[0-9]{7,13}$/',
        ]);
    }

    /**
     * Get the source ID for user entry
     * Retrieves the ID of the source labeled as user entry
     */
    protected function getSourceId()
    {
        return Sources::where('source_label', SOURCE_USER_ENTRY)->value('id');
    }

    protected function getApiBatchId(){
        $ProductContentsMaxBatchId = ProductContents::max('api_generated_batch_id') ?? 0;
        return $ProductContentsMaxBatchId + 1;
    }
    /**
     * Process single or multiple EANs
     * Iterates through the array of EANs, processing each one individually
     * Uses a database transaction to ensure data integrity
     */
    protected function processEans(array $eans)
    {
        $processedEans = [];
        DB::beginTransaction();
        try {
            foreach ($eans as $ean) {
                $processedEans[] = $this->processSingleEan($ean);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing EANs: ' . $e->getMessage());
            throw $e;
        }
        return $processedEans;
    }

    protected function CreateAndGetProductCollectionId($ai_request_title): string
    {
        $newCollection = ProductCollections::create([
            'product_set_name' => $ai_request_title,
            'team_id' => $this->team_id,
            'attribute_status' => STATUS_ACTIVE,
            'created_by' => $this->user->id,
            'uuid' => Str::uuid()->toString(),
            'is_system_generated'=>true
        ]);

        return $newCollection->id;
    }

    /**
     * Queue jobs for processed EANs
     * Creates a chain of jobs for each processed EAN and dispatches them to the queue
     */
    protected function processedQueue(array $eans,$request)
    {
        try {
            $requestData=[];
            $ids = $this->getRelatedIds($request);
            $productCollectionId=$this->getOrCreateProductCollectionId();

            $requestData['ai_request_title'] = $request['ai_request_title'];
            $requestData['product_collection_id'] = $productCollectionId;

            $aiRequestObject=$this->createProductAiContentRequest($ids,$requestData);
            $product_ai_request_id=$aiRequestObject->id;
            foreach ($eans as $eanObject) {

                $ProductAiContentResponses=new ProductAiContentResponses();
                $ProductAiContentResponses->uuid=Str::uuid()->toString();
                $ProductAiContentResponses->product_id=$eanObject['ProductId']??NULL;
                $ProductAiContentResponses->ai_full_response=NULL;
                $ProductAiContentResponses->ai_response_output=NULL;
                $ProductAiContentResponses->ai_response_status=PRODUCT_STATUS_WRITING;
                $ProductAiContentResponses->product_ai_content_requests_id=$product_ai_request_id;
                $ProductAiContentResponses->created_by=0;
                $ProductAiContentResponses->save();

                $logParameter=[];
                $logParameter['product_ai_content_response_id']=$ProductAiContentResponses->id;

                $product_ai_response_id=$ProductAiContentResponses->id;

                Bus::chain([
                    new ShoppingScraperJob($eanObject['ean'],$logParameter),
                    new ManageCacheJob($eanObject['ProductId'], $eanObject['ean'],$logParameter),
                    new MergeResponseByClaudeJob($eanObject['ProductId'], $eanObject['ean'], $eanObject['productAiContentPrimaryId'], '',$logParameter),
                    new ProductTypeJob($eanObject['productAiContentId'],$product_ai_request_id,$product_ai_response_id),
                    new AiContentOfProductJob($eanObject['productContentId'],$product_ai_request_id,$product_ai_response_id)
                ])->dispatch();
            }
        } catch (\Exception $e) {
            Log::error('Error processing jobs of EANs: ' . $e->getMessage());
        }
        return true;
    }

    private function getRelatedIds($requestData)
    {
        return [
            'collectionId' => $this->getModelId(CollectionManagers::class, $requestData['attributeId']),
            'ToneOfVoiceId' => $this->getModelId(ToneOfVoices::class, $requestData['toneOfVoiceId']),
            'HtmlMarkupId' => $this->getModelId(HtmlMarkups::class, $requestData['htmlMarkupId']),
            'LanguageId' => $this->getModelId(Languages::class, $requestData['languageId'], true),
            'usp_id' => $this->getModelId(UspCollections::class, $requestData['uspId']),
            'product_title_id' => $this->getModelId(TitleCollections::class, $requestData['titleFormatId']),
        ];
    }
    private function createProductAiContentRequest($ids, $requestData)
    {
        return ProductAiContentRequests::create([
            'uuid' => Str::uuid()->toString(),
            'ai_request_title' => $requestData['ai_request_title'] ?? '',
            'collection_id' => $ids['collectionId'],
            'tone_of_voice_id' => $ids['ToneOfVoiceId'],
            'language_id' => $ids['LanguageId'],
            'html_markup_id' => $ids['HtmlMarkupId'],
            'product_id' => $requestData['product_collection_id'],
            'team_id' => $this->team_id,
            'ai_request_status' => STATUS_PENDING,
            'created_by' => $this->user->id,
            'ai_request_batch_id' => Str::uuid()->toString(),
            'usp_id' => $ids['usp_id'],
            'product_title_id' => $ids['product_title_id'],
        ]);
    }
    private function getModelId($modelClass, $id, $required = false)
    {
        $model = $modelClass::where('id', $id)->first();
        return $required ? $model->id : ($model->id ?? 0);
    }


    /**
     * Process a single EAN
     * Creates necessary entries in ProductContent, Product, and ProductAiContent tables
     */
    protected function processSingleEan(string $ean)
    {
        $productContent = $this->createProductContent(['product_ean' => $ean]);
        $product = $this->createProductAndLinkCollection($productContent->id);
        $productAiContent = $this->createProductAiContent($productContent->id, $ean);

        return [
            'ean' => $ean,
            'ProductPrimaryId' => $product->id,
            'ProductId' => $product->product_id,
            'productAiContentPrimaryId' => $productAiContent->id,
            'productAiContentId' => $productAiContent->product_content_id,
            'productContentId' => $productContent->id,
            'status'=>API_QUEUE_STATUS[$productAiContent->ai_status]
        ];
    }

    /**
     * Create a new product content entry
     * Populates the ProductContents table with basic product information
     */
    protected function createProductContent(array $productData): ProductContents
    {
        return ProductContents::create([
            'product_ean' => $productData['product_ean'],
            'product_status' => STATUS_ACTIVE,
            'created_by' => $this->user->id,
            'uuid' => Str::uuid()->toString(),
            'source_id' => $this->sourceId,
            'api_generated_batch_id'=>$this->apiBatchId
        ]);
    }

    /**
     * Create a new product and link it to the product collection
     * Creates an entry in the Products table and associates it with a product collection
     */
    protected function createProductAndLinkCollection(int $productId): Products
    {
        $product = Products::create([
            'product_id' => $productId,
            'product_collection_id' => $this->getOrCreateProductCollectionId(),
            'product_status' => STATUS_ACTIVE,
            'created_by' => $this->user->id,
            'uuid' => Str::uuid()->toString(),
        ]);
        ProductContents::where('id', $productId)->update(['product_id' => $product->id]);
        return $product;
    }

    /**
     * Create a new product AI content entry
     * Initializes a record in the ProductAiContents table for future AI-generated content
     */
    protected function createProductAiContent(int $productContentId, int $productEan): ProductAiContents
    {
        return ProductAiContents::create([
            'uuid' => Str::uuid()->toString(),
            'product_ean' => $productEan,
            'merged_data' => null,
            'full_response' => null,
            'ai_status' => STATUS_PENDING,
            'product_content_id' => $productContentId,
            'created_by' => $this->user->id

        ]);
    }

    /**
     * Generate a standardized error response
     * Creates a consistent JSON response for error scenarios
     */
    protected function errorResponse(string $message, array $details = [], int $statusCode = 400)
    {
        return response()->json([
            'error' => $message,
            'details' => $details
        ], $statusCode);
    }

    /**
     * Retrieve processed EANs
     * Fetches and returns the AI content for a specific product
     */
    public function getProcessedEans(Request $request)
    {
        $batchId = $request->id;

        $productEanWithQueueStatus = ProductContents::select([
            'product_contents.product_ean as ean',
            'product_contents.id',
            'product_ai_contents.ai_status as status',
        ])
        ->leftJoin('product_ai_contents', 'product_contents.id', '=', 'product_ai_contents.product_content_id')
        ->where('product_contents.api_generated_batch_id', $batchId)
        ->orderByDesc('product_contents.id')
        ->get();

        $mappedContents = $productEanWithQueueStatus->map(function ($item) {
            $item->status = API_QUEUE_STATUS[$item->status] ?? 'unknown';
            return $item;
        });

        if ($mappedContents->isEmpty()) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Task status retrieved successfully.',
            'status' => $mappedContents,
        ], 200);
    }

    /**
     * Retrieve AI-generated product details for a specific product content.
     *
     * This function does the following:
     * 1. Fetches the AI-generated content for a given product_content_id.
     * 2. Checks if the content exists.
     * 3. Parses the XML data stored in the 'merged_data' field.
     * 4. Extracts the product information from the parsed data.
     * 5. Constructs a response that includes:
     *    - A success message
     *    - The product EAN
     *    - All fields from the 'product_info' section of the AI-generated content
     */
    public function getAiProductDetails(Request $request)
    {
        $productAiContent = ProductAiContents::select('product_ean', 'merged_data')
            ->where('product_content_id', $request->id)
            ->first();

        if (!$productAiContent) {
            return response()->json([
                'message' => 'Content not found'
            ], 404);
        }

        $xmlString = $productAiContent->merged_data;
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $productDetails = json_decode($json, true);

        // Extract product_info and merge it with the response
        $responseData = [
            'message' => 'Content retrieved successfully.',
            'product_ean' => $productAiContent->product_ean,
        ];

        if (isset($productDetails['product_info']) && is_array($productDetails['product_info'])) {
            $responseData = array_merge($responseData, $productDetails['product_info']);
        }

        return response()->json($responseData, 200);
    }

    /**
     * Get or create a product collection ID
     * Retrieves an existing product collection or creates a new one if it doesn't exist
     */
    protected function getOrCreateProductCollectionId(): string
    {
        $existingCollection = ProductCollections::where('team_id', $this->team_id)
            ->where('product_set_name', DEFAULT_API_BASED_COLLECTION_NAME)
            ->first();

        if ($existingCollection) {
            return $existingCollection->id;
        }

        $newCollection = ProductCollections::create([
            'product_set_name' => DEFAULT_API_BASED_COLLECTION_NAME,
            'team_id' => $this->team_id,
            'attribute_status' => STATUS_ACTIVE,
            'created_by' => $this->user->id,
            'uuid' => Str::uuid()->toString()
        ]);

        return $newCollection->id;
    }
    /**
     * Test method to check if the API is working
     * A simple endpoint to verify the API's functionality
     */
    public function testMethod(Request $request)
    {
        Log::info('Test method called');
        return response()->json([
            'message' => 'API works well!',
            'users'=>Auth::user()

        ], 200);
    }
}
