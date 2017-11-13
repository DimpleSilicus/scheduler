<?php
/**
 * Weekly Scheduler class to add / edit / delete weekly scheduler data.
 *
 * @name       WeeklyScheduler.php
 * @category   WeeklyScheduler
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       Scheduler
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;

use Modules\Scheduler\Model\TimeScheduler;

/**
 * DailyScheduler class for weekly scheduler functionality like inserting weekly scheduler data.
 *
 * @category WeeklyScheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name WeeklyScheduler
 * @version 
 * @link http://google.com
 */

class WeeklyScheduler extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'weekly_scheduler';

    public $timestamps = false;

    /**
     * Function to add weekly scheduler details in weekly scheduler table also appropriate time in dependent table.
     * @name InsertWeeklyScheduler
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
        
    public static function InsertWeeklyScheduler($scheduleId,$schedulerData)
    {       
        dd($schedulerData);
        
        $objDaily = new self();        
        $objDaily->s_id = $scheduleId;
        $weekCnt=count($schedulerData[0]); //Weekday count
        $timeCnt=count($schedulerData[1]); //Weekday count
        for($i=0;$i<$weekCnt;$i++)
        {
            $objDaily->day_of_week = $schedulerData[0][$i];  //email or batch    
        }
        for($i=0;$i<$timeCnt;$i++)
        {
            $timeResponse= TimeScheduler::InsertTimeScheduler($scheduleId,$schedulerData[1][$i]);
        }    
        $success = $objDaily->save();        
        return $success;
    }
}
