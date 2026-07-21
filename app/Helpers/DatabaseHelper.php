<?php

namespace App\Helpers;

use App\Models\JobLog;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseHelper
{
    public static function insertOrUpdateJobLogs(array $data)
    {
        try {
            DB::beginTransaction();

            $result = [
                'success' => true,
                'message' => 'Job logs inserted/updated successfully',
                'affected_ids' => []
            ];

            $jobLogs = isset($data[0]) && is_array($data[0]) ? $data : [$data];

            foreach ($jobLogs as $logData) {
                $uniqueKey = [];
                if (isset($logData['product_ai_content_response_id'])) {
                    $uniqueKey['product_ai_content_response_id'] = $logData['product_ai_content_response_id'];
                } elseif (isset($logData['product_ai_content_id'])) {
                    $uniqueKey['product_ai_content_id'] = $logData['product_ai_content_id'];
                } 
                $jobLog = JobLog::updateOrCreate($uniqueKey, $logData);
                $result['affected_ids'][] = $jobLog->id;
            }

            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to insert/update job logs: ' . $e->getMessage()
            ];
        }
    }
}