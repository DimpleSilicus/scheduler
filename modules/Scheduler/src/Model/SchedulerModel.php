<?php
/**
 * Scheduler class to add / edit / delete user scheduler details.
 *
 * @name       SchedulerModel.php
 * @category   Scheduler
 * @package    Scheduler
 * @author     Dimple Agarwal<dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;
use Modules\Scheduler\Model\DailyScheduler;
use Modules\Scheduler\Model\SchedulerDay;
use Modules\Scheduler\Model\SchedulerTime;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
/**
 * Scheduler class for scheduler functionality like inserting scheduler data in master table of scheduler.
 * get all daily scheduler details, delete scheduler by scheduler id
 *
 * @category Scheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name Scheduler
 * @version 
 * @link http://google.com
 */
class Scheduler extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';

    protected $table = 'scheduler';

    public $timestamps = false;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Function to add scheduler details.
     *
     * @name InsertSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return array
     */   
    
    public static function InsertSchedulerDetails($userId,$schedulerDetail)
    {
        $objMember = new self();                 
        
        $objMember->name = $schedulerDetail['schedulerName'];
        $objMember->type = $schedulerDetail['schedulerType'];  //email or batch
        $objMember->interval = $schedulerDetail['schedulerInterval']; //(daily,weekly,monthly)
        $objMember->template_id = $schedulerDetail['scheduleTemplate'];
        $objMember->start_date = $schedulerDetail['schedulerFromDate']; 
        $objMember->end_date = $schedulerDetail['schedulerToDate'];         
        $objMember->user_id = $userId;       
        
        $success = $objMember->save();
        $month=null;
//        print_r($schedulerDetail['schedulerDateMultiple']);
        $schedulerTimes=$schedulerDetail['schedulerDateMultiple'];
        $count=count($schedulerTimes);
        if($success)
        {
            if($schedulerDetail['schedulerInterval'] == 'daily')
            {
                for($i=0;$i<$count;$i++)
                {
//                    echo "time:".$schedulerDetail['schedulerDateMultiple'][$i];
//                     $dailyResponse= DailyScheduler::InsertDailyScheduler($objMember->id,$schedulerTimes[$i]);
                     $dailyResponse= SchedulerTime::InsertTimeScheduler($objMember->id,$schedulerTimes[$i]);
                }

            }
            //For Weekly and monthly
            else if($schedulerDetail['schedulerInterval'] == 'weekly' || $schedulerDetail['schedulerInterval'] == 'monthly')
            {
                $weeklyDay=$schedulerDetail['day'];                
                $schedulerData=array($weeklyDay,$schedulerTimes);
                if($schedulerDetail['schedulerInterval'] == 'monthly')
                {
                    $month=$schedulerDetail['month'];
                }
                $dailyResponse= SchedulerDay::InsertWeeklyScheduler($objMember->id,$schedulerData,$month);
                
            }
            return $dailyResponse;
        }
        else
        {
            return 0;
        }
        
    }
    
    /**
     * Function to get all daily scheduler details user wise.
     *
     * @name getAllDailySchedulerbyUserId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public static function getAllDailySchedulerbyUserId($userId) {        
        
        $AllScheduler = self::select('scheduler.name','scheduler.interval','scheduler.status','scheduler.id as sid',DB::raw('group_concat(time) as time_of_day'))
//                            ->join('daily_scheduler as ds', 'ds.s_id', '=', 'scheduler.id')
                            ->join('scheduler_interval as si', 'si.s_id', '=', 'scheduler.id')
                            ->where('scheduler.user_id', '=', $userId)
                            ->groupBy('scheduler.id')
                            ->get()
                            ->toArray();
        return $AllScheduler;
    }    
    
    /**
     * Function to delete scheduler.
     *
     * @name deleteSchedulerBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function deleteSchedulerBySchedulerId($schedulerId) {
        
        $deleteScheduler=SchedulerDay::getDayBySchedulerId($schedulerId);
        //        print_r($deleteScheduler);
        $objSchedule = Scheduler::find($schedulerId);
        $success = $objSchedule->delete();   
        SchedulerDay::deleteDataBySchedulerId($schedulerId);
        SchedulerTime::DeleteIntervalTimeByschedulerId($schedulerId);
        foreach ($deleteScheduler as $key => $value)
        {
            SchedulerIntersect::DeleteIntersect($value['id']);
        }

        return $success;
    }
    
    /**
     * Function to get scheduler details by scheduler id.
     *
     * @name getSchedulerDetailsBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getSchedulerById($schedulerId)
    {
         return Scheduler::select("email_template.name as temp_name", "scheduler.*")->join("email_template", "email_template.id", "=", "template_id")
                 ->where("scheduler.id", $schedulerId)
                 ->first()->toArray();
    }
    
    /**
     * Function to get update scheduler details by user id.
     *
     * @name UpdateSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function UpdateSchedulerDetails($userId, $schedulerDetail)
    {        
        $objMemberUpdate = Scheduler::find($schedulerDetail['schedulerId']);
        $objMemberUpdate->name = $schedulerDetail['schedulerName'];
        $objMemberUpdate->type = $schedulerDetail['schedulerType'];  //email or batch
        $objMemberUpdate->interval = $schedulerDetail['schedulerInterval']; //(daily,weekly,monthly)
        $objMemberUpdate->start_date = $schedulerDetail['schedulerFromDate']; 
        $objMemberUpdate->end_date = $schedulerDetail['schedulerToDate'];         
        $objMemberUpdate->user_id = $userId;  
        $success = $objMemberUpdate->save();
        $schedulerTimes=$schedulerDetail['schedulerDateMultiple'];
        $schedulerTimesEdit=$schedulerDetail['schedulerDateMultipleEdit'];
        $arrDaily= SchedulerTime::getIntervalBySchedulerId($schedulerDetail['schedulerId']);
        $existArr= SchedulerDay::getDayBySchedulerId($schedulerDetail['schedulerId']);
        
        if($success)
        {
            //For new added time.
            if (is_array($schedulerTimes))
            {
                    //InsertDailyScheduler
                    $timearr=SchedulerDay::InsertTimeInterval($schedulerDetail['schedulerId'],$schedulerTimes);
                    if ($schedulerDetail['schedulerInterval'] == 'weekly' || $schedulerDetail['schedulerInterval'] == 'monthly' ) 
                    {
                        if (is_array($existArr) && !empty($existArr))
                        {
                            $existArrIndex= array_values($existArr);
                            for($i=0;$i<count($timearr);$i++)
                            {
                                SchedulerIntersect::InsertIntersect($existArrIndex[0]['id'],$timearr[$i]);
                            }
                        }
                    }
            }
            //For Update Existing Time.
            if (is_array($schedulerTimesEdit) && !empty($schedulerTimesEdit))
            {                       
                //For Edit time which is alredy present.
                foreach ($arrDaily as $key => $value){                         
                    if(!in_array($key, array_keys($schedulerTimesEdit))) {                            
                        $intervalResponse= SchedulerTime::DeleteIntervalTime($key);
                        $dailyResponse=SchedulerIntersect::DeleteIntersect($key);
                    } else {                            
                        $dailyResponse= SchedulerTime::updateIntervalTime($key,$schedulerTimesEdit[$key]);   
                    }                        
                }
            }                
            if ($schedulerDetail['schedulerInterval'] == 'weekly' || $schedulerDetail['schedulerInterval'] == 'monthly' ) 
            {
                $month=NULL;
                $day=$schedulerDetail['day'];
                $dayEdit=$schedulerDetail['dayEdit'];
                
                $schedulerData=array($day,$schedulerTimes);
                if($schedulerDetail['schedulerInterval'] == 'monthly')
                {
                    $month=$schedulerDetail['month'];
                }
                if(is_array($day))
                {
                    //return recenly inserted day array from scheduler_day table.
                    $dayArray= SchedulerDay::InsertWeeklyScheduler($schedulerDetail['schedulerId'],$schedulerData,$month);
                }
                //For existing added day or month.
                if (is_array($dayEdit))
                {
                    foreach ($existArr as $key => $value)
                    {
                        if(!in_array($key, array_keys($dayEdit))) {
                            $intervalResponse= SchedulerDay::DeleteInterval($key);
                            $dailyResponse= SchedulerIntersect::DeleteIntersect($key);
                            //Delete from intersect table also.
                            
                        } else {                                 
//                            echo "key::".$key,"::::".$dayEdit[$key];
                            $dailyResponse= SchedulerDay::UpdateInterval($key,$dayEdit[$key],$month);                            
                        }
                    }                    
                }
                else
                {
//                  If user has changed month at time of edit.
                    foreach ($existArr as $key => $value)
                    {
                        if(!in_array($key, array_keys($dayArray)))
                        {
                            SchedulerIntersect::DeleteIntersect($key);
                        }
                    }                    
                    foreach ($existArr as $key => $value)
                    {
                        SchedulerDay::DeleteInterval($key);
                    } 
                }
            }   
        }
        echo $dailyResponse;
        return $dailyResponse;
    }
    
    /**
     * Function to get history of scheduler details.
     *
     * @name UpdateSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function getHistory()
    {
        $SchedulerHistory = self::select('scheduler.name','scheduler.interval','scheduler.status','scheduler.id as sid')
                            ->join('scheduler_history as sh', 'sh.s_id', '=', 'scheduler.id')                                                        
                            ->get()
                            ->toArray();
        return $SchedulerHistory;
    }

    public static function getDailySchedulerData()
    {
        $dailyScheduler = self::select('scheduler.*','sint.time')
          ->join('scheduler_interval as sint', 'sint.s_id', '=', 'scheduler.id')
                ->where('scheduler.interval','=','daily')
                ->get()
                ->toArray();
        return $dailyScheduler;
    }    
    
}
