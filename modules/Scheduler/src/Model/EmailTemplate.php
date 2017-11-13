<?php
/**
 * Mailbox class to add / edit / delete user privacy settings
 *
 * @name       UserPrivacy.php
 * @category   UserPrivacy
 * @package    Profile
 * @author     Amol Savat <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * EmailTemplate class to get template detail.
 *
 * @category EmailTemplate
 * @package EmailTemplate
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name EmailTemplate
 * @version 
 * @link http://google.com
 */

class EmailTemplate extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'email_template';

    public $timestamps = false;

    /**
     * Function to get email template.
     *
     * @name GetTemplate
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return array
     */

    public static function GetTemplate($template_id=NULL)
    {   
        if($template_id!= NULL)
        {            
            $arrRequests = self::select('*')
            ->where('email_template.id','=',$template_id)
            ->get()
            ->toArray();
        }
        else
        {
           $arrRequests = self::select('*')
                                ->get()
                                ->toArray();
        }
            
        return $arrRequests;
    } 
}
