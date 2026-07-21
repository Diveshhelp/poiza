{{-- File Location:- resources/views/livewire/type-of-work-format/type-of-work-collections.blade.php --}}
<div>
    {{-- Centered Notification Component --}}
    <div x-data="{ 
        show: @entangle('notification.show'),
        timer: null,
        progress: 100,
        progressInterval: null,
        autoClose() {
            if (this.timer) clearTimeout(this.timer);
            if (this.progressInterval) clearInterval(this.progressInterval);
            
            this.progress = 100;
            
            const startTime = Date.now();
            const duration = 3000;
            this.progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                this.progress = Math.max(0, Math.round((1 - elapsed/duration) * 100));
                if (this.progress <= 0) clearInterval(this.progressInterval);
            }, 20);

            this.timer = setTimeout(() => {
                this.show = false;
                @this.set('notification.show', false);
            }, duration);
        }
    }"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-0"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @notify.window="show = true; autoClose()"
        x-init="$watch('show', value => { if (value) autoClose() })"
        class="fixed inset-x-0 top-4 z-50 flex items-center justify-center px-4 sm:px-0">
        
        
    </div>

    <div  class="mx-auto py-2 sm:px-6 lg:px-8" wire:key="type-of-work-manager-module-{{time()}}">
        <div class="md:grid">
            <div class="md:col-span-1 flex justify-between mb-4">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">{{$moduleTitle}} Collection</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Create and manage types of work for better task categorization.
                    </p>
                </div>
                <div class="px-4 sm:px-0">
                </div>
            </div>

            
            <div class="mt-5 md:mt-0 md:col-span-2">
               <form wire:submit="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                           
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label class="block font-medium text-sm text-gray-700" for="type_of_work_title">
                                        Type of Work Title
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" wire:model="type_of_work_title" required="required"
                                            placeholder="Enter type of work title" autocomplete="type_of_work_title"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="text-red-500">@error('type_of_work_title') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <button type="submit"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 ">
                                <span class="indicator-label" wire:loading.remove wire:target={{ $isUpdate ? 'updateDataObject' : 'saveDataObject'  }}>{{ $isUpdate ? 'Update Type of Work' : 'Create Type of Work'  }}</span>
                                <span class="indicator-progress" wire:loading wire:target={{ $isUpdate ? 'updateDataObject' : 'saveDataObject'  }}>
                                    <svg wire:loading class="w-5 h-5 mr-3 ml-3 text-white animate-spin"
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

        <div class="hidden sm:block">
            <div class="py-8">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-0 sm:px-0 lg:px-0">
                    @if($type_of_works_list->isEmpty())
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18h0v1c0 1-1 2-2 2H8c-1 0-2-1-2-2V7c0-1 1-2 2-2h8c1 0 2 1 2 2v11zm-4-9H10m4 4H10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Types of Work</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new type of work.</p>
                        </div>
                    @else
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Types of Work</h1>
                                <p class="mt-2 text-sm text-gray-700">A list of all types of work in your organization.</p>
                            </div>
                        </div>

                        <div class="mt-3 px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <ul role="list" class="divide-y divide-gray-100">
                                @foreach($type_of_works_list as $typeOfWork)
                                    <li class="flex items-center justify-between gap-x-6 py-5">
                                        <div class="min-w-0">
                                            <div class="flex items-start gap-x-3">
                                                <p class="text-sm font-semibold leading-6 text-gray-900">{{$typeOfWork->type_of_work_title}}</p>
                                                @if($typeOfWork->row_status == 1)
                                                    <p class="-mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Active
                                                    </p>
                                                @else
                                                    <p class="-mt-0.5 whitespace-nowrap rounded-md bg-yellow-50 px-1.5 py-0.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                                        Inactive
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                                <p class="whitespace-nowrap">Created <time datetime="{{ $typeOfWork->created_at }}">{{ $typeOfWork->created_at->diffForHumans() }}</time></p>
                                                <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                                                    <circle cx="1" cy="1" r="1" />
                                                </svg>
                                                <p class="truncate">by {{$typeOfWork->createdUser->name ?? ''}}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-none items-center gap-x-4">
                                            <div x-cloak x-data="{ open: false }" class="relative flex-none">
                                                <button @click="open = !open" type="button" class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900" id="options-menu-0-button" aria-expanded="false" aria-haspopup="true">
                                                    <span class="sr-only">Open options</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                                    </svg>
                                                </button>

                                                <div
                                                    x-show="open"
                                                    @click.away="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                                    role="menu"
                                                    aria-orientation="vertical"
                                                    aria-labelledby="options-menu-0-button"
                                                    tabindex="-1"
                                                >
                                                    <button wire:click="editTypeOfWork('{{$typeOfWork->uuid}}')" class="block px-3 py-1 text-sm leading-6 text-gray-900" role="menuitem" tabindex="-1">Edit</button>
                                                    <button wire:confirm="Are you sure you want to delete this department?" wire:click="deleteTypeOfWork('{{$typeOfWork->uuid}}')" class="block px-3 py-1 text-sm leading-6 text-gray-900" role="menuitem" tabindex="-1">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>