<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\ExpenseLog;
use Carbon\Carbon;
use Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Log;
use Str;

class ExpenseCollection extends Component
{
    use WithPagination;

    public $moduleTitle = EXPENSES_TITLE;
    public $perPage = PER_PAGE;
    
    public $filterStartDate;
    public $filterEndDate;
    public $filterStatus;
    public $filterAmount;
    public $filterNote;
    public $expense_date;
    public $amount;
    public $note;
    public $status = 'pending';
    public $uuid;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;
    public $commonStatusUpdateSuccess;

    public $currentMonthTotal = 0;
    public $monthlyTrend = 0;
    public $pendingTotal = 0;
    public $pendingCount = 0;
    public $paidTotal = 0;
    public $paidCount = 0;
    public $dailyAverage = 0;
    public $selectedMonth = '';
    public $selectedMonthTotal = 0;
    public $availableMonths = [];
    public $activeFiltersCount = 0;
    public $filterMinAmount = null;
    public $filterMaxAmount = null;
    public $filterKeyword = '';
    public $sortField = 'expense_date';
    public $sortDirection = 'desc';
    public $isUpdate=false;
    public $editExpenseId;
    protected $rules = [
        'expense_date' => 'required|date|before_or_equal:today',
        'amount' => 'required|numeric|min:0.01|max:9999999.99',
        'note' => 'required|string|min:3|max:1000',
        'status' => 'required|in:paid,pending,cancelled'
        
    ];

    protected $messages = [
        'expense_date.required' => 'The expense date is required.',
        'expense_date.date' => 'Please enter a valid date.',
        'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
        'amount.required' => 'The amount is required.',
        'amount.numeric' => 'The amount must be a number.',
        'amount.min' => 'The amount must be at least ₹0.01.',
        'amount.max' => 'The amount cannot exceed ₹9,999,999.99.',
        'note.required' => 'Please provide a note for this expense.',
        'note.min' => 'The note must be at least 3 characters.',
        'note.max' => 'The note cannot exceed 1000 characters.',
        'status.required' => 'Please select a status.',
        'status.in' => 'Please select a valid status.'
    ];
    protected $queryString = [
        'filterStartDate',
        'filterEndDate',
        'filterStatus',
        'filterAmount',
        'filterNote'
    ];

    public function mount()
    {
        $this->expense_date=date('Y-m-d');
        $this->resetFilters();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
        $this->calculateStats();
    }

    public function resetFilters()
    {
        $this->filterStartDate = '';
        $this->filterEndDate = '';
        $this->filterStatus = '';
        $this->filterAmount = '';
        $this->filterNote = '';
        $this->filterStartDate = null;
        $this->filterEndDate = null;
        $this->filterStatus = '';
        $this->filterMinAmount = null;
        $this->filterMaxAmount = null;
        $this->filterKeyword = '';
        $this->activeFiltersCount = 0;
    }

    // Updated method for month selection
    public function updatedSelectedMonth()
    {
        $this->calculateSelectedMonthTotal();
    }


    public function deleteExpense($uuid)
    {
        $expense = Expense::where('uuid', $uuid)->first();
        
        if ($expense) {
            // Create log before deletion with old_content
            ExpenseLog::create([
                'old_content' => $expense->toArray(),
                'new_content' => null,
                'team_id' => Auth::user()->currentTeam->id,
                'user_id' => Auth::id(),
                'created_by' => Auth::id()
            ]);

            $expense->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } else {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
        }
    }
    
