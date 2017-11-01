<?php

namespace App\Listeners;

use App\Events\ActionDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\DB;

class ThingToDoAfterEventWasFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActionDone  $event
     * @return void
     */
    public function handle(ActionDone $event)
    {
        echo "dfsa".$event->userId;
//        $HistoryResult=DB::table('scheduler_history as sh')
//            ->get()
//            ->toArray();
//        
//        $objDaily = new self();   
//        
//        $objDaily->s_id = $scheduleId;
//        $objDaily->time_of_day = $scheduleTime;  //email or batch
//          
//        
//        $success = $objDaily->save();
//        
//       
//        return $success;
//        print_r($HistoryResult);
    }
}
