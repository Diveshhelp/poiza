<div>
    <div class="mx-auto py-5 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">Personal {{ $moduleTitle }} Dashboard</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Track and manage all your personal expenses with comprehensive monthly analysis
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('expenses.create') }}"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Expense
                </a>
                <button wire:click="exportPDF" wire:loading.attr="disabled" type="button"
                    class="ml-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export PDF</span>
                    <span wire:loading wire:target="exportPDF" class="ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
        <!-- Monthly Summary Cards -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- This Month's Total -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    This Month's Total
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        ₹{{ number_format($currentMonthTotal, 2) }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2">
                    <div class="text-sm">
                        <span class="{{ $monthlyTrend > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $monthlyTrend > 0 ? '+' : '' }}{{ number_format($monthlyTrend, 2) }}%
                        </span>
                        <span class="text-gray-500 ml-1">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Pending Amount -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Pending Amount
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        ₹{{ number_format($pendingTotal, 2) }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2">
                    <div class="text-sm">
                        <span class="text-yellow-600 font-medium">{{ $pendingCount }}</span>
                        <span class="text-gray-500 ml-1">pending expenses</span>
                    </div>
                </div>
            </div>

            <!-- Paid Amount -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Paid Amount
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        ₹{{ number_format($paidTotal, 2) }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">{{ $paidCount }}</span>
                        <span class="text-gray-500 ml-1">paid expenses</span>
                    </div>
                </div>
            </div>

            <!-- Average Daily Expense -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Daily Average
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        ₹{{ number_format($dailyAverage, 2) }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2">
                    <div class="text-sm">
                        <span class="text-gray-500">Based on this month's expenses</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Chart -->
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-medium text-gray-900">Monthly Expense Trends</h3>
                <div class="mt-4" style="height: 250px;" wire:ignore>
                    <canvas id="monthlyExpensesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Month Selector -->
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-4 sm:p-6 flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-base font-medium text-gray-900">Monthly Breakdown</h3>
                    <div>
                        <select wire:model.live="selectedMonth"
                            class="rounded-md border-gray-300 py-1 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($availableMonths as $key => $month)
                                <option value="{{ $key }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center space-x-2 text-sm font-medium">
                    <span class="text-gray-500">Total:</span>
                    <span class="text-gray-900">₹{{ number_format($selectedMonthTotal, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div x-data="{ isFilterOpen: false }" class="mt-6">
            <!-- Filter Toggle Button -->
            <button @click="isFilterOpen = !isFilterOpen" type="button"
                class="w-full bg-white shadow rounded-lg px-4 py-3 text-left text-sm font-medium text-gray-700 flex justify-between items-center">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Advanced Filters
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300"
                    :class="isFilterOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Filter Content -->
            <div x-show="isFilterOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4" class="mt-2 bg-white shadow rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" wire:model.live="filterStartDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" wire:model.live="filterEndDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Min Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" wire:model.live="filterMinAmount"
                                class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" wire:model.live="filterMaxAmount"
                                class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Keyword Search</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live="filterKeyword"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Search expense notes, user names, etc.">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Add Expense Form -->
        <div class="mt-6 bg-white shadow rounded-lg p-4">
            <form wire:submit="saveExpense" class="w-full">
                <h3 class="text-base font-medium text-gray-900 mb-3">Quick Add Expense</h3>
                <div class="flex flex-wrap items-end gap-3 bg-white">
                    <!-- Expense Date -->
                    <div class="flex-grow-0">
                        <label for="expense_date" class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" wire:model="expense_date" id="expense_date" max="{{ date('Y-m-d') }}"
                            value="{{ date('Y-m-d') }}" x-init="$el.value = '{{ date('Y-m-d') }}'" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm w-full
                        @error('expense_date') border-red-300 @enderror">
                    </div>

                    <!-- Amount -->
                    <div class="flex-grow-0 w-28">
                        <label for="amount" class="block text-xs font-medium text-gray-700 mb-1">Amount</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" wire:model.live="amount" id="amount" step="0.01" min="0.01" class="pl-6 rounded-md shadow-sm text-sm w-full
                    @error('amount') border-red-300 @enderror" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex-grow-0 w-28">
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="status" id="status" class="rounded-md shadow-sm text-sm w-full
                @error('status') border-red-300 @enderror">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Note -->
                    <div class="flex-grow">
                        <label for="note" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" wire:model.live="note" id="note" class="w-full rounded-md shadow-sm text-sm
                @error('note') border-red-300 @enderror" placeholder="Enter expense details">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex-shrink-0">
                        <button type="submit"
                            class="px-3 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            @if($isUpdate)
                                Edit Expense
                            @else
                                Add Expense
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Error Messages -->
                <div class="mt-1 text-xs text-red-600 flex space-x-3">
                    @error('expense_date') <span>{{ $message }}</span> @enderror
                    @error('amount') <span>{{ $message }}</span> @enderror
                    @error('status') <span>{{ $message }}</span> @enderror
                    @error('note') <span>{{ $message }}</span> @enderror
                </div>
            </form>
        </div>

        <!-- Expenses Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <div class="px-4 py-4 sm:px-6 flex items-center justify-between bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Expense Transactions</h3>
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $expenses->firstItem() ?? 0 }}</span> to
                    <span class="font-medium">{{ $expenses->lastItem() ?? 0 }}</span> of
                    <span class="font-medium">{{ $expenses->total() ?? 0 }}</span> results
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200 text-xs">
    <thead class="bg-gray-100">
        <tr>
            <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                <div class="flex items-center">
                    <span>Date</span>
                    <button wire:click="sortBy('expense_date')" class="ml-1 text-gray-400 hover:text-gray-600">
                        @if($sortField === 'expense_date')
                            @if($sortDirection === 'asc')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @else
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                            </svg>
                        @endif
                    </button>
                </div>
            </th>
            <th scope="col" class="px-2 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">
                <div class="flex items-center justify-end">
                    <span>Amount</span>
                    <button wire:click="sortBy('amount')" class="ml-1 text-gray-400 hover:text-gray-600">
                        @if($sortField === 'amount')
                            @if($sortDirection === 'asc')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @else
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                            </svg>
                        @endif
                    </button>
                </div>
            </th>
            <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                Description
            </th>
            <th scope="col" class="px-2 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">
                Status
            </th>
            <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                User
            </th>
            <th scope="col" class="px-2 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide w-16">
                Actions
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse ($expenses as $expense)
            <tr wire:key="{{ $expense->uuid }}" class="hover:bg-gray-25">
                <td class="px-2 py-1.5 whitespace-nowrap">
                    <div class="text-xs font-medium text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-400">{{ $expense->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-2 py-1.5 whitespace-nowrap text-right">
                    <div class="text-xs font-semibold text-gray-900">₹{{ number_format($expense->amount, 2) }}</div>
                </td>
                <td class="px-2 py-1.5">
                    <div class="text-xs text-gray-900 truncate max-w-xs" title="{{ $expense->note }}">{{ $expense->note }}</div>
                </td>
                <td class="px-2 py-1.5 whitespace-nowrap text-center" x-data="{ showDropdown: false }">
                    <div class="relative" @click.away="showDropdown = false">
                        <button @click="showDropdown = !showDropdown" 
                                class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                                {{ $expense->status === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                {{ $expense->status === 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
                                {{ $expense->status === 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1
                                {{ $expense->status === 'paid' ? 'bg-green-400' : '' }}
                                {{ $expense->status === 'pending' ? 'bg-yellow-400' : '' }}
                                {{ $expense->status === 'cancelled' ? 'bg-red-400' : '' }}"></span>
                            {{ ucfirst($expense->status) }}
                            <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="showDropdown"
                             class="origin-top-center absolute left-1/2 transform -translate-x-1/2 mt-1 w-24 rounded shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                             style="display: none;">
                            <div class="py-1">
                                <button wire:click="updateStatus('{{ $expense->uuid }}', 'paid')"
                                        @click="showDropdown = false"
                                        class="block w-full text-center px-2 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                    <span class="flex items-center justify-center">
                                        <span class="h-1.5 w-1.5 bg-green-400 rounded-full mr-1"></span>
                                        Paid
                                    </span>
                                </button>
                                <button wire:click="updateStatus('{{ $expense->uuid }}', 'pending')"
                                        @click="showDropdown = false"
                                        class="block w-full text-center px-2 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                    <span class="flex items-center justify-center">
                                        <span class="h-1.5 w-1.5 bg-yellow-400 rounded-full mr-1"></span>
                                        Pending
                                    </span>
                                </button>
                                <button wire:click="updateStatus('{{ $expense->uuid }}', 'cancelled')"
                                        @click="showDropdown = false"
                                        class="block w-full text-center px-2 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                    <span class="flex items-center justify-center">
                                        <span class="h-1.5 w-1.5 bg-red-400 rounded-full mr-1"></span>
                                        Cancelled
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-2 py-1.5 whitespace-nowrap">
                    <div class="text-xs text-gray-900">{{ $expense->user->name }}</div>
                </td>
                <td class="px-2 py-1.5 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-1">
                        <button wire:click="editExpense('{{ $expense->uuid }}')"
                                class="text-blue-500 hover:text-blue-700 p-0.5 rounded"
                                title="Edit">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button wire:click="deleteExpense('{{ $expense->uuid }}')"
                                wire:confirm="Delete this expense?"
                                class="text-red-500 hover:text-red-700 p-0.5 rounded"
                                title="Delete">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-2 py-6 text-center text-xs text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg class="h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="font-medium text-gray-900">No expenses found</span>
                        @if($activeFiltersCount > 0)
                            <button wire:click="resetFilters" class="text-blue-600 hover:text-blue-800 text-xs mt-1">
                                Reset filters
                            </button>
                        @else
                            <span class="text-xs mt-1">Create a new expense to get started</span>
                        @endif
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="bg-gray-50 border-t border-gray-200">
        <tr>
            <td class="px-2 py-1.5 text-xs font-semibold text-gray-900 text-right">
                Total:
            </td>
            <td class="px-2 py-1.5 text-xs font-bold text-gray-900">
                ₹{{ number_format($expenses->sum('amount'), 2) }}
            </td>
            <td colspan="4" class="px-2 py-1.5 text-xs text-gray-500">
                {{ $expenses->count() }} items
            </td>
        </tr>
    </tfoot>
</table>
            <div class="px-3 py-3 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
<!-- Chart.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // Global variable for chart instance
    let monthlyChart = null;
    const sampleData = {
        labels: ['Dec 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'May 2025'],
        values: [48500, 32750, 52300, 39800, 61200, 45600]
    };
    // Initialize chart after DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
    });

    // For pages that might already be loaded
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(function() {
            initChart();
        }, 100);
    }

    function initChart() {
        console.log('Initializing expense chart...');
        const chartCanvas = document.getElementById('monthlyExpensesChart');
        if (!chartCanvas) {
            console.warn('Chart canvas element not found');
            return;
        }
        
        
        const ctx = chartCanvas.getContext('2d');
        if (!ctx) {
            console.warn('Could not get canvas context');
            return;
        }
        
        // Destroy existing chart if it exists
        if (monthlyChart) {
            monthlyChart.destroy();
        }
        
        // Create new chart with empty data (will be populated later)
        monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Monthly Expenses',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Total: ₹' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Request chart data
        if (window.Livewire) {
            console.log('Requesting chart data from Livewire...');
            Livewire.dispatch('getExpenseChartData');
        }
    }

    // Set up Livewire event listeners (Livewire 3 syntax)
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('expenseChartDataUpdated', (data) => {
            console.log('Hllo Received chart data:', data);
            console.log(data[0].labels)
            updateChart(data);
        });
    });
    
    // Function to update chart with new data
    function updateChart(data) {
        if (!monthlyChart) {
            console.warn('Chart not initialized when data received');
            initChart();
        }
        
        if (monthlyChart) {
            monthlyChart.data.labels = data[0].labels;
            monthlyChart.data.datasets[0].data = data[0].values;
            monthlyChart.update();
            console.log('Chart updated with database data');
        }
    }
    
</script>

@script
<script>
    $wire.dispatch('post-created');
</script>
@endscript
</div>