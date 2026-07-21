<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\ExpenseLog;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ExpenseManager extends Component
{
    public $moduleTitle = EXPENSES_TITLE;
    public $expense_date;
    public $amount;
    public $note;
    public $status = 'pending';
    public $uuid;
    // public $isEditing = false;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;
    public $commonStatusUpdateSuccess;

    protected $rules = [
        // 'expense_date' => 'required|date',
        // 'amount' => 'required|numeric|min:0',
        // 'note' => 'required|string',
        // 'status' => 'required|in:paid,pending,cancelled'
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

    public function mount($uuid = null)
    {
        // if ($uuid) {
        //     $this->isEditing = true;
        //     $expense = Expense::where('uuid', $uuid)->firstOrFail();
        //     $this->uuid = $expense->uuid;
        //     $this->expense_date = $expense->expense_date->format('Y-m-d');
        //     $this->amount = $expense->amount;
        //     $this->note = $expense->note;
        //     $this->status = $expense->status;
        // } else {
        //     $this->expense_date = date('Y-m-d');
        // }
        $this->expense_date = date('Y-m-d');
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    // Real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function saveExpense()
    {
        $this->validate();

        $expense = new Expense();
        $expense->uuid = Str::uuid();
        $expense->expense_date = $this->expense_date;
        $expense->amount = $this->amount;
        $expense->note = $this->note;
        $expense->status = $this->status;
        $expense->team_id = Auth::user()->currentTeam->id;
        $expense->user_id = Auth::id();
        $expense->created_by = Auth::id();
        
        $expense->save();

        // Create log entry with new_content for new expense
        ExpenseLog::create([
            'old_content' => null,
            'new_content' => $expense->toArray(),
            'team_id' => Auth::user()->currentTeam->id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id()
        ]);

        $this->dispatch('notify-success', $this->commonCreateSuccess);
        return redirect()->route('expenses.index');
    }

    public function render()
    {
        return view('livewire.expense.expense-manager')->layout('layouts.app');
    }
}