<div>
    <div x-data="{ 
        showModal: false,
        parentDepartment: null,
        form: {
            name: '',
            description: '',
            status: 1,
            uuid:''
        },
        
    }"
    @open-sub-dept-modal.window="
    parentDepartment = $event.detail;
    showModal = true;
"
>
<div  class="mx-auto py-2 sm:px-6 lg:px-8" wire:key="departments-manager-module-{{time()}}">
        <div class="md:grid">
            <div class="md:col-span-1 flex justify-between mb-4">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">{{$moduleTitle}} Collection</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Create and manage departments and sub-departments for better organization.
                    </p>
                </div>
            </div>

            <!-- Tabs Container -->
            <div x-data="{ activeTab: 'department' }" class="mt-5 md:mt-0 md:col-span-2">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'department'"
                            :class="{'border-indigo-500 text-indigo-600': activeTab === 'department',
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'department'}"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            Department
                        </button>
                        <button 
                            @click="activeTab = 'subdepartment'"
                            :class="{'border-indigo-500 text-indigo-600': activeTab === 'subdepartment',
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'subdepartment'}"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            Category
                        </button>
                    </nav>
                </div>

                <!-- Department Tab Content -->
                <div x-show="activeTab === 'department'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form wire:submit="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label class="block font-medium text-sm text-gray-700" for="department_name">
                                        Department Name
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" 
                                               wire:model="department_name" 
                                               required="required"
                                               placeholder="Enter department name" 
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="text-red-500">@error('department_name') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                    <span class="indicator-label" wire:loading.remove wire:target="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                                        {{ $isUpdate ? 'Update Department' : 'Create Department' }}
                                    </span>
                                    <span class="indicator-progress" wire:loading wire:target="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                                        <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Category Tab Content -->
                <div x-show="activeTab === 'subdepartment'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form wire:submit="saveSubDepartment">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Parent Department Selection -->
                                <div class="sm:col-span-3">
                                    <label class="block font-medium text-sm text-gray-700" for="parent_department_id">
                                        Parent Department
                                    </label>
                                    <div class="mt-2">
                                        <select wire:model="parent_department_id"
                                                required
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Select Parent Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-red-500">@error('parent_department_id') {{ $message }} @enderror</div>
                                </div>

                                <!-- Category Name -->
                                <div class="sm:col-span-3">
                                    <label class="block font-medium text-sm text-gray-700" for="sub_department_name">
                                        Category Name
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" 
                                               wire:model="sub_department_name" 
                                               required="required"
                                               placeholder="Enter sub-department name" 
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="text-red-500">@error('sub_department_name') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                    <span class="indicator-label" wire:loading.remove wire:target="saveSubDepartment">
                                        Create Category
                                    </span>
                                    <span class="indicator-progress" wire:loading wire:target="saveSubDepartment">
                                        <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- DepartmentGrid.blade.php -->
                <!-- EnhancedDepartmentGrid.blade.php -->
                    <div class="max-w-7xl pt-5">
                        @if($departments->isEmpty())
                            <div class="text-center bg-white rounded-lg shadow-sm p-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18h0v1c0 1-1 2-2 2H8c-1 0-2-1-2-2V7c0-1 1-2 2-2h8c1 0 2 1 2 2v11zm-4-9H10m4 4H10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No Departments</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new department.</p>
                            </div>
                        @else
                            <div class="sm:flex sm:items-center mb-8">
                                <div class="sm:flex-auto">
                                    <h1 class="text-2xl font-bold text-gray-900">Departments</h1>
                                    <p class="mt-2 text-sm text-gray-700">A comprehensive view of all departments and their sub-departments in your organization.</p>
                                </div>
                            </div>

                            <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($departments as $department)
                                    <li class="col-span-1 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <!-- Department Header -->
                                        <div class="w-full p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                @if($department->row_status == 1)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ring-1 ring-inset ring-green-600/20">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Inactive</span>
                                                @endif
                                            </div>

                                            <div class="mt-4">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $department->department_name }}</h3>
                                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                                    <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $department->subDepartments->count() }} sub-departments
                                                </div>
                                            </div>

                                            <!-- Department Metadata -->
                                            <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm">
                                                        <p class="font-medium text-gray-900">{{ $department->createdUser->name ?? 'Unknown' }}</p>
                                                        <p class="text-gray-500">Creator</p>
                                                    </div>
                                                </div>
                                                <div class="text-right text-sm text-gray-500">
                                                    <p class="font-medium">Created</p>
                                                    <p>{{ $department->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sub-departments Section -->
                                        @if($department->subDepartments->count() > 0)
                                            <div x-data="{ open: false }" class="border-t border-gray-100">
                                                <button @click="open = !open" class="w-full px-6 py-3 flex items-center justify-between text-sm hover:bg-gray-50 transition-colors duration-150 focus:outline-none">
                                                    <span class="font-medium text-indigo-600">View Sub-departments</span>
                                                    <svg :class="{'rotate-180': open}" class="ml-2 h-5 w-5 text-indigo-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                
                                                <div x-show="open" 
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform scale-95"
                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                    x-transition:leave-end="opacity-0 transform scale-95"
                                                    class="px-6 py-4 space-y-3 bg-gray-50">
                                                    @foreach($department->subDepartments as $subDepartment)
                                                        <div class="bg-white rounded-lg p-3 sm:p-4 shadow-sm">
                                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                                                                <div class="flex items-center space-x-2 sm:space-x-3">
                                                                    <div class="min-w-0">
                                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $subDepartment->name }}</p>
                                                                        <div class="flex items-center mt-0.5 text-xs text-gray-500">
                                                                            <span class="truncate max-w-[120px] sm:max-w-none">By : {{ $subDepartment->createdUser->name ?? 'Unknown' }}</span>
                                                                            
                                                                        </div>
                                                                        <div class="flex items-center mt-0.5 text-xs text-gray-500">
                                                                        <span class="truncate max-w-[120px] sm:max-w-none">{{ $subDepartment->created_at->diffForHumans() }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center justify-end space-x-2 sm:space-x-3 mt-1 sm:mt-0">
                                                                    @if($subDepartment->is_active == 1)
                                                                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Active</span>
                                                                    @else
                                                                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">Inactive</span>
                                                                    @endif
                                                                
                                                                    <button wire:confirm="Are you sure you want to delete sub department from this list?" wire:click="deleteSubDepartment({{ $subDepartment->id }})"
                                                                        class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                                                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-0.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        <span class="hidden sm:inline">Delete</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="border-t border-gray-100">
                                            <div class="grid grid-cols-3 divide-x divide-gray-100">
                                                <button wire:click="editDepartment('{{ $department->uuid }}')" 
                                                        class="flex items-center justify-center py-4 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors duration-150">
                                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button @click="$dispatch('open-sub-dept-modal', {
                                                        uuid: '{{ $department->uuid }}',
                                                        department_name: '{{ $department->department_name }}',
                                                        id: {{ $department->id }}
                                                    })" 
                                                        class="flex items-center justify-center py-4 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors duration-150">
                                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Add Sub
                                                </button>

                                                <button wire:click="deleteDepartment('{{ $department->uuid }}')" 
                                                        wire:confirm="Are you sure you want to delete this department?"
                                                        class="flex items-center justify-center py-4 text-sm font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 transition-colors duration-150">
                                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
            </div>
        </div>
    </div>
        <!-- Modal -->
        <div x-show="showModal" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
            x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showModal = false"
                    aria-hidden="true">
                </div>

                <!-- Modal panel -->
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    
                    <!-- Modal header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900" id="modal-title">
                                Create Category
                            </h3>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Parent department info -->
                    <div class="mt-4 bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Parent Department</div>
                        <div class="mt-1 text-lg font-medium text-gray-900" x-text="parentDepartment?.department_name"></div>
                    </div>

                    <!-- Form -->
                    <form  @submit.prevent="$wire.createSubDepartment(parentDepartment?.uuid, form).then(() => { form = { name: '' }; })"  class="mt-6 space-y-6">
                        <!-- Department Name -->
                        <div>
                            <label for="sub-department-name" class="block text-sm font-medium text-gray-700">
                                Category Name
                            </label>
                            <input type="text" 
                                id="sub-department-name"
                                x-model="form.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter sub-department name"
                                required>
                            <input type="hidden" 
                                id="department-id"
                                x-model="parentDepartment?.uuid"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                        </div>
                        <!-- Footer -->
                        <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="ml-4 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                <span wire:loading.remove wire:target="createSubDepartment">
                                    Create Category
                                </span>
                                <span wire:loading wire:target="createSubDepartment">
                                    Creating...
                                </span>
                            </button>
                            <button type="button"
                                    @click="showModal = false"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>