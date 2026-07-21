<?php

namespace App\Helpers;

use Log;

class LogHelper
{
    public static function infoLogger($array)
    {
        Log::info($array['message']." ".json_encode($array['data']));
    }
    public static function errorLogger($array)
    {
        Log::error($array['message']." ".json_encode($array['data']));
    }
    public static function warningLogger($array)
    {
        Log::warning($array['message']." ".json_encode($array['data']));
    }
}
