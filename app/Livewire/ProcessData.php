<?php

namespace App\Livewire;

use App\Models\Leave;
use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Mail;
use Session;
use Str;

class ProcessData extends Component
{
    public function render()
     {

        //Leave Entries
       $OldTableLeaveData= DB::table('raj_main_learning')->get();
       foreach($OldTableLeaveData as $k=>$v){
            $leave = new Learning();
            $leave->uuid = (string) Str::uuid();
            $userEmail= DB::table('raj_main_users')->where("id",$v->employee_id)->first();
            $MainId=User::where("email",$userEmail->email)->first()->id;
            $leave->user_id = $MainId;
            $leave->status = $v->status;
            $leave->start_date = $v->start_date;
            $leave->end_date = $v->end_date;
            $leave->total_days = Carbon::parse($v->start_date)->diffInDays(Carbon::parse($v->end_date)) + 1;

            $leave->is_full_day = $v->half_day;
            $leave->reason = $v->reason;
            $leave->team_id=32;
            $leave->save();
            sleep(1);
       }
    }
}