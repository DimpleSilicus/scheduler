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
namespace Modules\Federal\Model;

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
class BankDetails extends Model
{
   
    protected $primaryKey = 'id';

    protected $table = 'bank_details';

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
    
    public static function InsertData($fileNameCSV)
    {
        $filePath= str_replace('\\','/',storage_path().'/app/'.$fileNameCSV);
        $query = 'LOAD DATA LOCAL INFILE "' . $filePath . '"
                        INTO TABLE bank_details
                        FIELDS TERMINATED BY "~"
                        LINES TERMINATED BY "\n" 
                            (routing_number,
                            telegraphic_name,
                            bank_name,
                            state,
                            city,
                            funds_transfer_status,
                            funds_settlement_status,
                            book_entry_securities,
                            @revised_date)
                            SET revised_date = nullif(@revised_date,\'\')';
        $sucess=DB::connection()->getpdo()->exec($query);
        return $sucess;
    }
    
    public static function TruncateTable()
    {
        $success=DB::table('bank_details')->truncate();
        return $success;
    }
    
    public static function getAllCount()
    {
        $bankData = self::select('*')->get()->count();
        
        return $bankData;
    }
    
    public static function insertUsingTransaction()
    {        
        $exception=DB::transaction(function () {
            
            DB::select('call update_bank_details()');        
        });
        
        return is_null($exception) ? true : $exception;
    }
}
