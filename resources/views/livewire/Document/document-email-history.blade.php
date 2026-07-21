<!-- Add this to your blade template -->
<!-- Email History Modal -->
<div
    x-data="{ open: false }"
    x-show="open"
    x-init="
        window.addEventListener('open-email-history-modal', () => { open = true });
        window.addEventListener('close-email-history-modal', () => { open = false });
    "
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="backdrop-filter: blur(2px); display: none;"
>
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="open = false"
        class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-3xl m-4"
    >
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email History: {{ $selectedDocumentTitle }}
            </h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                @if(count($emailLogs) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachments</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($emailLogs as $log)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->sent_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->recipient_email }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->subject }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open" class="text-blue-500 hover:text-blue-700">
                                                {{ $log->attachments_count }} {{ Str::plural('file', $log->attachments_count) }}
                                            </button>
                                            <div x-show="open" class="mt-2 bg-gray-50 p-2 rounded text-xs">
                                                <ul class="list-disc list-inside">
                                                    @foreach($log->emailAttachments as $attachment)
                                                        <li>{{ $attachment->file_name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->sentBy->name ?? 'Unknown' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-6 text-gray-500">
                        No emails have been sent for this document yet.
                    </div>
                @endif
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 rounded-b-lg border-t border-gray-200 flex justify-end">
            <button 
                @click="open = false" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Close
            </button>
        </div>
    </div>
</div>