    public function saveExpense()
    {
        $this->validate();

        $data = [
            'expense_date' => $this->expense_date,
            'amount'       => $this->amount,
            'note'         => $this->note,
            'status'       => $this->status,
            'team_id'      => Auth::user()->currentTeam->id,
            'user_id'      => Auth::id(),
        ];

        $expense = $this->isUpdate
            ? tap(Expense::findOrFail($this->editExpenseId))
            : tap(new Expense(['uuid' => Str::uuid()]), fn($e) => $e->created_by = Auth::id());

        $oldContent = $this->isUpdate ? $expense->toArray() : null;

        $expense->fill($data)->save();

        ExpenseLog::create([
            'old_content' => $oldContent,
            'new_content' => $expense->toArray(),
            'team_id'     => $data['team_id'],
            'user_id'     => $data['user_id'],
            'created_by'  => $data['user_id'],
        ]);

        $this->dispatch('notify-success', $this->isUpdate ? $this->commonUpdateSuccess : $this->commonCreateSuccess);

        return redirect()->route('expenses.index');
    }
    public function editExpense($uuid){
        $queryData = Expense::where("uuid",$uuid)->first();
       
        $this->expense_date = $queryData->expense_date;
        $this->amount = $queryData->amount;
        $this->note = $queryData->note;
        $this->status = $queryData->status;
        $this->isUpdate=true;
        $this->editExpenseId=$queryData->id;
    }

