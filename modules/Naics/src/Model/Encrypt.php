<?php
/**
 * BankDetails class operate data batch wise
 *
 * @name       BankDetails.php
 * @category   Scheduler
 * @package    Scheduler
 * @author     Dimple Agarwal<dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       None
 * @filesource
 */
namespace Modules\Naics\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


/**
 * Class is to operate bank data.
 *
 * @category Scheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name Scheduler
 * @version 
 * @link http://google.com
 */
class Encrypt extends Model
{
   
    protected $primaryKey = 'id';

    protected $table = 'encrypt';

    public $timestamps = false;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
//    protected $dates = ['deleted_at'];
    
    /**
     * Function to add scheduler details.
     *
     * @name InsertSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return array
     */   
    
        
    public static function randomEncryt()
    {
        echo "model";
    }
    
}
