<div class="bg-white rounded-lg shadow p-6 ml-2">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-{{ $color }}-500 rounded-md flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="ml-5 w-0 flex-1">
            <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($value) }}</div>
                    @if($change != 0)
                    <div class="ml-2 flex items-baseline text-sm font-semibold {{ $changeType === 'increase' ? 'text-green-600' : 'text-red-600' }}">
                        @if($changeType === 'increase')
                            <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        <span class="sr-only">{{ $changeType === 'increase' ? 'Increased' : 'Decreased' }} by</span>
                        {{ abs($change) }}%
                    </div>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
</div>