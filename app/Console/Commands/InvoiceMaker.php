<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BillingMaster;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceMaker extends Command
{
    protected $signature = 'invoice-generate:job';
    protected $description = 'Generate monthly invoices automatically';

    public function handle()
    {
        try {
            // Get current month start and end dates
            $currentMonth = Carbon::now();
            $monthStart = $currentMonth->copy()->startOfMonth()->format('Y-m-d');
            $monthEnd = $currentMonth->copy()->endOfMonth()->format('Y-m-d');
            
            // Static data for multiple clients/teams
            $staticInvoiceData = [
                [
                    'selected_team_id' => 1,
                    'billing_details_id' => 1,
                    'invoice_matter' => env('BILLING_MATTER'). '-' . $currentMonth->format('F Y'),
                    'amount' => env('BILLING_AMOUNT'),
                    'status' => '1'
                ],
                
            ];

            $generatedCount = 0;

            foreach ($staticInvoiceData as $invoiceData) {
                // Check if invoice already exists for this team and month
                $existingInvoice = BillingMaster::where('selected_team_id', $invoiceData['selected_team_id'])
                    ->where('billing_start_date', $monthStart)
                    ->first();

                if (!$existingInvoice) {
                    // Create new invoice
                    $billing = BillingMaster::create([
                        'uuid' => Str::uuid(),
                        'selected_team_id' => $invoiceData['selected_team_id'],
                        'team_id' => $invoiceData['selected_team_id'],
                        'billing_details_id' => $invoiceData['billing_details_id'],
                        'invoice_matter' => $invoiceData['invoice_matter'],
                        'amount' => $invoiceData['amount'],
                        'billing_start_date' => $monthStart,
                        'billing_end_date' => $monthEnd,
                        'status' => $invoiceData['status'],
                        'cancelled_reason' => null,
                        'created_by'=>0
                    ]);

                    $generatedCount++;
                    Log::info("Invoice generated for team ID: {$invoiceData['selected_team_id']}, UUID: {$billing->uuid}");
                } else {
                    Log::info("Invoice already exists for team ID: {$invoiceData['selected_team_id']} for month: {$monthStart}");
                }
            }

            $message = "Invoice generation completed. Generated {$generatedCount} new invoices for " . $currentMonth->format('F Y');
            
            Log::info($message);
            $this->info($message);
            
            return $message;

        } catch (\Exception $e) {
            $errorMessage = 'Invoice generation failed: ' . $e->getMessage();
            Log::error($errorMessage);
            $this->error($errorMessage);
            
            return $errorMessage;
        }
    }
}