    public function exportPDF()
    {
        // Apply any active filters to the query
        $expenses = $this->getFilteredExpenses()->get();
        // Check if PDF package is installed and create the PDF
        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            // Using barryvdh/laravel-dompdf package
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.expenses-report', [
                'expenses' => $expenses,
                'fromDate' => $this->filterStartDate,
                'toDate' => $this->filterEndDate,
                'generated_at' => now()->format('M d, Y H:i'),
                'company_name' => config('app.name')
            ]);
           
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'expenses-report-' . now()->format('Y-m-d') . '.pdf');
        } else {
            
            $this->dispatch('notify-error', 'PDF generation package not available. Please contact the administrator.');
        }
    }
        protected function getFilteredExpenses()
    {
        $query = Expense::query()->with('user');
        
        if ($this->filterStartDate) {
            $query->whereDate('expense_date', '>=', $this->filterStartDate);
        }
        
        if ($this->filterEndDate) {
            $query->whereDate('expense_date', '<=', $this->filterEndDate);
        }
        
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }
        
        if ($this->filterAmount) {
            $query->where('amount', $this->filterAmount);
        }
        
        if ($this->filterNote) {
            $query->where('note', 'like', '%' . $this->filterNote . '%');
        }
        $query->where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id);
        return $query->latest('expense_date');
    }

    public function updateStatus($uuid, $status)
    {
        $expense = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->where('uuid', $uuid)->first();
        
        if ($expense) {
            $expense->status = $status;
            $expense->save();
            
            $this->dispatch('notify-success', 'Expense status updated successfully');

            
        }
    }

    // Method to calculate statistics
    public function calculateStats()
    {
        // Calculate current month stats
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        $this->currentMonthTotal = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
        
        // Calculate previous month for trend
        $startOfPrevMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfPrevMonth = $now->copy()->subMonth()->endOfMonth();
        $prevMonthTotal = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->whereBetween('expense_date', [$startOfPrevMonth, $endOfPrevMonth])->sum('amount');
        
        // Calculate trend percentage
        if ($prevMonthTotal > 0) {
            $this->monthlyTrend = (($this->currentMonthTotal - $prevMonthTotal) / $prevMonthTotal) * 100;
        }
        
        // Status totals
        $this->pendingTotal = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->where('status', 'pending')->sum('amount');
        $this->pendingCount = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->where('status', 'pending')->count();
        $this->paidTotal = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->where('status', 'paid')->sum('amount');
        $this->paidCount = Expense::where('team_id', Auth::user()->currentTeam->id)
        ->where('user_id', Auth::user()->id)->where('status', 'paid')->count();
        
        // Calculate daily average
        $daysInMonth = $endOfMonth->day;
        $this->dailyAverage = $daysInMonth > 0 ? $this->currentMonthTotal / $daysInMonth : 0;
        
        // Calculate available months for dropdown
        $this->availableMonths = Expense::selectRaw('DISTINCT YEAR(expense_date) as year, MONTH(expense_date) as month')
            ->orderByRaw('YEAR(expense_date) DESC, MONTH(expense_date) DESC')
            ->get()
            ->mapWithKeys(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [$date->format('Y-m') => $date->format('F Y')];
            })
            ->toArray();
            
        // If no month is selected, use current month
        if (empty($this->selectedMonth)) {
            $this->selectedMonth = $now->format('Y-m');
        }
        
        // Calculate selected month total
        $this->calculateSelectedMonthTotal();
        
        // Count active filters
        $this->countActiveFilters();
    }

    // Calculate selected month total
    public function calculateSelectedMonthTotal()
    {
        if ($this->selectedMonth) {
            $date = Carbon::createFromFormat('Y-m', $this->selectedMonth);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $this->selectedMonthTotal = Expense::where('team_id', Auth::user()->currentTeam->id)
            ->where('user_id', Auth::user()->id)->whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
        }
    }

    // Sort functionality
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Method to load chart data
    #[On('post-created')]
    public function getExpenseChartData()
    {
        $months = collect();
        $values = collect();
        
        try {
            // Get data for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                
                $total = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
                
                $months->push($date->format('M Y'));
                $values->push(floatval($total));
            }
            
            Log::info('Chart data prepared', [
                'labels' => $months->toArray(),
                'values' => $values->toArray()
            ]);
            
            // Send data back to the JavaScript
            $this->dispatch('expenseChartDataUpdated', [
                'labels' => $months->toArray(),
                'values' => $values->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating chart data: ' . $e->getMessage());
        }
    }
    

    // Count active filters
    public function countActiveFilters()
    {
        $count = 0;
        
        if ($this->filterStartDate) $count++;
        if ($this->filterEndDate) $count++;
        if ($this->filterStatus) $count++;
        if ($this->filterMinAmount) $count++;
        if ($this->filterMaxAmount) $count++;
        if ($this->filterKeyword) $count++;
        
        $this->activeFiltersCount = $count;
    }
    public function sendWhatsAppTemplate($phoneNumber)
    {
        $accessToken = 'EAAROCEZBuupwBO0rycMzm8EHvgfS0Yxj8DnGJ6JeKGf5NBXZBxPJPA6VDboa4teL6GC4ZAErvKo7d0XA3jpQgReOJKZACCOTOwxNIp2RVfFgV37yIC9E8ZC3kMYJ1CqxLCn0xNxEpjQBcvx0GBUY8AjX7h65zI9Dmzv9856ZC4OOjeAkiQdDujlO2a6VGhDuGraY7UUs4twh9cNshRSHuZAlPxqrZCLeommlkVAZD';
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://graph.facebook.com/v22.0/691971210660862/messages', [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => 'template',
            'template' => [
                'name' => 'hello_world',
                'language' => [
                    'code' => 'en_US'
                ]
            ]
        ]);
    
        Log::info($response->json());
    }
    

    public function render()
    {
        // $this->sendWhatsAppTemplate("+918866223880");
        $query = Expense::with('user')
            ->where('team_id', Auth::user()->currentTeam->id);
            // ->where('user_id', Auth::user()->id);

        if ($this->filterStartDate) {
            $query->whereDate('expense_date', '>=', $this->filterStartDate);
        }

        if ($this->filterEndDate) {
            $query->whereDate('expense_date', '<=', $this->filterEndDate);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterAmount) {
            $query->where('amount', $this->filterAmount);
        }

        if ($this->filterMinAmount) {
            $query->where('amount', '>=', $this->filterMinAmount);
        }
        
        if ($this->filterMaxAmount) {
            $query->where('amount', '<=', $this->filterMaxAmount);
        }
        
        if ($this->filterKeyword) {
            $query->where(function($q) {
                $q->where('note', 'like', '%' . $this->filterKeyword . '%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%' . $this->filterKeyword . '%');
                  });
            });
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate($this->perPage);

        return view('livewire.expense.expense-collection', [
            'expenses' => $expenses
        ])->layout('layouts.app');
    }
}