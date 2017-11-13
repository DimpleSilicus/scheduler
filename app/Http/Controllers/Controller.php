<?php

/**
 *  Laravel auth controller class file
 *
 * @name       AuthController.php
 * @category   Controllers
 * @package    Auth
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/

 */
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\ToolKit\Workshop;

/**
 * Registration & Login Controller
 *
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors. Why don't you explore it?
 *
 * @name     AuthController
 * @category Controller
 * @package  Auth
 * @author   Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license  Silicus http://www.silicus.com/
 * @version  Release:<v.1>
 * @link     None
 */
class Controller extends BaseController
{
    
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Site URL
     *
     * @var string $siteUrl
     */
    public $url = '';

    /**
     * Site theme name
     *
     * @var string $theme
     */
    public $theme = '';

    /**
     * Constructer of controller class
     *
     * @name   __construct
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function __construct()
    {
        $this->url = config('app.url');
        $this->theme = config('app.theme');
    }

    /**
     * This will loda JS and CSS file dynamically
     *
     * @name   loadJsCSS
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @param array $js  description
     * @param array $css description
     *
     * @return void
     */
    public function loadJsCSS($js = null, $css = null)
    {
        if (is_array($js)) {
            view()->share('jsFiles', $js);
        }
        
        if (is_array($css)) {
            view()->share('cssFiles', $css);
        }
    }
}
