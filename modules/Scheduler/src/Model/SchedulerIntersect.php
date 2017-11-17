<?php
/**
 * Time Scheduler class to add / edit / delete time of scheduler for weekly and monthly data.
 *
 * @name       SchedulerIntersect.php
 * @category   SchedulerIntersect
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
 * SchedulerIntersect class for time scheduler functionality like inserting  time of scheduler in DB.
 *
 * @category SchedulerIntersect
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name SchedulerIntersect
 * @version 
 * @link http://google.com
 */

class SchedulerIntersect extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'w_id';
        
    protected $table = 'scheduler_intersect';

    public $timestamps = false;
    protected $dates = ['deleted_at'];
    
    /**
     * Function to add scheduler details in intersect table.
     * This table does not contain primary key.
     * This table is intersection of scheduler_day and scheduler_interval table.
     * 
     * @name InsertIntersect
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    
    public static function InsertIntersect($dayId,$intervalId)
    {
        $objTime = new self();
        $objTime->w_id = $dayId;
        $objTime->i_id = $intervalId;
        $success = $objTime->save();        
        return $success;
    }   
    
    /**
     * Function to delete scheduler details in intersect table by id as w_id which is id of scheduler_day table.
     * @name DeleteIntersect
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function DeleteIntersect($id)
    {
        $objSchedule = SchedulerIntersect::find($id);
        $success = $objSchedule->delete();        
        return $success;
    }
    
    /**
     * Function to get scheduler details in intersect table by id.
     * @name DeleteIntersect
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function getIntersectById($id)
    {
        $interval = self::select('w_id','i_id')
            ->where('w_id', '=', $id)
            ->get()->toArray();
//        ->keyBy('id')
        return $interval;
    }
}
