<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\CustomerEmailLogs;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerBulkEmail;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customers;
    protected $subject;
    protected $content;
    protected $userId;
    
    // For larger lists, we'll process in chunks
    protected $chunkSize = 50;
    
    // Job timeout and retries
    public $timeout = 3600; // 1 hour
    public $tries = 3;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $customers, string $subject, string $content, int $userId)
    {
        $this->customers = $customers;
        $this->subject = $subject;
        $this->content = $content;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Update the email log to processing
        $log = CustomerEmailLogs::where([
            'subject' => $this->subject,
            'user_id' => $this->userId,
            'status' => 'queued'
        ])
        ->orderBy('created_at', 'desc')
        ->first();
        
        if ($log) {
            $log->update(['status' => 'processing']);
        }
        
        $successCount = 0;
        $failureCount = 0;
        
        // Process emails in chunks to avoid memory issues
        $this->customers->chunk($this->chunkSize)->each(function ($chunk) use (&$successCount, &$failureCount) {
            foreach ($chunk as $customer) {
                try {
                    // Replace placeholders with customer data
                    $personalizedContent = $this->replaceCustomerPlaceholders($customer, $this->content);
                    
                    // Send the email
                    Mail::to($customer->email)
                        ->send(new CustomerBulkEmail(
                            $customer, 
                            $this->subject, 
                            $personalizedContent
                        ));
                    
                    $successCount++;
                    
                    // Sleep for a short time to avoid overwhelming the mail server
                    usleep(250000); // 0.25 seconds
                    
                } catch (\Exception $e) {
                    // Log the error
                    \Log::error('Failed to send email to ' . $customer->email . ': ' . $e->getMessage());
                    $failureCount++;
                }
            }
        });
        
        // Update the email log with results
        if ($log) {
            $log->update([
                'status' => 'completed',
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'completed_at' => now()
            ]);
        }
    }
    
    /**
     * Replace placeholders in email content with customer data
     *
     * @param Customer $customer
     * @param string $content
     * @return string
     */
    protected function replaceCustomerPlaceholders(User $customer, string $content)
    {
        $replacements = [
            '{name}' => $customer->name,
            '{email}' => $customer->email,
            '{id}' => $customer->id,
            '{created_at}' => $customer->created_at->format('M d, Y'),
            // Add more placeholders as needed
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
