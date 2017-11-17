<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Storage;

use App\Events;
use App\Events\Event;
use App\Events\ActionDone;
use Illuminate\Support\Facades\DB;

use Modules\Scheduler\Model\Scheduler;
use Modules\Scheduler\Model\SchedulerDay;
use Modules\Scheduler\Model\SchedulerIntersect;
use Modules\Scheduler\Model\EmailTemplate;
use Modules\Scheduler\Model\SchedulerTime;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
            '\App\Console\Commands\DailyScheduler',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule_old(Schedule $schedule)
    {        
        $DailyResult=DB::table('scheduler as s')    
                ->select('*', 's.id as sid')
                ->join('daily_scheduler as ds', 'ds.s_id', '=', 's.id')
                ->where('s.status','=','1')
                ->get()
                ->toArray();
        $count=count($DailyResult);
        for($i=0;$i<$count;$i++)
        {
            $finalResDaily=$DailyResult[$i];
            if($DailyResult[$i]->interval == 'daily') 
            {
                 $schedule->command('DailyScheduler:dailyschedule '.$DailyResult[$i]->interval.' '.$DailyResult[$i]->type.' '.$DailyResult[$i]->user_id.' '. $DailyResult[$i]->template_id)
                 ->everyMinute()
                ->after(function() use ($finalResDaily) {
                    // Task is complete...
                        event(new ActionDone($finalResDaily->sid,1));                          
               });
               //For running scheduler on daily basis.
//                ->dailyAt($DailyResult[$i]->time_of_day);
            }
        }
    }
    
    protected function schedule(Schedule $schedule)
    {
        $allScheduler= SchedulerDay::getAllScheduler();
        $allCnt=count($allScheduler);
        for($i=0;$i<$allCnt;$i++)
        {
            if(date('Y-m-d')> date('Y-m-d', strtotime($allScheduler[$i]['start_date'])) && date('Y-m-d')<= date('Y-m-d', strtotime($allScheduler[$i]['end_date'])))
            {
                $templateData= EmailTemplate::GetTemplate($allScheduler[$i]['template_id']);
                if($allScheduler[$i]['interval']=='weekly')
                {
                    switch ($allScheduler[$i]['s_day'])
                    {
                        case 'Monday':    
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'mondays',$allScheduler[$i]['sid']);
                            break;
                        case 'Tuesday':
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'tuesdays',$allScheduler[$i]['sid']);
                            
                            break;
                        case 'Wednesday':
                            
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'wednesdays',$allScheduler[$i]['sid']);
                            break;
                        case 'Thursday':
                            
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'thursdays',$allScheduler[$i]['sid']);
                            break;
                        case 'Friday':
                            
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'fridays',$allScheduler[$i]['sid']);
                            break;
                        case 'Saturday':
                            
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'saturdays',$allScheduler[$i]['sid']);
                            break;
                        case 'Sunday':
                            
                            $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],'sundays',$allScheduler[$i]['sid']);
                            break;
                    }
                }
                else
                {
    //                echo "else".$allScheduler[$i]['s_day'];
                    $currMonth = date('m');   
                    $day=$allScheduler[$i]['s_day'];
                    if($currMonth == $allScheduler[$i]['s_day'])
                    {                    
                        $this->RunWeekScheduler($allScheduler[$i]['id'],$schedule,$allScheduler[$i]['type'],$allScheduler[$i]['user_id'],$templateData[0]['id'],(int)$day,$allScheduler[$i]['sid']);
                    }
                }
            }
        }
        
        $dailyScheduler= Scheduler::getDailySchedulerData();
        $dailyCnt=count($dailyScheduler);
        for($j=0;$j<$dailyCnt;$j++)
        {
            $schedulerId=$dailyScheduler[$j]['id'];
            if(date('Y-m-d')> date('Y-m-d', strtotime($dailyScheduler[$j]['start_date'])) && date('Y-m-d')<= date('Y-m-d', strtotime($dailyScheduler[$j]['end_date'])))
            {                
                if($dailyScheduler[$j]['type']=='email')
                {
                    $schedule->command('DailyScheduler:dailyschedule '.$dailyScheduler[$j]['user_id'].' '. $dailyScheduler[$j]['template_id'])
//                           ->dailyAt($dailyScheduler[$j]['time']);
                               ->everyMinute()
                                ->after(function() use($schedulerId) {
                                 // Task is complete...
                                     event(new ActionDone($schedulerId,1));                          
                                });
                }
            }
        }
    }
    
     /**
     * Function to common function used to run scheduler on given basis(weekly,monthly)
     * @name DeleteIntersect
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    function RunWeekScheduler($schedulerId,$schedule,$type,$user_id,$templateId,$day,$sid)
    {            
        $stringTemp='';
        $mondayArr= SchedulerIntersect::getIntersectById($schedulerId);
        $monCnt=count($mondayArr);
        for($j=0;$j<$monCnt;$j++)
        {
            $stringTemp[] =$mondayArr[$j]['i_id'];
        }
        $timeArr= SchedulerTime::getIntervalByMultipleId($stringTemp);
        $timeCnt=count($timeArr);
        for($k=0;$k<$timeCnt;$k++)
        {
           if($type=='email')
           {               
                if(is_string($day))
                {                   
                   $schedule->command('DailyScheduler:dailyschedule '.$user_id.' '. $templateId)
//                           ->$day()->at($timeArr[$k]['time']);
                                ->everyMinute()
                                ->after(function() use ($sid) {
                                 // Task is complete...
                                     event(new ActionDone($sid,1));                          
                                });
                }
                else
                {
                    $schedule->command('DailyScheduler:dailyschedule '.$user_id.' '. $templateId)
//                           ->monthlyOn($day,$timeArr[$k]['time']);
                                ->everyMinute()
                                ->after(function() use ($sid) {
                                 // Task is complete...
                                     event(new ActionDone($sid,1));                          
                                });
                }
//                       
           }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
