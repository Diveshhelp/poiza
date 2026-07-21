<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TitleCollections;
use App\Models\TitleManagers;
use App\Models\ToneOfVoices;
use App\Models\UspCollections;
use App\Models\UspManagers;
use Auth;
use Illuminate\Http\Request;
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

class ProductAiContentController extends Controller
{
    public $user;
    public $team_id;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->team_id = Auth::user()->currentTeam->id??'';
    }

    public function getDatabaseRecordSet(Request $request)
    {
        $userId = $request->user()->id;
        $perPage = $request->input('per_page', 15);

        $data = ProductAiContentRequests::where('created_by', $userId)
            ->orderBy("id", "DESC")
            ->paginate($perPage);

        return response()->json($data);
    }

    public function getAllAttributeSets(Request $request)
    {
        $teamId=$this->team_id;

        $data = CollectionManagers::select("id","collection_set_name")
            ->withCount('attribute_set')
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();

        return response()->json($data);
    }

    public function getAllHtmlMarkupSets(Request $request)
    {
        $teamId=$this->team_id;

        $data = HtmlMarkups::select("id","html_markup_name")
            ->withCount('markup_set')
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();

        return response()->json($data);
    }

    public function getAllLanguageSets()
    {
        $data = Languages::select("id","language_name")
            ->orderBy("language_name", "ASC")
            ->get();

        return response()->json($data);
    }

    public function getAllProductCollections(Request $request)
    {
        $teamId=$this->team_id;

        $data = ProductCollections::select("id","product_set_name")
            ->withCount('product_set')
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();

        return response()->json($data);
    }

    public function getAllUspSets(Request $request)
    {
        $teamId=$this->team_id;

        $data = UspCollections::select("id","usp_set_name")
            ->withCount('collection_set')
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();
            return response()->json($data);
    }

    public function getAllToneOfVoice(Request $request)

    {
        $teamId=$this->team_id;

        $data = ToneOfVoices::select("id","tone_of_voice_title")
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();
            return response()->json($data);
    }
    public function getAllTitleFormatSets(Request $request)

    {
        $teamId=$this->team_id;

        $data = TitleCollections::select("id","title_set_name")
            ->where("team_id", $teamId)
            ->orderBy("id", "DESC")
            ->get();
            return response()->json($data);
    }
    
    
}