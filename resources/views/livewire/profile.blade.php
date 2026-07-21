<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="mt-10 sm:mt-0">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">Profiles</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        All profiles with prefilled collection sets.
                    </p>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{route("ai-content")}}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Go to AI Content
                    </a>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-0 sm:px-0 lg:px-0">
                    <div class="mt-8 flow-root">

                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                            x-data="{ showConfirmDialog: false, profileToDelete: null }">

                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                @include("livewire.common.messages")
                                <div class="overflow-hidden mt-2">
                                    @if($data_set->isEmpty())
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" aria-hidden="true">
                                                <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No profiles</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new profile from
                                                <a href="{{route("ai-content")}}">AI Content</a>.</p>
                                        </div>
                                    @else
                                        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                            @foreach ($data_set as $key => $dataValue)
                                                <li
                                                    class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white shadow">
                                                    <!-- Content section -->
                                                    <div class="flex-1 p-6">
                                                        <div class="flex-1 truncate">
                                                            <div class="flex items-center space-x-3">
                                                                <h3 class="truncate text-sm font-medium text-gray-900">
                                                                    {{$dataValue->profile_name}}
                                                                </h3>
                                                            </div>
                                                            @if($profileDetails = $dataValue->getProfileDetails())
                                                                @foreach($profileDetails as $key => $value)
                                                                    @if(!empty($value))
                                                                        <p class="mt-1 truncate text-sm text-gray-500">
                                                                            <span
                                                                                class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                            <span>{{ $value }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            <p class="mt-1 truncate text-sm text-gray-500">
                                                                <span class="font-semibold">Created at:</span>
                                                                <span>{{$dataValue->created_at ?? ''}}</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Delete button section -->
                                                    <div class="mt-auto">
                                                        <div class="flex divide-x divide-gray-200">
                                                            <div class="flex w-full">
                                                                <button
                                                                    @click="showConfirmDialog = true; profileToDelete = '{{ $dataValue->uuid }}'"
                                                                    class="relative inline-flex w-full items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" class="size-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                    Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <!-- Confirmation Dialog -->
                                        <div x-show="showConfirmDialog" x-trap="showConfirmDialog"
                                            class="fixed inset-0 z-1111 overflow-y-auto" aria-labelledby="modal-title"
                                            role="dialog" aria-modal="true">
                                            <div
                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                    aria-hidden="true"></div>

                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                    aria-hidden="true">&#8203;</span>

                                                <div
                                                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div
                                                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <!-- Heroicon name: outline/exclamation -->
                                                                <svg class="h-6 w-6 text-red-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                                    aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                                    id="modal-title">
                                                                    Delete Profile
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500">
                                                                        Are you sure you want to delete this profile? This
                                                                        action cannot be undone.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <button type="button"
                                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                            @click="$wire.deleteProfile(profileToDelete); showConfirmDialog = false">
                                                            Delete
                                                        </button>
                                                        <button type="button"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                            @click="showConfirmDialog = false">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>