<?php

require_once('LoadDotEnvFile.php');
require_once('BaseApi.php');

class Curl extends BaseApi {

    /**
     * declared env file
     */
    const ENV = '../.env';

    /**
     * declared log file path
     */
    const LOG_PATH = './logs';

    /**
     * declared log file prefix
     */
    const LOG_FILE_PREFIX = 'log';

    /**
     * @var
     * class variable that will hold the log file name
     */
    private $logFileName;

    /**
     * @var
     * class variable that will hold the curl request handler
     */
    private $handler;

    /**
     * @var
     * class variable that will hold the api base url
     */
    private $baseUrl;

    /**
     * @var
     * class variable that will hold the data inputs of our request
     */
    private $data;

    /**
     * @var
     * class variable that will tell us what type of request method to use (defaults to get)
     */
    private $requestMethod;

    /**
     * Curl constructor
     */
    public function __construct(){
        $this->checkCurlIsInstalledOrNot();
        (new LoadDotEnvFile($this->getEnvFilePath(self::ENV), []))->load();
        $this->initProperties();
        $this->setLogFileName();
    }

    /**
     * @param null $handler
     * @param string $baseUrl
     * @param array $data
     * @param string $requestMethod
     */
    public function initProperties($handler = null, $baseUrl = '', $data = [], $requestMethod = self::GET){
        $this->handler = $handler;
        $this->baseUrl = $baseUrl;
        $this->data = $data;
        $this->requestMethod = $requestMethod;
    }

    /**
     * Display curl installed or not install in the server
     */
    public function checkCurlIsInstalledOrNot()
    {
        if ($this->isCurlInstalled()) {
            echo "<br>cURL is <span style=\"color:blue\">installed</span> on this server<br>";
        } else {
            echo "<br>cURL is NOT <span style=\"color:red\">installed</span> on this server<br>";
        }
    }

    /**
     * set log file path and name
     */
    public function setLogFileName(){
        $this->logFileName = self::LOG_PATH.'/'.self::LOG_FILE_PREFIX.'_'.date("j.n.Y").'.log';
    }

    /**
     * @param $endPoint
     */
    public function setBaseUrl($endPoint){
        $this->baseUrl = self::STRIPE_END_POINT.'/'.self::STRIPE_API_VERSION.$endPoint;
    }

    /**
     * @param array $data
     */
    public function setData( $data = [] ){
        $this->data = $data;
    }

    /**
     * @param string $requestMethod
     */
    public function setMethod( $requestMethod = self::GET ){
        $this->requestMethod = $requestMethod;
    }

    /**
     * @param $requestMethod
     */
    public function handleRequestMethod($requestMethod){

        curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, $requestMethod);
        if ($this->data)
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
    }

    /**
     * set additional handler in curl
     */
    public function setAdditionalHandlerInCurl(){

            curl_setopt($this->handler, CURLOPT_URL, $this->baseUrl);
            curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->handler, CURLOPT_ENCODING, '');
            curl_setopt($this->handler, CURLOPT_MAXREDIRS, 10);
            curl_setopt($this->handler, CURLOPT_TIMEOUT, 0);
            curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->handler, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
            curl_setopt($this->handler, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $_ENV['STRIPE_SECRET_KEY'],
                'Content-Type: application/x-www-form-urlencoded',
            ));
    }

    /**
     * @return bool|string
     */
    public function sendDataToStripeApi(){
        try{

           $this->handler = curl_init( );
            switch($this->requestMethod){

                case self::POST:
                    curl_setopt($this->handler, CURLOPT_POST, count($this->data));
                    $this->handleRequestMethod($this->requestMethod);
                    break;
                case self::PUT || self::DELETE:
                    $this->handleRequestMethod($this->requestMethod);
                    break;
                default:
                    if ($this->data)
                        $this->baseUrl = sprintf("%s?%s", $this->baseUrl, http_build_query($this->data));
                    break;
            }

            // set additional handler
            $this->setAdditionalHandlerInCurl();

            $response = curl_exec($this->handler);
            $httpCode = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                $this->writeLog('Success', $httpCode, $response);
            } else {
                $this->writeLog('Error', $httpCode, $response);
            }

            curl_close($this->handler);
            $this->handler = null;

            return $response;

        }catch( Exception $e ){
            $this->writeLog('error', $e->getCode(), $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * @param $status
     * @param $httpCode
     * @param $response
     */
    public function writeLog($status, $httpCode, $response){

        //To write to txt log
        $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "Attempt: ".$status.PHP_EOL.
            "http Code: ".$httpCode.PHP_EOL.
            "User: Guest".PHP_EOL.
            "response: ".$response.PHP_EOL.
            "------------------------------------------".PHP_EOL;
        //Save string to log, use FILE_APPEND to append.

        file_put_contents($this->logFileName, $log, FILE_APPEND);
    }

    /**
     * Check cURL is installed or not
     */
    public  function isCurlInstalled() {

        if (in_array ('curl', get_loaded_extensions())) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $file
     * @return string
     */
    public function getEnvFilePath($file)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . $file;
    }

}