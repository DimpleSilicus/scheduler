<?php
/**
 * Time Scheduler class to add / edit / delete time of scheduler for weekly and monthly data.
 *
 * @name       TimeScheduler.php
 * @category   TimeScheduler
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       Scheduler
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * SchedulerTime class for time scheduler functionality like inserting  time of scheduler in DB.
 *
 * @category SchedulerTime
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name SchedulerTime
 * @version 
 * @link http://google.com
 */

class SchedulerTime extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';

    protected $table = 'scheduler_interval';

    public $timestamps = false;
    protected $dates = ['deleted_at'];
    /**
     * Function to add weekly scheduler details in weekly scheduler table also appropriate time in dependent table.
     * @name InsertWeeklyScheduler
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    
    public static function InsertTimeScheduler($scheduleId,$time)
    {
        $objTime = new self();
        $objTime->s_id = $scheduleId;
        $objTime->time = $time;
        $objTime->save();        
        return $objTime->id;
    }
    
    /**
     * Function to get scheduler details by scheduler id by key of interval table.
     * @name getIntervalBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getIntervalBySchedulerId($scheduleId)
    {
        $schedulerInt = self::select('id','s_id','time')
            ->where('s_id', '=', $scheduleId)
            ->get()->keyBy('id')->toArray();
        
        return $schedulerInt;
    }     
    
    /**
     * Function to get Interval details by scheduler id of interval table.
     * @name getIntervalById
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getIntervalById($scheduleId)
    {
        $interval = self::select('id','s_id','time')
            ->where('s_id', '=', $scheduleId)
            ->get()->toArray();
//        ->keyBy('id')
        return $interval;
    }    
    
    /**
     * Function to update Interval details by scheduler id.
     * @name updateIntervalTime
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function updateIntervalTime($scheduleId,$scheduleTime)
    {
        $objSchUpdate = SchedulerTime::find($scheduleId);
        $objSchUpdate->time = $scheduleTime;  //email or batch    
        $success = $objSchUpdate->save();        
        return $success;
    }
    
    /**
     * Function to delete Interval details by id.
     * @name DeleteIntervalTime
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function DeleteIntervalTime($id)
    {
        $objSchedule = SchedulerTime::find($id);
        $success = $objSchedule->delete();        
        return $success;
    }
    
    /**
     * Function to delete Interval details by scheduler id.
     * @name DeleteIntervalTimeByschedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function DeleteIntervalTimeByschedulerId($schedulerId)
    {
        $objSchedule = SchedulerTime::where('s_id','=',$schedulerId);
        $success = $objSchedule->delete();        
        return $success;
    }
    
    public static function getIntervalByMultipleId($ids)
    {
        $interval = self::select('id','s_id','time')
            ->whereIn('id',$ids)
//            ->where('s_id', '=', $scheduleId)
            ->get()->toArray();
        return $interval;
    }
}
