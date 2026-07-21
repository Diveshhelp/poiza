<div>
    <div class="mx-auto py-5 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Manage your stock categories efficiently and organize your inventory
                </p>
            </div>
        </div>

        <!-- Quick Add/Edit Form -->
        <div class="mt-6 bg-white shadow rounded-lg p-4">
            <form wire:submit="{{ $uuid ? 'updateCategory' : 'saveCategory' }}" class="w-full">
                <h3 class="text-base font-medium text-gray-900 mb-3">
                    {{ $uuid ? 'Edit Category' : 'Add New Category' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Category Name -->
                    <div>
                        <label for="category_name" class="block text-xs font-medium text-gray-700 mb-1">Category Name</label>
                        <input type="text" wire:model="category_name" id="category_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('category_name') border-red-300 @enderror">
                        @error('category_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" id="description" rows="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('description') border-red-300 @enderror"></textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('status') border-red-300 @enderror">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4 flex justify-end space-x-3">
                    @if($uuid)
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancel
                        </button>
                    @endif
                    <button type="submit"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        {{ $uuid ? 'Update Category' : 'Add Category' }}
                    </button>
                </div>
            </form>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Created Date Range -->
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
                        <input type="text" wire:model.live="searchQuery"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Search category names, descriptions, etc.">
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

        <!-- Categories Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <div class="px-4 py-4 sm:px-6 flex items-center justify-between bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Stock Categories</h3>
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $categories->firstItem() ?? 0 }}</span> to
                    <span class="font-medium">{{ $categories->lastItem() ?? 0 }}</span> of
                    <span class="font-medium">{{ $categories->total() ?? 0 }}</span> results
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created At
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->category_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $category->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <button wire:click="editCategory('{{ $category->uuid }}')"
                                        class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full p-1 transition-colors duration-150">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="deleteCategory('{{ $category->uuid }}')"
                                        wire:confirm="Are you sure you want to delete this category?"
                                        class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-full p-1 transition-colors duration-150">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Get started by creating a new category using the form above.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>