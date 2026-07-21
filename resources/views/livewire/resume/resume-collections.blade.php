<div class="max-w-8xl mx-auto py-1 sm:px-1 lg:px-1" x-data="{ showResumeModal: false, editMode: false, resumeId: null }">
    <!-- Resume Form Modal -->
     
<div x-show="showResumeModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed overflow-auto inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" x-cloak>
     
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
        <button @click="showResumeModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">&times;</button>
            <h2 class="text-xl font-bold mb-4" x-text="editMode ? 'Edit Resume' : 'Add Resume'"></h2>
            <form wire:submit.prevent="{{ $isUpdate ? 'updateResume' : 'saveResume' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Candidate Name</label>
                        <input type="text" wire:model="resumeFields.candidate_name" class="w-full border rounded" required>
                        @error('resumeFields.candidate_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" wire:model="resumeFields.email" class="w-full border rounded">
                        @error('resumeFields.email') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Candidate Code</label>
                        <input type="text" wire:model="resumeFields.candidate_code" class="w-full border rounded">
                        @error('resumeFields.candidate_code') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Mobile</label>
                        <input type="text" wire:model="resumeFields.mobile" class="w-full border rounded">
                        @error('resumeFields.mobile') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Reference ID</label>
                        <input type="text" wire:model="resumeFields.referance_id" class="w-full border rounded">
                        @error('resumeFields.referance_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Interview Time</label>
                        <input type="datetime-local" wire:model="resumeFields.interview_time" class="w-full border rounded">
                        @error('resumeFields.interview_time') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Current CTC</label>
                        <input type="text" wire:model="resumeFields.current_ctc" class="w-full border rounded">
                        @error('resumeFields.current_ctc') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Expected CTC</label>
                        <input type="text" wire:model="resumeFields.expected_ctc" class="w-full border rounded">
                        @error('resumeFields.expected_ctc') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Notice Period</label>
                        <input type="text" wire:model="resumeFields.notice_period" class="w-full border rounded">
                        @error('resumeFields.notice_period') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Domain</label>
                        <input type="text" wire:model="resumeFields.domain" class="w-full border rounded">
                        @error('resumeFields.domain') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Other Info</label>
                        <textarea wire:model="resumeFields.other_info" class="w-full border rounded"></textarea>
                        @error('resumeFields.other_info') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Year of Experience</label>
                        <input type="number" wire:model="resumeFields.year_of_exp" class="w-full border rounded">
                        @error('resumeFields.year_of_exp') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select wire:model="resumeFields.status" class="w-full border rounded">
                            <option value="0">Draft</option>
                            <option value="1">Ready</option>
                            <option value="2">Hold</option>
                            <option value="3">Rejected</option>
                            <option value="4">Done</option>
                        </select>
                        @error('resumeFields.status') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="showResumeModal = false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button type="submit" class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                        <span x-text="editMode ? 'Update Resume' : 'Create Resume'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resume List -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Resumes</h1>
        <button @click="showResumeModal = true; editMode = false; $wire.resetForm()" class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
            + Add Resume
        </button>
    </div>
    <div class="bg-white shadow rounded-lg overflow-x-auto">
       <table class="min-w-full divide-y divide-gray-200 text-xs">
    <thead class="bg-gray-50 text-xs">
        <tr>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Cand. Code</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Name</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Mobile</th>
            
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Ref.</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Int. Time</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Current CTC</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Expected CTC</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Notice Period</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Domain</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Exp</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
            <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100 text-xs">
        @foreach($resumes as $resume)
            <tr>
                <td class="px-2 py-2 whitespace-nowrap">CAN-{{ $resume->candidate_code }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->candidate_name }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->email }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->mobile }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ implode(",",$resume->references->pluck('ref_name')->toArray()) }}</td>
                <td class="px-2 py-2 whitespace-nowrap">
                    {{ $resume->interview_time ? \Carbon\Carbon::parse($resume->interview_time)->format('d M Y, h:i A') : '-' }}
                </td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->current_ctc }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->expected_ctc }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->notice_period }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->domain }}</td>
                <td class="px-2 py-2 whitespace-nowrap">{{ $resume->year_of_exp }}</td>
                <td class="px-2 py-2 whitespace-nowrap">
                    <select
                        wire:change="updateStatus('{{ $resume->uuid }}', $event.target.value)"
                        class="px-1 py-0.5 rounded text-xs border
                            @if($resume->status == 1) bg-green-100 text-green-800
                            @elseif($resume->status == 2) bg-yellow-100 text-yellow-800
                            @elseif($resume->status == 3) bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif
                        ">
                        <option value="0" @if($resume->status == 0) selected @endif>Draft</option>
                        <option value="1" @if($resume->status == 1) selected @endif>Ready</option>
                        <option value="2" @if($resume->status == 2) selected @endif>Hold</option>
                        <option value="3" @if($resume->status == 3) selected @endif>Rejected</option>
                        <option value="4" @if($resume->status == 4) selected @endif>Done</option>
                    </select>
                </td>
                <td class="px-2 py-2 flex gap-1 whitespace-nowrap">
                    <!-- Edit Button -->
                    <button 
                        @click="showResumeModal = true; editMode = true; $wire.editResume('{{ $resume->uuid }}')" 
                        class="p-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                        title="Edit"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z" />
                        </svg>
                    </button>
                    <!-- Delete Button -->
                    <button 
                        @click="$dispatch('show-delete-modal', {id: '{{ $resume->uuid }}'});$wire.setId('{{ $resume->uuid }}')" 
                        class="p-1 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                        title="Delete"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <!-- Details Button -->
                    <button 
                        @click="$dispatch('show-detail-modal', {id: '{{ $resume->uuid }}'});$wire.setId('{{ $resume->uuid }}')" 
                        class="p-1 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-600 transition"
                        title="Details"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                        </svg>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: false, id: null }"
         x-on:show-delete-modal.window="show = true; id = $event.detail.id"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h2 class="text-lg font-bold mb-4">Delete Resume?</h2>
            <p class="mb-6">Are you sure you want to delete this resume? This action cannot be undone.</p>
            <div class="flex justify-end gap-2">
                <button @click="show = false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button @click="$wire.deleteResume(id); show = false" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </div>
    </div>
    <!-- Resume Details Modal (Attachments, Follow-ups, References) -->
    <div x-data="{ show: false, id: null, showAddRef: false, confirmDeleteRef: null, confirmDeleteAtt: null, confirmDeleteFup: null }"
     x-on:show-detail-modal.window="show = true; id = $event.detail.id"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button @click="show = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <h2 class="text-2xl font-bold mb-6 text-blue-700">Resume Details</h2>

        <!-- Attachments Section -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.485 8.485l6.586-6.586"></path>
                    </svg>
                    Attachments
                </h3>
                <div class="flex items-center gap-2">
                    <input type="file" wire:model="attachmentFile" class="border rounded text-sm px-2 py-1">
                    <button wire:click="addAttachment(id)" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Add</button>
                </div>
            </div>
            <ul class="divide-y divide-gray-100 rounded border border-gray-200 bg-gray-50">
                @forelse(App\Models\ResumeAttachment::where('resume_id', $editId)->get() as $att)
                    <li class="flex items-center justify-between px-3 py-2 hover:bg-white transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586"></path>
                            </svg>
                            <a href="{{ asset('storage/'.$att->file_path) }}" target="_blank" class="text-blue-600 hover:underline">{{ $att->file_name }}</a>
                        </div>
                          <span class="text-xs text-gray-500 ml-6 mt-1">
                    Added on {{ $att->created_at->format('d M Y, h:i A') }}
                </span>
                        <div>
                            <button 
                                @click="confirmDeleteAtt = {{ $att->id }}" 
                                class="text-red-500 ml-2 hover:underline"
                                x-show="confirmDeleteAtt !== {{ $att->id }}"
                            >Delete</button>
                            <span x-show="confirmDeleteAtt === {{ $att->id }}">
                                <span class="text-sm text-gray-500">Are you sure?</span>
                                <button wire:click="deleteAttachment('{{ $att->id }}')" class="text-red-500 ml-1">Yes</button>
                                <button @click="confirmDeleteAtt = null" class="ml-1">No</button>
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="px-3 py-2 text-gray-400 text-sm">No attachments uploaded.</li>
                @endforelse
            </ul>
        </section>

        <!-- Follow-ups Section -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3"></path>
                    </svg>
                    Follow-ups
                </h3>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model="followupDate" class="border rounded text-sm px-2 py-1">
                    <input type="text" wire:model="followupNote" placeholder="Note" class="border rounded text-sm px-2 py-1">
                    <button wire:click="addFollowup(id)" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Add</button>
                </div>
            </div>
            <ul class="divide-y divide-gray-100 rounded border border-gray-200 bg-gray-50">
                @forelse(App\Models\ResumeFollowup::where('resume_id', $editId)->orderBy('followup_date', 'desc')->get() as $fup)
                    <li class="flex items-center justify-between px-3 py-2 hover:bg-white transition">
                        <div>
                            <span class="font-medium text-gray-700">{{ date('d-M-Y',strtotime($fup->followup_date)) }}</span>
                            <span class="ml-2 text-gray-500">{{ $fup->note }}</span>
                        </div>
                        <div>
                            <button 
                                @click="confirmDeleteFup = {{ $fup->id }}" 
                                class="text-red-500 ml-2 hover:underline"
                                x-show="confirmDeleteFup !== {{ $fup->id }}"
                            >Delete</button>
                            <span x-show="confirmDeleteFup === {{ $fup->id }}">
                                <span class="text-sm text-gray-500">Are you sure?</span>
                                <button wire:click="deleteFollowup('{{ $fup->id }}')" class="text-red-500 ml-1">Yes</button>
                                <button @click="confirmDeleteFup = null" class="ml-1">No</button>
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="px-3 py-2 text-gray-400 text-sm">No follow-ups yet.</li>
                @endforelse
            </ul>
        </section>

        <!-- References Section -->
        <section>
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"></path>
                    </svg>
                    References
                </h3>
                <button @click="showAddRef = !showAddRef" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                    <span x-show="!showAddRef">Add Reference</span>
                    <span x-show="showAddRef">Cancel</span>
                </button>
            </div>
            <div x-show="showAddRef" class="mb-2 flex gap-2">
                <input type="text" wire:model="referenceName" placeholder="Reference Name" class="border rounded text-sm px-2 py-1 flex-1">
                <button wire:click="addReference(id)" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Add</button>
            </div>
            <ul class="divide-y divide-gray-100 rounded border border-gray-200 bg-gray-50">
                @forelse(App\Models\ResumeReference::where('resume_id', $editId)->get() as $ref)
                    <li class="flex items-center justify-between px-3 py-2 hover:bg-white transition">
                        <span class="text-gray-700">{{ $ref->ref_name }}</span>
                        <div>
                            <button 
                                @click="confirmDeleteRef = {{ $ref->id }}" 
                                class="text-red-500 ml-2 hover:underline"
                                x-show="confirmDeleteRef !== {{ $ref->id }}"
                            >Delete</button>
                            <span x-show="confirmDeleteRef === {{ $ref->id }}">
                                <span class="text-sm text-gray-500">Are you sure?</span>
                                <button wire:click="deleteReference('{{ $ref->id }}')" class="text-red-500 ml-1">Yes</button>
                                <button @click="confirmDeleteRef = null" class="ml-1">No</button>
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="px-3 py-2 text-gray-400 text-sm">No references added.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
</div>