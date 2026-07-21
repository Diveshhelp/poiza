<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" wire:key="title-manager-module-{{time()}}">
        <div>
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{$moduleTitle}} collection: {{ $title_set_name }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Add product type and their specific title.
                    </p>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{route("title-format")}}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Go to collections
                    </a>
                </div>
            </div>

            <div class="mt-5 md:col-span-2">

                @if($isUpdate)
                    <form wire:submit="updateDataObject">
                @else
                    <form wire:submit="saveDataObject">
                @endif
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            @include("livewire.common.messages")

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label class="block font-medium text-sm text-gray-700" for="name">
                                        Product Type
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" wire:model="product_type_title" required="required"
                                            placeholder="Television" autocomplete="product_type_title"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="text-red-500">@error('product_type_title') {{ $message }} @enderror</div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">{{$moduleTitle}}</label>
                                    @foreach($inputs as $key => $value)
                                        <div class="flex items-center mb-2 mt-2">
                                            <input type="text" wire:model="inputs.{{ $key }}"
                                                placeholder="{color} - {brand} {model}"
                                                class="mr-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @if($key>0)
                                            <a href="javascript:void(0);" wire:click="removeInput({{ $key }})"
                                                class="ml-2 bg-red-400 text-white px-2 py-1 rounded"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </a>
                                            @endif
                                        </div>
                                        @error('inputs.' . $key) <span class="text-red-500 ">{{ $message }}</span> @enderror

                                    @endforeach
                                    <div class="mt-2">
                                        <a href="javascript:void(0);" wire:click="addInput">
                                            <div class="mt-2 relative flex w-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg> Add {{$moduleTitle}}
                                            </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">

                            <button type="submit"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 ">
                                <span class="indicator-label" wire:loading.remove wire:target={{ $isUpdate ? 'updateDataObject' : 'saveDataObject'  }}wire:target="updateDataObject">{{ $isUpdate ? "Update $moduleTitle" : "Create $moduleTitle" }}</span>
                                <span class="indicator-progress" wire:loading wire:target={{ $isUpdate ? 'updateDataObject' : 'saveDataObject'  }}><svg wire:target="updateDataObject"
                                        wire:loading class="w-5 h-5 mr-3 ml-3 text-white animate-spin"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">{{$moduleTitle}}'s per Product Type</h1>
                            <p class="mt-2 text-sm text-gray-700">A list of all the {{$moduleTitle}}'s of the product type.</p>
                        </div>
                    </div>
                    @if($initial_record_set_object->isEmpty())

                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No attributes</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new attribute.</p>

                        </div>

                    @else
                        <ul role="list" class="mt-3 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($initial_record_set_object as $dataKey => $dataValue)

                                <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow ">

                                    <div class="overflow-hidden bg-white sm:rounded-lg sm:shadow min-h-3.5">

                                        <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                                            <h3 class="text-base font-semibold leading-6 text-gray-900">
                                                {{$dataValue->product_type_title}}
                                            </h3>
                                        </div>

                                        <ul role="list" class="divide-y divide-gray-200 ">
                                            @foreach($dataValue->titleValues as $innerValue)
                                                <li class="">
                                                    <div class="block hover:bg-gray-50 ">
                                                        <div class="px-6 py-2">
                                                            <div class="flex items-center justify-between">
                                                                <div class="truncate text-sm font-medium">
                                                                    {{$innerValue->title_value}}
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </li>
                                            @endforeach

                                            <div class="-mt-px flex divide-x divide-gray-200">
                                                <div class="flex w-0 flex-1">
                                                    <a href="javascript:void(0);"
                                                        wire:click="editObject('{{$dataValue->uuid}}');"
                                                        class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </div>
                                                <div class="-ml-px flex w-0 flex-1">
                                                    <a href="javascript:void(0);"
                                                        wire:click="deleteObject('{{$dataValue->uuid}}');" wire:confirm="Are you sure you want to delete this?"
                                                        class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>

                                                        Delete
                                                    </a>
                                                </div>
                                            </div>

                                        </ul>

                                    </div>

                                </li>

                            @endforeach

                        </ul>
                    @endif


                </div>
                @if ($showImportModal)
                    @include('livewire.attributes.model.import-csv')
                @endif
            </div>
        </div>
    </div>
</div>
