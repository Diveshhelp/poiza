<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">
                    Add New Expense
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{-- {{ $isEditing ? 'Update expense details' : 'Create a new expense entry' }} --}}
                    Create a new expense entry
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('expenses.index') }}" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    Back to Expenses
                </a>
            </div>
        </div>

        <form wire:submit="saveExpense" class="mt-6">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Expense Date -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="expense_date" class="block text-sm font-medium text-gray-700">Expense Date</label>
                            {{-- <input type="date" wire:model="expense_date" id="expense_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('expense_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror --}}
                            <input type="date" 
                                wire:model.live="expense_date" 
                                id="expense_date" 
                                max="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                                @error('expense_date') border-red-300 text-red-900 placeholder-red-300 @enderror">
                            @error('expense_date') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                {{-- <input type="number" wire:model="amount" id="amount" class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00" step="0.01">
                            </div>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror --}}
                            <input type="number" 
                                    wire:model.live="amount" 
                                    id="amount" 
                                    step="0.01"
                                    min="0.01"
                                    max="9999999.99"
                                    class="pl-7 mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                    @error('amount') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('amount') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model.live="status" 
                                    id="status" 
                                    class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                    @error('status') border-red-300 text-red-900 @enderror">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('status') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div class="col-span-6">
                            <label for="note" class="block text-sm font-medium text-gray-700">
                                Note
                                <span class="text-xs text-gray-500">({{ strlen($note ?? '') }}/1000)</span>
                            </label>
                            <textarea wire:model.live="note" 
                                    id="note" 
                                    rows="3" 
                                    class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                    @error('note') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    placeholder="Enter expense details"></textarea>
                            @error('note') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        {{-- {{ $isEditing ? 'Update Expense' : 'Create Expense' }} --}}
                        Create Expense
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>