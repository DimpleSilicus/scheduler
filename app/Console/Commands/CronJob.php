<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use Illuminate\Support\Facades\DB;
use Modules\Scheduler\Model\BankDetails;
use Modules\Scheduler\Model\BankDetailsTemp;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Config;

class CronJob extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CronJob:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Cron job for Bank Data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

       try {
            $response = $this->curlRequest();
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $fileName = date('Y-m-d') . '.txt';
                $fileNameCSV = date('Y-m-d') . '.csv';
                $content = $response->getBody();
                $finalResult = explode("\n", $content);

                //Lopp hole. not understood count is mismatching.
//                for($i=0;$i<2;$i++)
                $operationArray = $this->convertToArray($finalResult);
                
                //Take backup of live table before updating live table.
//                $dump = "mysqldump -u root '' scheduler bank_details > bank_details.sql";
//               $dump = 'mysqldump -u root -p scheduler bank_details > data.sql';
//              $sucess=DB::connection()->getpdo()->exec($dump); 
//               $sucess=DB::connection()->getpdo()->exec(str_replace("'", " ", $dump));
//                dd();

                //By default, this value is set to the storage/app/today'sdate.txt. 
                //Exist return boolean 1 if file exist.
                $exists = Storage::disk('local')->exists($fileNameCSV);
                if ($exists) {
                    Storage::delete($fileNameCSV);
                }
                $bytes_written = Storage::disk('local')->put($fileName, $operationArray);
                $bytesWrittenCSV = Storage::disk('local')->copy($fileName, $fileNameCSV);
                if ($bytes_written === false || $bytesWrittenCSV === false) {
                    die("Error writing to file");
                } else {
                    $success = BankDetailsTemp::TruncateTable();
                    $response = BankDetailsTemp::InsertTempData($fileNameCSV);
                    if ($response > 0) {
                        $allTempCount = BankDetailsTemp::getAllCount();
                        $allCount = BankDetails::getAllCount();
                        if ($allTempCount > 0 || $allCount >= 0) {
                            //If temp data count is greate then directly dump data to live table.
                            if ($allTempCount > $allCount) {
                                
                                //Remaining is taking backup of live data before inserting into live table
//                                $responseNew = $this->OperationOnTable($fileNameCSV, $allCount);

                                //Transaction operation to dump to live table.                                
                                $responseNew=BankDetails::insertUsingTransaction();                                
                                if ($responseNew) {
                                    //This check is for checking that 95%of data is dumped or not.
                                    $newCount = BankDetails::getAllCount(); //This count is updated count after records are inserted from temp table.
                                    if ($allTempCount == $newCount) {
                                        echo "Cron run successfully.";
                                    } else {
                                        echo "Data is not inserted successfully.";
                                        //Remaining - Insert backup table/file to live table again.
                                    }
                                } else {
                                    echo "Data is not inserted successfully. else";
                                    //Remaining - Insert backup table/file to live table again.
                                }
                            } else {
                                $percentage = $this->calculatePercentage($allTempCount, $allCount);
                                $percentageConstant=Config::get('constants.options.PERCENTAGE');
                                if ($percentage >= $percentageConstant) {
                                    //dump temp data table into live table.
//                                    $responseF = $this->OperationOnTable($fileNameCSV, $allCount);
                                    
                                    //Transaction operation to dump to live table.                                
                                    $responseF=BankDetails::insertUsingTransaction();
                                    if ($responseF) {
                                        //This check is for checking that 95%of data is dumped or not.
                                        $newCount = BankDetails::getAllCount(); //This count is updated count after records are inserted from temp table.
                                        $newPercentage = $this->calculatePercentage($allTempCount, $newCount);
                                        if ($newPercentage >= $percentageConstant) {
                                            echo "Cron Run Scuccessfully";
                                        }
                                        else {
                                            echo "Data is not inserted successfully else";
                                            //Remaining - Insert backup table/file to live table again.
                                        }
                                    }
                                }
                                //In else part don't do any action. So skip else part.
                            }
                        } else {
                            echo "Temporary table is empty.";
                        }
                        $filename = storage_path() . '/logs/bank_' . date('Y-m-d') . '.log';
                        $msg = '[' . date('Y-m-d H:i:s') . ']' . ' | Success| |' . $fileNameCSV . '|' . "\n";
                        File::append($filename, $msg);
//                        echo "Cron run successfully";
                    }
                }

//                BankDetails::TruncateTable();dd();
//                echo "res:::".$response = BankDetails::InsertTempData($fileNameCSV);  
            } else {
                echo "Data has not come from URL-Status Code -" . $statusCode;
            }
        } 
        catch (RequestException $e) {            
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
        catch(\Exception $e) {            
            print_r($e->getMessage());            
        }
        catch(\Illuminate\Database\QueryException $ex)
        { 
            print_r($ex->getMessage());
        }
        catch(\GuzzleHttp\Exception $g)
        {
            print_r($g->getMessage());
        }
        catch(\Symfony\Component\Routing\Exception $r)
        {
            print_r($r->getMessage());
        }
    }
    
    /**
     * Function is to for curl request.
     *
     * @name curlRequest
     * @access private
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return response object
     */
    private function curlRequest() {
        $frbServices = Config::get('constants.options.FRBSERVICE');
        $client = new \GuzzleHttp\Client(['verify' => false, 'cookies' => true]);
        $client->post(
                'https://www.frbservices.org/EPaymentsDirectory/submitAgreement', array(
            'headers' => array(
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0'
            ),
            'form_params' => array(
                'agreementValue' => 'Agree'
            )
                )
        );
        $cookieJarPrev = $client->getConfig('cookies');
        $cookieJarNew = $cookieJarPrev->toArray();
        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $domain = 'www.frbservices.org';
        $values = ['abaDataCaptureCookie' => 'abaDataCaptureCookie', $cookieJarNew[0]['Name'] => $cookieJarNew[0]['Value']];

        $cookieJar = $jar->fromArray($values, $domain);
        try {
            $clientnew = new \GuzzleHttp\Client(['verify' => false, 'cookies' => true]);
            $response = $clientnew->post($frbServices, array(
                'headers' => array(
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0',
                ),
                'form_params' => array(
                    'agreementValue' => 'Agree',
                ),
                'cookies' => $cookieJar,
                    )
            );
            return $response;
        } catch (RequestException $e) {
            echo "catch block";
            dd();
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
    }

    /**
     * Function is to operations on live table - truncate and insert.
     *
     * @name OperationOnTable
     * @access private
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return boolean
     */
    private function OperationOnTable($fileNameCSV, $totalCount) {
//        $response='';
        if ($totalCount > 0) {
            $liveSuccess = BankDetails::TruncateTable();
//            if($liveSuccess)
//            {
            return BankDetails::InsertData($fileNameCSV);
//            }
//            else
//            {
//                return false;
//            }            
        } else {
            return BankDetails::InsertData($fileNameCSV);
        }
//        return $response;
    }

    /**
     * Function is calculate percentage of 2 no's.
     *
     * @name calculatePercentage
     * @access private
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return interger
     */
    private function calculatePercentage($allTempCount, $newCount) {
        return $percentage = ($allTempCount / $newCount) * 100;
    }

    /**
     * Function is to operate array and return array with specific format for dumping to text file.
     *
     * @name convertToArray
     * @access private
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return array
     */
    private function convertToArray($finalResult) {
        $operationArray = array();
        $string = '';
        $count = count($finalResult);
        for ($i = 0; $i < $count - 1; $i++) {
            $routingNumber = substr($finalResult[$i], 0, 9);
            $telegraphicName = substr($finalResult[$i], 9, 18);
            $customerName = substr($finalResult[$i], 27, 36);
            $state = substr($finalResult[$i], 63, 2);
            $city = substr($finalResult[$i], 65, 25);
            $funds = substr($finalResult[$i], 90, 1);
            $fundsSettlement = substr($finalResult[$i], 91, 1);
            $bookEntry = substr($finalResult[$i], 92, 1);
            $date = substr($finalResult[$i], 93, 8);
            if (!empty(trim($date))) {
                $year = substr($finalResult[$i], 93, 4);
                $month = substr($finalResult[$i], 97, 2);
                $day = substr($finalResult[$i], 99, 2);
                $dateRevision = $year . '-' . $month . '-' . $day;
            } else {
                $dateRevision = '';
            }
            $string = $routingNumber . '~' . $telegraphicName . '~' . $customerName . '~' . $state . '~' . $city . '~' . $funds . '~' . $fundsSettlement . '~' . $bookEntry . '~' . $dateRevision . "\n";
            $operationArray[] = $string;
        }
        return $operationArray;
    }

}
