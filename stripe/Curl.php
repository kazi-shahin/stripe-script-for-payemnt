<?php

require_once('DotEnv.php');
require_once('Base.php');

class Curl extends Base {

    // class variable that will hold the curl request handler
    private $handler;

    private $baseUrl;

    // class variable that will hold the data inputs of our request
    private $data;

    // class variable that will tell us what type of request method to use (defaults to get)
    private $requestMethod;

    public function __construct()
    {
        $this->isCurlInstalled();

        (new DotEnv($this->getEnvFilePath('.env'), []))->load();
        $this->initProperties();
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
     * @param $endPoint
     * @return $this
     */
    public function setBaseUrl($endPoint){

        $this->baseUrl = self::STRIPE_END_POINT.'/'.self::STRIPE_API_VERSION.$endPoint;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData( $data = [] ){
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $requestMethod
     * @return $this
     */
    public function setMethod( $requestMethod = self::GET ){
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function send(){
        try{

            #if( $this->handler !== null ){
                $this->handler = curl_init( );
            #}

            switch($this->requestMethod){

                case self::POST:

                    curl_setopt($this->handler, CURLOPT_POST, count($this->data));
                    if ($this->data)
                        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
                    break;
                case self::PUT:
                    curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, self::PUT);
                    if ($this->data)
                        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
                    break;
                case self::DELETE:
                    curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, self::DELETE);
                    if ($this->data)
                        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
                    break;
                default:
                    if ($this->data)
                        $this->baseUrl = sprintf("%s?%s", $this->baseUrl, http_build_query($this->data));
                    break;

            }

            curl_setopt($this->handler, CURLOPT_URL, $this->baseUrl);
            curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->handler, CURLOPT_ENCODING, '');
            curl_setopt($this->handler, CURLOPT_MAXREDIRS, 10);
            curl_setopt($this->handler, CURLOPT_TIMEOUT, 0);
            curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->handler, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
            curl_setopt($this->handler, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$_ENV['STRIPE_SECRET_KEY'],
                'Content-Type: application/x-www-form-urlencoded',
            ));

            $response = curl_exec ( $this->handler );

            $httpCode = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

            if($httpCode == 200) {
                $this->writeLog('Success', $httpCode, $response);
            }else{
                $this->writeLog('Error',$httpCode, $response);
            }

            curl_close ( $this->handler );
            $this->handler = null;

            return $response;

        }catch( Exception $e ){
            $this->writeLog('error', $e->getCode(), $e->getMessage());
            die( $e->getMessage() );
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
        file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
    }

    /**
     * Check cURL is installed or not
     */
    public  function isCurlInstalled() {

        if  (in_array  ('curl', get_loaded_extensions())) {
            echo "<br>cURL is <span style=\"color:blue\">installed</span> on this server<br>";
        }
        else {
            echo "<br>cURL is NOT <span style=\"color:red\">installed</span> on this server<br>";
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