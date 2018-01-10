<?php

/**
 *  Laravel auth controller class file
 *
 * @name       RegisterController.php
 * @category   Controllers
 * @package    Auth
 * @author     Ajay Bhosale <ajay.bhosale@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: c8e7553654d1c055d0c5b734ea2d4c858ad92fe5 $
 * @link       None
 * @filesource
 */

namespace Modules\Scheduler\Controller\Auth;


use Modules\Profile\Model\UserPrivacy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Config;
use Modules\User\Model\UserPackage;
use Modules\User\Model\RegisterUser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Cache;
//use App\library\Paypal as PaypalLib;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FlowConfig;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\InputFields;
use PayPal\Api\Links;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PaymentHistory;
use PayPal\Api\Presentation;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\RelatedResources;
use PayPal\Api\Sale;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Api\Transactions;
use PayPal\Api\WebProfile;
use PayPal\Core\PayPalConfigManager as PPConfigManager;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

/**
 * Registration & Login Controller
 *
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors. Why don't you explore it?
 *
 * @name RegisterController
 * @category Controller
 * @package Auth
 * @author Ajay Bhosale <ajay.bhosale@silicus.com>
 * @license Silicus http://www.silicus.com/
 * @version Release:<v.1>
 * @link None
 */
class RegisterController extends Controller {
    /*
     * |--------------------------------------------------------------------------
     * | Register Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles the registration of new users as well as their
     * | validation and creation. By default this controller uses a trait to
     * | provide this functionality without requiring any additional code.
     * |
     */

use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('guest');
        
    }

    
    /**
     * Display registration form
     *
     * @name showRegistrationForm
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @return void
     */
    public function showRegistrationForm() {
                       
        return view($this->theme . '.auth.register');
    }

}
