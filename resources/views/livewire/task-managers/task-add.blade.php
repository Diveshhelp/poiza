<div>
    <div class="max-w-7xl mx-auto py-1 sm:px-6 lg:px-8" wire:key="task-manager-module-{{time()}}">
        <div>
        <div class="flex flex-wrap items-center justify-between">
            <div class="min-w-0 flex-1 pr-2">
                <h3 class="text-lg font-medium text-gray-900">Add Task</h3>
                <p class="mt-1 md:mt-2 text-xs md:text-sm text-gray-600 leading-relaxed line-clamp-1 md:line-clamp-none">
                Add your specific details of your task
                </p>
            </div>
            <div class="flex shrink-0 md:ml-4 md:mt-0">
                <a href="{{route('task-collections')}}"
                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-1 md:mr-3 size-4 md:size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                <span class="whitespace-nowrap">Go back</span>
                </a>
            </div>
        </div>

            <div class="mt-5 md:col-span-2">
                <div>
                    <form wire:submit="saveDataObject">
                        @include("livewire.common.messages")
                        <div class="mt-3 px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-3 ">
                              <!-- Department Selection -->
                              <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="department">Department</label>
                                <div
                                    x-data="{
                                        open: false,
                                        search: '',
                                        selectedDepartment: @entangle('department'),
                                        selectedLabel: '',
                                        highlightedIndex: 0,
                                        
                                        init() {
                                            this.updateSelectedLabel();
                                            this.$watch('selectedDepartment', () => this.updateSelectedLabel());
                                            this.$watch('filteredDepartments', () => {
                                                this.highlightedIndex = 0;
                                            });
                                        },
                                        
                                        updateSelectedLabel() {
                                            if (this.selectedDepartment) {
                                                const dept = this.departments.find(d => d.id.toString() === this.selectedDepartment.toString());
                                                this.selectedLabel = dept ? dept.department_name : '';
                                            } else {
                                                this.selectedLabel = '';
                                            }
                                        },
                                        
                                        get departments() {
                                            return @js($departmentList ?? []);
                                        },
                                        
                                        get filteredDepartments() {
                                            return this.search === ''
                                                ? this.departments
                                                : this.departments.filter(dept => 
                                                    dept.department_name.toLowerCase().includes(this.search.toLowerCase())
                                                );
                                        },
                                        
                                        selectDepartment(id, name) {
                                            this.selectedDepartment = id;
                                            this.selectedLabel = name;
                                            this.open = false;
                                            this.search = '';
                                            
                                            // Trigger Livewire update to load subdepartments
                                            this.$nextTick(() => {
                                                this.$wire.set('department', id);
                                            });
                                        },
                                        
                                        handleKeyDown(event) {
                                            if (!this.open) return;
                                            
                                            // Up arrow - move highlight up
                                            if (event.key === 'ArrowUp') {
                                                event.preventDefault();
                                                this.highlightedIndex = this.highlightedIndex > 0 
                                                    ? this.highlightedIndex - 1 
                                                    : this.filteredDepartments.length - 1;
                                            }
                                            // Down arrow - move highlight down
                                            else if (event.key === 'ArrowDown') {
                                                event.preventDefault();
                                                this.highlightedIndex = this.highlightedIndex < this.filteredDepartments.length - 1 
                                                    ? this.highlightedIndex + 1 
                                                    : 0;
                                            }
                                            // Enter - select highlighted item
                                            else if (event.key === 'Enter' && this.filteredDepartments.length > 0) {
                                                event.preventDefault();
                                                const dept = this.filteredDepartments[this.highlightedIndex];
                                                this.selectDepartment(dept.id, dept.department_name);
                                            }
                                        }
                                    }"
                                    @click.away="open = false"
                                    @keydown="handleKeyDown($event)"
                                    class="relative mt-2"
                                >
                                    <!-- Selected department display -->
                                    <div 
                                        @click="open = !open; $nextTick(() => open ? $refs.searchInput.focus() : null)"
                                        class="flex items-center justify-between p-2 rounded-md border border-gray-300 bg-white cursor-pointer"
                                        :class="{ 'border-indigo-500 ring-2 ring-indigo-500': open }"
                                    >
                                        <div x-text="selectedLabel || 'Select Department'" 
                                            class="text-sm"
                                            :class="{ 'text-gray-500': !selectedLabel }">
                                        </div>
                                        <svg class="h-5 w-5 text-gray-400" :class="{ 'transform rotate-180': open }" 
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Dropdown with search and items -->
                                    <div
                                        x-show="open"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-48"
                                    >
                                        <!-- Search input -->
                                        <div class="px-3 py-2 sticky top-0 bg-white border-b">
                                            <input
                                            autofocus
                                            tabindex="1"
                                                x-ref="searchInput"
                                                x-model="search"
                                                @keydown.escape.prevent.stop="open = false"
                                                @keydown.up.prevent.stop="handleKeyDown($event)"
                                                @keydown.down.prevent.stop="handleKeyDown($event)"
                                                @keydown.enter.prevent.stop="handleKeyDown($event)"
                                                placeholder="Search departments..."
                                                class="w-full border-0 p-1 text-sm focus:ring-0 focus:outline-none border-gray-300 rounded"
                                            />
                                        </div>
                                        
                                        <!-- No results message -->
                                        <template x-if="filteredDepartments.length === 0">
                                            <div class="px-4 py-2 text-gray-500 text-sm">No departments found</div>
                                        </template>
                                        
                                        <!-- Department list -->
                                        <template x-for="(dept, index) in filteredDepartments" :key="dept.id">
                                            <div
                                                @click="selectDepartment(dept.id, dept.department_name)"
                                                @mouseover="highlightedIndex = index"
                                                class="px-4 py-2 cursor-pointer hover:bg-indigo-50 text-sm"
                                                :class="{ 
                                                    'bg-indigo-50 text-indigo-900': selectedDepartment == dept.id,
                                                    'bg-indigo-100': highlightedIndex === index && selectedDepartment != dept.id
                                                }"
                                            >
                                                <span x-text="dept.department_name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Hidden select to maintain Livewire validation -->
                                <select wire:model.live="department" class="hidden">
                                    <option value="">Select Department</option>
                                    @foreach ($departmentList as $depKey => $depVal)
                                        <option value="{{$depVal->id}}">{{$depVal->department_name}}</option>
                                    @endforeach
                                </select>
                                
                                <div class="text-red-500">@error('department') {{ $message }} @enderror</div>
                            </div>
                                <!-- Enhanced Select2-like Subdepartment Selection with Alpine.js -->
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="subDepartment">
                                    Sub Department
                                </label>
                                
                                <div
                                    x-data="{
                                        open: false,
                                        search: '',
                                        selectedSubDepartment: @entangle('subDepartment'),
                                        selectedLabel: '',
                                        highlightedIndex: 0,
                                        isLoading: false,
                                        
                                        init() {
                                            this.updateSelectedLabel();
                                            this.$watch('selectedSubDepartment', () => this.updateSelectedLabel());
                                            this.$watch('filteredItems', () => {
                                                this.highlightedIndex = 0;
                                            });
                                            
                                            this.$watch('open', (value) => {
                                                if (value) {
                                                    this.$nextTick(() => {
                                                        if (this.$refs.searchInput) {
                                                            this.$refs.searchInput.focus();
                                                        }
                                                    });
                                                }
                                            });
                                        },
                                        
                                        updateSelectedLabel() {
                                            if (this.selectedSubDepartment) {
                                                const subDept = this.availableItems.find(d => d.id.toString() === this.selectedSubDepartment.toString());
                                                this.selectedLabel = subDept ? subDept.name : '';
                                            } else {
                                                this.selectedLabel = '';
                                            }
                                        },
                                        
                                        get availableItems() {
                                            return @js($subDepartmentList ?? []);
                                        },
                                        
                                        get filteredItems() {
                                            return this.search === ''
                                                ? this.availableItems
                                                : this.availableItems.filter(item => 
                                                    item.name.toLowerCase().includes(this.search.toLowerCase())
                                                );
                                        },
                                        
                                        selectSubDepartment(id) {
                                            // Show loading indicator
                                            this.isLoading = true;
                                            
                                            id = id.toString();
                                            this.selectedSubDepartment = id;
                                            
                                            // Close dropdown and reset search
                                            this.open = false;
                                            this.search = '';
                                            
                                            // Trigger Livewire update
                                            this.$nextTick(() => {
                                                this.$wire.set('subDepartment', id);
                                                this.isLoading = false;
                                            });
                                        },
                                        
                                        // Keyboard navigation
                                        handleKeyDown(event) {
                                            if (!this.open) return;
                                            
                                            if (event.key === 'ArrowDown') {
                                                event.preventDefault();
                                                this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filteredItems.length - 1);
                                            } else if (event.key === 'ArrowUp') {
                                                event.preventDefault();
                                                this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                                            } else if (event.key === 'Enter' && this.filteredItems.length > 0) {
                                                event.preventDefault();
                                                const item = this.filteredItems[this.highlightedIndex];
                                                this.selectSubDepartment(item.id);
                                            } else if (event.key === 'Escape') {
                                                event.preventDefault();
                                                this.open = false;
                                            }
                                        }
                                    }"
                                    @click.away="open = false"
                                    @keydown="handleKeyDown($event)"
                                    class="relative mt-2"
                                >
                                    <!-- Selected subdepartment display -->
                                    <div 
                                        @click="if (!{{ empty($department) ? 'true' : 'false' }}) { open = !open; }"
                                        class="flex items-center justify-between p-2 rounded-md border border-gray-300 bg-white cursor-pointer transition duration-200 ease-in-out"
                                        :class="{ 
                                            'bg-gray-100': {{ empty($department) ? 'true' : 'false' }}, 
                                            'border-indigo-500 ring-2 ring-indigo-500': open,
                                            'hover:border-gray-400': !open && !{{ empty($department) ? 'true' : 'false' }}
                                        }"
                                    >
                                        <div 
                                            x-text="selectedLabel || '{{ empty($department) ? 'Please select a department first' : 'Select Sub Department' }}'" 
                                            class="text-sm"
                                            :class="{ 'text-gray-500': !selectedLabel }">
                                        </div>
                                        <svg class="h-5 w-5 text-gray-400" :class="{ 'transform rotate-180': open }" 
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Dropdown with search and items -->
                                    <div
                                        x-show="open"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-48"
                                    >
                                        <!-- Search input -->
                                        <div class="px-3 py-2 sticky top-0 bg-white border-b">
                                            <input
                                            tabindex="2"
                                                x-ref="searchInput"
                                                x-model="search"
                                                @keydown.escape.prevent.stop="open = false"
                                                @keydown.up.prevent.stop="handleKeyDown($event)"
                                                @keydown.down.prevent.stop="handleKeyDown($event)"
                                                @keydown.enter.prevent.stop="handleKeyDown($event)"
                                                placeholder="Search sub departments..."
                                                class="w-full border-0 p-1 text-sm focus:ring-0 focus:outline-none border-gray-300 rounded"
                                                :disabled="{{ empty($department) ? 'true' : 'false' }}"
                                            />
                                        </div>
                                        
                                        <!-- Loading indicator -->
                                        <div x-show="isLoading" class="flex justify-center py-2">
                                            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- No results message -->
                                        <template x-if="filteredItems.length === 0 && !isLoading">
                                            <div class="px-4 py-2 text-gray-500 text-sm">No sub departments found</div>
                                        </template>
                                        
                                        <!-- Item list -->
                                        <template x-for="(item, index) in filteredItems" :key="item.id">
                                            <div
                                                @click="selectSubDepartment(item.id)"
                                                @mouseover="highlightedIndex = index"
                                                class="px-4 py-2 cursor-pointer hover:bg-indigo-50 text-sm"
                                                :class="{ 
                                                    'bg-indigo-50 text-indigo-900': selectedSubDepartment == item.id,
                                                    'bg-indigo-100': highlightedIndex === index && selectedSubDepartment != item.id
                                                }"
                                            >
                                                <span x-text="item.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Hidden select to maintain Livewire validation -->
                                <select wire:model.live="subDepartment" class="hidden">
                                    <option value="">Select Sub Department</option>
                                    @foreach ($subDepartmentList ?? [] as $subDept)
                                        <option value="{{$subDept->id}}">{{$subDept->name}}</option>
                                    @endforeach
                                </select>
                                
                                <div class="text-red-500">
                                    @error('subDepartment') {{ $message }} @enderror
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="title" class="block font-medium text-sm text-gray-700">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input type="text" wire:model="title" id="title" placeholder="Enter task title"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('title') ring-red-300 @enderror">
                                </div>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                                <!-- Person Assignment -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700"
                                        for="assigned_person">Assigned
                                        Person</label>
                                    <select wire:model="assigned_person"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Select Person</option>
                                        @foreach ($usersList as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-red-500">@error('assigned_person') {{ $message }} @enderror</div>
                                </div>

                                <!-- Work Deadline -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="deadline">Work
                                        Deadline</label>
                                    <input type="date" wire:model="deadline"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <div class="text-red-500">@error('deadline') {{ $message }} @enderror</div>
                                </div>

                                <!-- Work Priority -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="priority">Work
                                        Priority</label>
                                    <select wire:model="priority"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Select Priority</option>
                                        <option selected value="highest">Highest</option>
                                        <option value="high">High</option>
                                        <option value="low">Low</option>
                                        <option value="very_low">Very Low</option>
                                    </select>
                                    <div class="text-red-500">@error('priority') {{ $message }} @enderror</div>
                                </div>

                                <!-- Work Type -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="work_type">Work
                                        Type</label>
                                    <select wire:model="work_type"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Select Work Type</option>
                                        <option value="routine">Routine</option>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                    <div class="text-red-500">@error('work_type') {{ $message }} @enderror</div>
                                </div>

                                <!-- Work Status -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="status">Work
                                        Status</label>
                                    <select wire:model="status"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option selected  value="not_started">Work Not Started</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="done">Done</option>
                                        <option value="delayed">Delayed</option>
                                    </select>
                                    <div class="text-red-500">@error('status') {{ $message }} @enderror</div>
                                </div>
                                <!-- Repetition -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="repetition">Work Repetition</label>
                                    <select wire:model.live="repetition"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option selected value="no">One Time</option>
                                        <option value="daily">Every Day</option>
                                        <option value="weekly">Every Week</option>
                                        <option value="monthly">Every Month</option>
                                        <option value="quarterly">Every Quarter</option>
                                        <option value="half_yearly">Every Half Year</option>
                                        <option value="yearly">Every Year</option>
                                    </select>
                                    <div class="text-red-500">@error('repetition') {{ $message }} @enderror</div>
                                </div>
                                <div class="sm:col-span-2">
                                    <!-- Weekly Selection -->
                                    @if($repetition === 'weekly')
                                        <label class="block font-medium text-sm text-gray-700" for="work_detail">Repeat Option</label>
                                        <div class="mt-2 space-x-2">
                                            @foreach($this->getWeekDays() as $value => $label)
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" wire:model="weekDays" value="{{ $value }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-600">
                                                    <span class="ml-1">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="text-red-500">@error('weekDays') {{ $message }} @enderror</div>
                                    @endif

                                    <!-- Monthly Selection -->
                                    @if($repetition === 'monthly')
                                        <label class="block font-medium text-sm text-gray-700" for="work_detail">Repeat Option</label>
                                        <select wire:model="selectedMonth" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @foreach($this->getMonths() as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500">@error('selectedMonth') {{ $message }} @enderror</div>
                                    @endif

                                    <!-- Quarterly Selection -->
                                    @if($repetition === 'quarterly')
                                        <label class="block font-medium text-sm text-gray-700" for="work_detail">Repeat Option</label>
                                        <select wire:model="selectedQuarter" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @foreach($this->getQuarters() as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500">@error('selectedQuarter') {{ $message }} @enderror</div>
                                    @endif

                                    <!-- Half Yearly Selection -->
                                    @if($repetition === 'half_yearly')
                                        <label class="block font-medium text-sm text-gray-700" for="work_detail">Repeat Option</label>
                                        <select wire:model="selectedHalfYear" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @foreach($this->getHalfYears() as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500">@error('selectedHalfYear') {{ $message }} @enderror</div>
                                    @endif
                                </div>
                            </div>
                            @if($repetition !== 'no')
                                <div class="sm:col-span-2 mt-4">
                                    <label class="block font-medium text-sm text-gray-700 mb-2">
                                        Repeat Until
                                    </label>
                                    
                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                        <!-- Never option -->
                                        <div class="flex items-center">
                                            <input type="radio" id="repeat-never" wire:model.live="repeatUntilType" value="never" 
                                                class="form-radio rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-600">
                                            <label for="repeat-never" class="ml-2">Never (forever)</label>
                                        </div>
                                        
                                        <!-- Until Date option with inline date picker -->
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                <input type="radio" id="repeat-date" wire:model.live="repeatUntilType" value="date" 
                                                    class="form-radio rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-600">
                                                <label for="repeat-date" class="ml-2">Until Date</label>
                                            </div>
                                            
                                            <div class="ml-2 transition-all duration-200 max-w-xs" 
                                                :class="{ 'opacity-100': '{{ $repeatUntilType }}' === 'date', 'opacity-50': '{{ $repeatUntilType }}' !== 'date' }">
                                                <input
                                                    wire:model="repeatUntilDate"
                                                    type="date"
                                                    class="block w-full rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                    :disabled="'{{ $repeatUntilType }}' !== 'date'"
                                                >
                                            </div>
                                        </div>
                                        
                                        <!-- For Occurrences option with inline number input -->
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                <input type="radio" id="repeat-occurrences" wire:model.live="repeatUntilType" value="occurrences" 
                                                    class="form-radio rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-600">
                                                <label for="repeat-occurrences" class="ml-2">For</label>
                                            </div>
                                            
                                            <div class="mx-2 transition-all duration-200 w-20" 
                                                :class="{ 'opacity-100': '{{ $repeatUntilType }}' === 'occurrences', 'opacity-50': '{{ $repeatUntilType }}' !== 'occurrences' }">
                                                <input
                                                    wire:model="maxOccurrences"
                                                    type="number"
                                                    min="1"
                                                    class="block w-full rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                    :disabled="'{{ $repeatUntilType }}' !== 'occurrences'"
                                                >
                                            </div>
                                            <span>Occurrences</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Error Messages -->
                                    <div class="mt-1 flex space-x-4">
                                        <div class="text-red-500" x-show="'{{ $repeatUntilType }}' === 'date'">
                                            @error('repeatUntilDate') {{ $message }} @enderror
                                        </div>
                                        <div class="text-red-500" x-show="'{{ $repeatUntilType }}' === 'occurrences'">
                                            @error('maxOccurrences') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                               <!-- Work Details -->
                               <div class="sm:col-span-1 mt-3 pt-4">
                                    <label class="block font-medium text-sm text-gray-700" for="work_detail">Work
                                        Detail</label>
                                    <textarea wire:model="work_detail" rows="2"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    <div class="text-red-500">@error('work_detail') {{ $message }} @enderror</div>
                                </div>

                                <div class="sm:col-span-2 mt-2">
                                    <label for="mediaFiles" class="block text-sm font-medium text-gray-700 mb-1">
                                        Upload Task Material
                                    </label>

                                    <div
                                        class="mt-1 flex justify-center px-6 pt-3 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="mediaFiles"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload files</span>
                                                    <input id="mediaFiles" type="file" wire:model="mediaFiles"
                                                        class="sr-only" multiple>
                                                </label>
                                                <p class="pl-1"></p>
                                            </div>

                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF, PDF, DOC, DOCX
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Loading State -->
                                    <div wire:loading wire:target="mediaFiles" class="mt-1">
                                        <div class="inline-flex items-center text-sm text-blue-600">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Processing files...
                                        </div>
                                    </div>

                                    <!-- Error Messages -->
                                    @error('mediaFiles.*')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Files Preview Section -->
                                    @if($mediaFiles)
                                        <div class="mt-3 space-y-2">
                                            <div class="text-sm font-medium text-gray-700">Selected files:</div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach($mediaFiles as $index => $file)
                                                    <div class="relative group">
                                                        <div
                                                            class="flex items-center p-3 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                            @if(str_contains($file->getMimeType(), 'image'))
                                                                <!-- Image Preview -->
                                                                <div class="relative h-16 w-16 mr-2 rounded-lg overflow-hidden">
                                                                    <img src="{{ $file->temporaryUrl() }}"
                                                                        class="h-full w-full object-cover" alt="Preview">
                                                                </div>
                                                            @else
                                                                <!-- Document Icon -->
                                                                <div
                                                                    class="flex items-center justify-center h-16 w-16 mr-2 bg-gray-200 rounded-lg">
                                                                    <span class="text-sm font-medium text-gray-600">
                                                                        {{ strtoupper($file->getClientOriginalExtension()) }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                                    {{ $file->getClientOriginalName() }}
                                                                </p>
                                                                <p class="text-sm text-gray-500">
                                                                    {{ number_format($file->getSize() / 1024 / 1024, 2) }} MB
                                                                </p>
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button type="button" wire:click="removeFile({{ $index }})"
                                                                class="ml-2 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors duration-150">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Upload Button -->
                                            <div class="flex justify-end mt-2">
                                                <span wire:loading wire:target="saveDataObject">Uploading...</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Notes -->
                                <div class="sm:col-span-2 hidden">
                                    <label class="block font-medium text-sm text-gray-700" for="notes">Notes</label>
                                    <textarea wire:model="notes" rows="1"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    <div class="text-red-500">@error('notes') {{ $message }} @enderror</div>
                                </div>
                            </div>

                        <!-- Form Actions -->
                        <div
                            class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <button type="submit"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                <span class="indicator-label" wire:loading.remove wire:target="saveDataObject">
                                    {{ $isUpdate ? 'Update Task' : 'Create Task' }}
                                </span>
                                <span class="indicator-progress" wire:loading wire:target="saveDataObject">
                                    <svg class="w-5 h-5 mr-3 ml-3 text-white animate-spin"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>