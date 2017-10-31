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

namespace Modules\User\Controllers\Auth;

use App\User;
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

        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential('AVSbZbw7g-cdc_q-RzY63n4nLIQQJ1MsU8UHrDU3bbBUWItz7hAbt1jBHxlh3UD2VZHB56TCUvbjPxCL'
                , 'EC-rB97O5g2SHkOY-YpECsRUXXHHm9jIrj3XB9TivA5X-fL2HrHtQT1oKyEwI6P1iRyqIBE69v0Z4t-m'));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *            data to be validate
     *
     * @return void \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {

        return Validator::make($data, [
                    'email' => 'required|email|max:255|unique:users',
                    'userName' => 'required|max:255|unique:users',
                    'password' => 'required|min:8|confirmed',
                    'password_confirmation' => 'required',
                    'gedcom' => 'required'
                        ], [
                    'email.required' => 'Email ID is required.',
                    'email.email' => 'The Email must be a valid email address.',
                    'userName.required' => 'Username is required.',
                    'userName.unique' => 'The Username has already been taken.',
                    'password.required' => 'Password is required.',
                    'password.min' => 'The Password must be at least 8 characters.',
                    'password.confirmed' => 'The Password confirmation does not match.',
                    'password_confirmation.required' => 'Confirm Password is required.',
                    'gedcom.required' => 'Gedcom is required.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *            data to be validate
     *
     * @return User
     */
    protected function create(array $data) {
        $user = User::create([
                    'name' => $data['userName'],
                    'email' => $data['email'],
                    'username' => $data['userName'],
                    'password' => bcrypt($data['password']),
                    'type' => '0'
        ]);

        // add user privacy settings
        $userId = $user->id;
        $appearToWorldMap = '0';
        $appearToMyNetwork = '0';
        $pedigree = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $images = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $videos = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $journals = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $events = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );

        UserPrivacy::addUserPrivacySettings($userId, $appearToWorldMap, $appearToMyNetwork, $pedigree, $images, $videos, $journals, $events);
        // send email after successfull registration

        $messageTemplate['from'] = "Admin";
        $messageTemplate['from_email'] = "Admin@genealogy.com";
        $messageTemplate['to_email'] = $data['email']; // $data['email'];
        $messageTemplate['subject'] = "Registration confirmation.";
        $messageTemplate['body'] = "Congradulation !! you have registered successfully, with email id " . $data['email'];

        \Mail::send(Config::get('app.theme') . '.emails.registration', $messageTemplate, function ($m) use ($messageTemplate) {
            $m->from($messageTemplate['from_email'], $messageTemplate['from']);
            $m->to($messageTemplate['to_email']);
            $m->subject($messageTemplate['subject']);
        });
        
        // Email for admin
        /*
         * \Mail::send(Config::get('app.theme') . '.emails.registration', [
         * 'ms' => $messageTemplate
         * ], function ($m) use ($messageTemplate) {
         * $m->from($messageTemplate['from_email'], $messageTemplate['from']);
         * $m->to($messageTemplate['to_email']);
         * $m->subject($messageTemplate['subject']);
         * });
         * /* Email for admin
         *
         * $messageTemplate['body'] = "New registration done with below details: <br/><br/> Emailid: " . $data['email'] . "<br/> Name: " . $data['name'] . "<br/>Phone: " . $data['phone'];
         * \Mail::send(Config::get('app.theme') . '.emails.registration', [
         * 'ms' => $messageTemplate
         * ], function ($m) use ($messageTemplate) {
         * $m->from($messageTemplate['from_email'], $messageTemplate['from']);
         * $m->to(SettingsFacade::getData('email'));
         * $m->subject($messageTemplate['subject']);
         * });
         *
         *
         * $title = 'ssssss';
         * $content = 'ssssss';
         *
         * \Mail::send(Config::get('app.theme') . '.emails.registration', [
         * 'title' => $title,
         * 'content' => $content
         * ], function ($message) {
         *
         * $message->from("amol.savat@silicus.com", 'Christian Nwamba');
         *
         * $message->to("amol.savat@silicus.com");
         * });
         */
        return $user;
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
        /*
         * \Mail::send(Config::get('app.theme') . '.emails.registration', [
         * 'title' => 'dd',
         * 'content' => 'dd'
         * ], function ($message) {
         * $message->from('samtg3267@genealogynetworkhub.com', 'Laravel');
         * $message->subject('subject');
         * $message->to('amolsavat@gmail.com');
         * });
         * $data = [
         * 'key' => 'value'
         * ];
         *
         * dd(\Mail::failures());
         */
        
        $arrPackages = UserPackage::getAllPackages();

        return view($this->theme . '.auth.register')->with('arrPackages', $arrPackages);;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @name PaidMemberSignup
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function PaidMemberSignup(Request $request) {
        
//        echo "dfsd";exit;
         
        $this->validate($request, [
            'pemail' => 'required|email|max:255|unique:users,email',
            'puserName' => 'required|max:255|unique:users,userName',
            'ppassword' => 'required|min:8|confirmed',                    
            'ppassword_confirmation' => 'required',
            'gedcom' => 'required'
        ], [
                    'pemail.required' => 'Email ID is required.',
                    'pemail.email' => 'The Email must be a valid email address.',
                    'pemail.unique' => 'The Email has already been taken.',
                    'puserName.required' => 'Username is required.',
                    'puserName.unique' => 'The Username has already been taken.',
                    'ppassword.required' => 'Password is required.',
                    'ppassword.min' => 'The Password must be at least 8 characters.',
                    'ppassword.confirmed' => 'The Password confirmation does not match.',
                    'ppassword_confirmation.required' => 'Confirm Password is required.',
                    'gedcom.required' => 'Gedcom is required.',
        ]);
       
        $user = User::create([
                    'name' => $request['puserName'],
                    'email' => $request['email'],
                    'username' => $request['puserName'],
                    'password' => bcrypt($request['ppassword']),
                    'type' => '0',
                    'status' => '0'
        ]);


        // add user privacy settings
        $userId = $user->id;
        session(['SessionUserId' => $userId]);
        $appearToWorldMap = '0';
        $appearToMyNetwork = '0';
        $pedigree = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $images = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $videos = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $journals = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );
        $events = array(
            "public" => 1,
            "closeFamily" => 0,
            "relative" => 0,
            "researchConnection" => 0,
            "nobody" => 0
        );

        UserPrivacy::addUserPrivacySettings($userId, $appearToWorldMap, $appearToMyNetwork, $pedigree, $images, $videos, $journals, $events);

        $gedcomid=$request->get('gedcom');
        Cache::put('Subscription', $gedcomid,30);
        
        return $this->PaypalPayment($request,NULL);
    }
    
    public function PaypalPayment(Request $request,$amount1)
    {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        if(!isset($amount1) && $amount1 == NULL)
        {
            $amount1 = $request->get('GedComAmt');
        }
        
        
        
        $item_1->setName('Subscripiton') /** item name * */
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($amount1);/** unit price * */
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal($amount1);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paypal.status')) /** Specify return URL * */
                ->setCancelUrl(route('paypal.status'));


        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; * */
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo "<pre>" . $ex->getCode(); // Prints the Error Code
            echo "<pre>" . $ex->getData(); // Prints the detailed error message 
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session * */
        Session()->put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal * */
            return Redirect::away($redirect_url);
        }
        Session()->put('error', 'Unknown error occurred');
        return Redirect()->to('user/register');
    }

    /**
     * To get amount value based on user selection Gedcom value.
     *
     * @name GetAmountValue
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function GetAmountValue(Request $request) {

        $gedcomValue = $request->gedcomValue;
        // get users by gedcom value.
        return $arrUsers = UserPackage::getPackageDetail($gedcomValue);
    }

    /**
     * Return to this function when PayPal payment get success response
     *
     * @name getPaymentStatus
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function getPaymentStatus() {
        /** Get the payment ID before session clear * */
        $payment_id = Session()->get('paypal_payment_id');
        /** clear the session payment ID * */
        Session()->forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
//            \Session()->put('error', 'Payment failed');
            return Redirect()->to('user/register');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary * */
        /** to execute a PayPal account payment. * */
        /** The payer_id is added to the request query parameters * */
        /** when the user is redirected from paypal back to your site * */
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /*         * Execute the payment * */
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            /** it's all right * */
            /** Here Write your database logic like that insert record or value in database if you want * */
            $transaction_id = $result->getId();
            $trans = $result->getTransactions();
            
//            $package_id = $request['gedcom'];
            $amount = $trans[0]->getItemList()->getItems()[0]->getPrice();
            $user_id = Auth::id();   
            $package_id= Cache::get('Subscription');

            if(!isset($user_id) && $user_id == NULL)
            {
                $user_id= session('SessionUserId');
            }
            $status = '1';
            $transaction_response = $result->getState();
            $payment_type = $result->getPayer()->getPaymentMethod();

            $EmailIdArr = User::getUserById($user_id);


            if (empty($transaction_id) && $transaction_id == NULL) {
                $transaction_id = '';
            }
            
            if (empty($amount) && $amount = NULL) {
                $amount = '';
            }
            if (empty($user_id) && $user_id = NULL) {
                $user_id = '';
            }
            if (empty($transaction_response) && $transaction_response = NULL) {
                $transaction_response = '';
            }
            if (empty($payment_type) && $payment_type = NULL) {
                $payment_type = '';
            }

            $EmailId = $EmailIdArr[0]->email;
            $UserId = encrypt($user_id);
            $PaypalResponse = RegisterUser::saveUserDetails($user_id, $package_id, date('Y:m:d h:i:s A'), $amount, $transaction_id, $status, $transaction_response, $payment_type);
//            $body = "Congradulation !! you have registered successfully, with emailid " . $EmailId . "
//                Please verify your account by clicking here.
//                <a href='http://genealogy.dev/user/VerifyUser?userId=" . $UserId . "'>Click here</a>";
//
//            $mailArr = [
//                'body' => $body
//            ];
//
//            \Mail::send(Config::get('app.theme') . '.emails.registration', $mailArr, function ($m) use ($EmailIdArr,$body) {
//                $m->from("Admin@genealogy.com", "Admin");
//                $m->to($EmailIdArr[0]->email);
////                $m->to("dimple.agarwal@silicus.com");
//                $m->subject("Registration confirmation.");
//                $m->setBody($body,'text/html');
//            });

            User::UpdateUserType($user_id);

            if ($PaypalResponse) {
                return Redirect()->to('user/sucessRegister');
            }
        } else {
//            \Session()->put('error', 'Payment failed');
            return Redirect()->to('user/register');
        }
    }

    /**
     * showing success page after successful payment.
     *
     * @name showSucessPage
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function showSucessPage() {

        return view($this->theme . '.auth.sucess');
    }

    public function VerifyUser() {
        $InputArr = Input::all();

        if (!empty($InputArr)) {
            $userId = decrypt($InputArr['userId']);
            User::UpdateUserStatus($userId, '1');
            $Message = "Email Id verify Successfully.";
        } else {
            $Message = "Something went wrong.Please try again later.";
        }
        return view($this->theme . '.auth.verify', ['Message' => $Message]);
    }
    
    public function UpgradeUserPackage(Request $request)
    {
        // validate inputs        

        $this->validate($request, [
            'Subscription' => 'required'
                ], [
                    'Subscription.required' => 'Subscription is required.'
                ]);
        
        $gedcomid = $request->get('Subscription');
        Cache::put('Subscription', $gedcomid,30);
        
        $arrPackages = UserPackage::getPackageDetail($gedcomid);
        $amount1= $arrPackages[0]['amount'];
        
        return $obj = $this->PaypalPayment($request,$amount1);
    }

}
