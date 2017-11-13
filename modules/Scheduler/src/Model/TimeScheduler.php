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

/**
 * TimeScheduler class for time scheduler functionality like inserting  time of scheduler in DB.
 *
 * @category TimeScheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name TimeScheduler
 * @version 
 * @link http://google.com
 */

class TimeScheduler extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'time_of_scheduler';

    public $timestamps = false;

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
        $objDaily->s_id = $time;
        $success = $objTime->save();        
        return $success;
    }
}
