<div class="bg-gray-50 py-4 px-4 sm:px-4 lg:px-4">
    <div wire:loading.class="opacity-50" wire:target="saveForm" class="max-w-full mx-auto">
        
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 p-3 rounded-lg {{ session('alert-type') === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' }}">
                <div class="flex items-center">
                    @if(session('alert-type') === 'success')
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    <span class="font-medium text-sm">{{ session('message') }}</span>
                </div>
            </div>
        @endif

        <!-- Main card -->
        <div class="bg-white shadow-lg rounded-lg relative overflow-hidden border border-gray-200">
            <!-- Loading overlay -->
            <div wire:loading wire:target="saveForm" class="absolute inset-0 bg-white bg-opacity-95 flex items-center justify-center z-50">
                <div class="flex items-center space-y-2">
                    <div class="w-8 h-8 border-3 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                    <span class="text-gray-700 font-medium ml-3">Saving...</span>
                </div>
            </div>

            <!-- Header -->
            <div class="bg-gray-800 text-white px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h1 class="text-xl font-bold">Labour License Process Chart</h1>
                    <button wire:click="exportJson" 
                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export JSON
                    </button>
                </div>
            </div>
            
            <!-- Form start -->
			<form wire:submit.prevent="saveForm" class="p-4">
                @csrf
                <input type="hidden" name="id" value="{{$TicketsData['id']}}">
                
                <!-- Establishment Info -->
                <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header with company name -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800">{{$TicketsData['establish_name']}}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details section -->
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nature of Work -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wider font-medium mb-1">Nature of Work</p>
									<p class="text-gray-800 font-semibold text-sm">{{ $natureOfWork->title }}</p>
                                </div>
                            </div>
                            
                            <!-- Creation Date -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h6m-6 0a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2m-6 0H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wider font-medium mb-1">Created Date</p>
                                    <p class="text-gray-800 font-semibold text-sm">{{date('d/m/Y h:i A',strtotime($TicketsData['created_new_date']))}}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status indicator -->
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-gray-700 text-sm font-medium">Labour License Registration Process</span>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">Active</span>
                                </div>
                                <div class="text-gray-500 text-sm">
                                    Process ID: {{$TicketsData['id']}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <!-- Process Steps -->
                <div class="space-y-3">
                    <?php $stepNames = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twlve', 'tharteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen']; ?>
					@foreach($stepNames as $index => $step)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex gap-3">
                            <!-- Step Number and Checkbox -->
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 rounded-full font-semibold text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <input type="checkbox" wire:model="processSteps.{{ $step }}.checkbox" 
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Step Description -->
                                <div class="mb-3">
                                    <div class="text-gray-800 font-medium text-sm leading-relaxed">
                                        @switch($step)
                                            @case('one')
                                                DOCUMENT RECEIVED AS PER CHECKLIST
                                                @break
                                            @case('two')
                                                AS PER CHECK LIST MISSING DOCUMENT
                                                @break
                                            @case('three')
                                                <div class="space-y-2">
                                                    <div>LLS WITH EFFECT FROM 
                                                        <input type="text" wire:model.lazy="processSteps.three.3_from"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 onetime" 
                                                            placeholder="From date" size="10"> TO 
                                                        <input type="text" wire:model.lazy="processSteps.three.3_to"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 onetime" 
                                                            placeholder="To date" size="10">
                                                    </div>
                                                </div>
                                                @break
                                            @case('four')
                                                <div class="space-y-2">
                                                    <div>TOTAL EMPLOYEE 
                                                        <input type="text" wire:model.lazy="processSteps.four.4_emp"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Employee count" size="6">
                                                    </div>
                                                </div>
                                                @break
                                            @case('five')
                                                <div class="space-y-2">
                                                    <div>REG. FEE Rs. 
                                                        <input type="text" wire:model.lazy="processSteps.five.5_reg_fee"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Reg fee" size="6">, 
                                                        SEC. DEPOSIT (EE*540) Rs. 
                                                        <input type="text" wire:model.lazy="processSteps.five.5_sec_fee"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Sec deposit" size="6">
                                                    </div>
                                                    <div>CONSULTING FEES Rs. 
                                                        <input type="text" wire:model.lazy="processSteps.five.5_our_fee"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Our fee" size="6">, 
                                                        OTHER EXP. 
                                                        <input type="text" wire:model.lazy="processSteps.five.5_other_fee"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Other exp" size="6">
                                                        TOTAL Rs. 
                                                        <input type="text" wire:model.lazy="processSteps.five.5_total_fee"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Total" size="6">
                                                    </div>
                                                </div>
                                                @break
                                            @case('six')
                                                <div class="space-y-2">
                                                    <div>ABOVE FEES RECEIVED ? (Y/N) 
                                                        <input type="text" wire:model.lazy="processSteps.six.6_y_n"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Y/N" size="3">
                                                    </div>
                                                </div>
                                                @break
                                            @case('seven')
                                                PREAPARE SECURITY DEPOSITE CHALLAN AND GET SIGN & SEAL OF CONCERN LABOUR OFFICE AND PAY AT TRESARY BRANCH
                                                @break
                                            @case('eight')
                                                CREATE NEW INVESTOR REGISTRATION AT IFP PORTAL
                                                @break
                                            @case('nine')
                                                <div class="space-y-2">
                                                    <div>NEW IFP ID 
                                                        <input type="text" wire:model.lazy="processSteps.nine.ifp_id"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="IFP ID" size="10"> & PASSWORD 
                                                        <input type="text" wire:model.lazy="processSteps.nine.ifp_pass"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Password" size="10"> SAVE IN 2,25 SCREEN NO 20. IF COMPANY IS NOT IN SOFTWARE THEN SAVE IN ITS FOLDER.
                                                    </div>
                                                </div>
                                                @break
                                            @case('ten')
                                                ADD COMPANY NAME & ADDRESS IN OUR SOFTWARE FROM MENU NO. 2;1;10;7 IF COMPANY IS NOT IN SOFTWARE.
                                                @break
                                            @case('eleven')
                                                LOG IN INTO IFP PORTAL USING ABOVE ID, PASSWORD & CREATE NEW PROJECT
                                                @break
                                            @case('twlve')
                                                GO TO CREATE PROJECT ->PROJECT REQUEST LIST ->CLICK ON CLICK HERE ->CLICK ON "APPLY OF LICENSE UNDER LABOUR ACT -> SELECT LANGAUGE AND CONCERN LOCATION
                                                @break
                                            @case('tharteen')
                                                FILL UP ALL DETAILS IN FORM & SUBMIT THE APPLICATION
                                                @break
                                            @case('fourteen')
                                                <div class="space-y-2">
                                                    <div>PAY LICENSE FEES ONLINE AND NOTE DOWN LICENSE APPLICATION NO 
                                                        <input type="text" wire:model.lazy="processSteps.fourteen.14_llc_fees"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Application No" size="12">
                                                    </div>
                                                </div>
                                                @break
                                            @case('fifteen')
                                                SEND APPLICATION NO., OWNER NAME, COMPANY NAME AND NO. OF EMPLOYEES TO SIR
                                                @break
                                            @case('sixteen')
                                                DOWNLOAD, SAVE & MAIL LABOUR LICENCE COPY
                                                @break
                                            @case('seventeen')
                                                DO ENTRY IN CALANDER FOR EXPIRING YEAR
                                                @break
                                            @case('eighteen')
                                                <div class="space-y-2">
                                                    <div>INSERT BILL ENTRY IN EXCELL SHEET SAVED IN THE LABLE OF DHIRUBHAI OF RS. 
                                                        <input type="text" wire:model.lazy="processSteps.eighteen.18_llc_fees"
                                                            class="mx-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                                            placeholder="Amount" size="8">
                                                    </div>
                                                </div>
                                                @break
                                            @case('nineteen')
                                                MARK THIS TICKET AS CLOSE
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                
                                <!-- Bottom section with comments, user, date -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 pt-3 border-t border-gray-200">
                                    <div class="lg:col-span-2">
                                        <textarea rows="2" wire:model.lazy="processSteps.{{ $step }}.comment" 
                                            class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                            placeholder="Comments..."></textarea>
                                    </div>
                                    <div class="space-y-2 text-xs">
                                        <div>
                                            <span class="text-gray-500">By:</span>
                                            <span class="text-gray-700 font-medium">{{ $processSteps[$step]['user_name'] ?? 'Not assigned' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Date:</span>
                                            <span class="text-gray-700">{{ $this->getFormattedDateTime($processSteps[$step]['date_time'] ?? '') ?: 'Not completed' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Form footer -->
				<div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
                    <a href="{{ url()->previous() }}" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50" 
                        wire:loading.attr="disabled"
                        wire:target="saveForm">
                        <span wire:loading.remove wire:target="saveForm" class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Process Chart
                        </span>
                        <span wire:loading wire:target="saveForm" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
	
<style>
.onetime {
    @apply px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500;
}
</style>

<script type="text/javascript">
    $(document).ready(function () {
        // Date picker initialization
        $('.onetime').datepicker({
            dateFormat: 'dd/mm/yy',
            inline: true
        });
        
        // Form validation
        $("#createProject").validate({
            rules: {
                // Add validation rules as needed
            },
            messages: {
                // Add validation messages as needed
            }
        });
    });
</script>
</div>
