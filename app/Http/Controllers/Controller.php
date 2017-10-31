<?php

/**
 *  Laravel auth controller class file
 *
 * @name       AuthController.php
 * @category   Controllers
 * @package    Auth
 * @author     Ajay Bhosale <ajay.bhosale@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: 6fa4faa9e141a6d2c42512b0e18160e04fe6567e $
 * @link       None
 * @filesource
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
 * @author   Ajay Bhosale <ajay.bhosale@silicus.com>
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
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
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
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
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

    /**
     * Create object of Modules\ToolKit\Seo
     *
     * @name   seo
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @return object Modules\ToolKit\Seo
     */
    public function seo()
    {
        return app('seo');
    }

    /**
     * Set application meta-data
     *
     * @name   addMetadata
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @param array $metadata description
     *
     * @return void
     */
    public function addMetadata($metadata)
    {
        if (isset($metadata['title'])) {
            $this->seo()->setTitle($metadata['title']);
        }
        
        if (isset($metadata['description'])) {
            $this->seo()->setDescription($metadata['description']);
        }
        
        if (isset($metadata['keywords'])) {
            $this->seo()->setKewords($metadata['keywords']);
        }
    }

    /**
     * Add to log
     *
     * @name   addToLog
     * @access public static
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @param string $message Log message
     * @param string $module  Module name
     * @param string $type    Type of error log emergency, alert, critical, error, warning, notice, info and debug
     * @param array  $details Message details
     *
     * @return void
     */
    public function addToLog($message, $module = 'general', $type = 'error', $details = [])
    {
        Workshop::addLog($message, $module, $type, $details);
    }
}
