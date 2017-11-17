<?php
/**
 * SchedulerDay class to add / edit / delete weekly and monthly scheduler data in same table.
 *
 * @name       SchedulerDay.php
 * @category   SchedulerDay
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       Scheduler
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;

use Modules\Scheduler\Model\SchedulerTime;
use Modules\Scheduler\Model\SchedulerIntersect; 
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * SchedulerDay class for weekly and monthly scheduler functionality like inserting scheduler data for weekly and monthly.
 *
 * @category SchedulerDay
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name WeeklyScheduler
 * @version 
 * @link http://google.com
 */

class SchedulerDay extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';

    protected $table = 'scheduler_day';

    public $timestamps = false;
    
    protected $dates = ['deleted_at'];
    /**
     * Function to add weekly scheduler details in scheduler_day table and also appropriate time in dependent table.
     * @name InsertWeeklyScheduler
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
        
    public static function InsertWeeklyScheduler($scheduleId,$schedulerData,$month=NULL)
    {
        if(isset($schedulerData[0]))
        {
            $dayCnt=count($schedulerData[0]); //Weekday count
        
            //For inserting records in scheduler_day table
            for($i=0;$i<$dayCnt;$i++)
            {      
                $objDaily = new self();        
                $objDaily->s_id = $scheduleId;
                $objDaily->s_day = $schedulerData[0][$i];  //email or batch 
                $objDaily->month = $month;
                $objDaily->save();
                $dayArr[]=$objDaily->id;
            }
        }        
        if(isset($schedulerData[1]))
        {
            $timeArr = SchedulerDay::InsertTimeInterval($scheduleId,$schedulerData[1]);  
            //                     InsertIntersect($dayId,$intervalId);
            
        }
        else
        {
            $intervalArr = SchedulerTime::getIntervalById($scheduleId);
            for($i=0;$i<count($intervalArr);$i++)
            {
                $timeArr[]=$intervalArr[$i]['id'];
            }
        }
       
        $dayArrCnt=count($dayArr);
        $timeArrCnt=count($timeArr);
        $intervalArr= SchedulerTime::getIntervalById($scheduleId);
        //For inserting records in scheduler_intersect
        for($w=0;$w<$dayArrCnt;$w++)
        {
            for($t=0;$t<$timeArrCnt;$t++)
            {                
                SchedulerIntersect::InsertIntersect($dayArr[$w],$timeArr[$t]);
            }
        }
        return $dayArr;
    }
    
    /**
     * Function to get scheduler day by scheduler id from day table.
     * @name getDayBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getDayBySchedulerId($scheduleId)
    {
        $dailyScheduler = self::select('*')
            ->where('s_id', '=', $scheduleId)
            ->get()
        ->keyBy('id')
                ->toArray();
        return $dailyScheduler;
    }     
    
    /**
     * Function for inserting records in Scheduler Interval
     * @name InsertTimeInterval
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    
    public static function InsertTimeInterval($scheduleId,$schedulerData)
    {        
        $timeCnt=count($schedulerData); //time count
        $timeArr=array();
        for($i=0;$i<$timeCnt;$i++)
        {
            $timeResponse= SchedulerTime::InsertTimeScheduler($scheduleId,$schedulerData[$i]);                       
            $timeArr[]=$timeResponse;
        }        
        return $timeArr;
    }
    
    /**
     * Function for deleting records from Scheduler Interval by id
     * @name DeleteInterval
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function DeleteInterval($id)
    {
        $objSchedule = SchedulerDay::find($id);
        $success = $objSchedule->delete();        
        return $success;
    }
       
    /**
     * Function for update records in Scheduler Interval.
     * @name UpdateInterval
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function UpdateInterval($scheduleId,$day,$month=NULL)
    {        
        $objSchUpdate = SchedulerDay::find($scheduleId);
        $objSchUpdate->s_day = $day;  //email or batch
        $objSchUpdate->month = $month;
        $success = $objSchUpdate->save();        
        return $success;
    }
    
    /**
     * Function to get weekly and monthly scheduler for running all scheduler in kernel.
     * this only get weekly and monthly because for daily scheduler_day table doesn't contain value.
     * @name UpdateSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function getAllScheduler()
    {
        //        SELECT sd.*,s.interval,s.start_date,s.end_date FROM scheduler_day as sd JOIN scheduler as s on s.id=sd.s_id JOIN scheduler_intersect as si on si.w_id=sd.id GROUP BY sd.id
        $allScheduler = self::
                    select('scheduler_day.*','s.id as sid','s.user_id','s.template_id','s.interval','s.start_date','s.end_date','s.type')
                    ->join('scheduler as s', 's.id', '=', 'scheduler_day.s_id')
                    ->join('scheduler_intersect as si', 'si.w_id', '=', 'scheduler_day.id')
                    ->groupBy('scheduler_day.id')
                    ->get()
                    ->toArray();
        
        return $allScheduler;
    }
    
    public static function deleteDataBySchedulerId($schedulerId)
    {
        $objSchedule = SchedulerDay::where('s_id','=',$schedulerId);
        $success=$objSchedule->delete(); 
//        print_r($deleteScheduler);
        return $success;
    }
}
