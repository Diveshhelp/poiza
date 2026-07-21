<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB,Auth;
use File;
use Log;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Schema;


class MasterController extends Controller
{
    public function __construct()
    {   
    }
    public function index()
    {
        return view('welcome.index');
    }

}