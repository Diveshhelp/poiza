<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;

class TestJobs extends Command
{
    /* Improved Version*/
    protected $signature = 'test:job';
    protected $description = 'Check the jobs is running or not running';

    public function handle()
    {
        Log::info("Run at : " . Carbon::now());

        // Perform the job check here and return a meaningful value or message
        // For example, you might check the status of a job and return 'Job is running' or 'Job is not running'
        // For now, let's just return a simple message
        return 'Job check completed';
    }
}