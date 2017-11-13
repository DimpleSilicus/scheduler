<?php
/**
 * Daily Scheduler class to add / edit / delete daily scheduler data.
 *
 * @name       DailyScheduler.php
 * @category   DailyScheduler
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       Scheduler
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * DailyScheduler class for daily scheduler functionality like inserting daily scheduler data.
 *
 * @category DailyScheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name DailyScheduler
 * @version 
 * @link http://google.com
 */

class DailyScheduler extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'daily_scheduler';

    public $timestamps = false;

    /**
     * Function to add daily scheduler details in daily scheduler table.
     * @name InsertDailyScheduler
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
        
    public static function InsertDailyScheduler($scheduleId,$scheduleTime)
    {       
        $objDaily = new self();        
        $objDaily->s_id = $scheduleId;
        $objDaily->time_of_day = $scheduleTime;  //email or batch        
        $success = $objDaily->save();        
        return $success;
    }
    
    
    
    /**
     * Function to update daily scheduler details in daily scheduler table.
     * @name UpdateDailyScheduler
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function UpdateDailyScheduler($scheduleId,$scheduleTime)
    {
        $objSchUpdate = DailyScheduler::find($scheduleId);
//        $objSchUpdate->s_id = $scheduleId;
        $objSchUpdate->time_of_day = $scheduleTime;  //email or batch    
        $success = $objSchUpdate->save();        
        return $success;
    }
    
    /**
     * Function to get daily scheduler details by scheduler id of master table.
     * @name getDailySchedulerBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getDailySchedulerBySchedulerId($scheduleId)
    {
        $dailyScheduler = self::select('*')
            ->where('s_id', '=', $scheduleId)
            ->get()->keyBy('id')->toArray();
        
        return $dailyScheduler;
    }     
    
    /**
     * Function to delete daily scheduler details by id.
     * This return only one record.
     * @name getDailySchedulerBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function DeleteDailyScheduler($dailyId)
    {
        $objSchedule = DailyScheduler::find($dailyId);
        $success = $objSchedule->delete();        
        return $success;
    }
}
