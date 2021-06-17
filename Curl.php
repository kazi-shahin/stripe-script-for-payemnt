<?php
require_once('Stripe.php');
require_once('DotEnv.php');
class Curl {

    // class variable that will hold the curl request handler
    private $handler = null;

    // class variable that will hold the url
    private $baseUrl = '';

    // class variable that will hold the data inputs of our request
    private $data = [];

    // class variable that will tell us what type of request method to use (defaults to get)
    private $requestMethod = 'get';

    public function __construct()
    {
        if ($this->isCurlInstalled()) {
            echo "<br>cURL is <span style=\"color:blue\">installed</span> on this server<br>";
        } else {
            echo "<br>cURL is NOT <span style=\"color:red\">installed</span> on this server<br>";
        }
        (new DotEnv($this->getEnvFilePath('.env'), [

        ]))->load();
    }


    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl( $baseUrl = '' ){

        $this->baseUrl = Stripe::setBaseUrl();
        $this->baseUrl .= $baseUrl;

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
    public function setMethod( $requestMethod = 'get' ){
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
            switch( strtolower( $this->requestMethod ) ){

                case 'post':
                    curl_setopt($this->handler, CURLOPT_POST, count($this->data));
                    if ($this->data)
                        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
                    break;
                case 'put':
                    curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($this->data)
                        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
                    break;
                case 'delete':
                    curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, "DELETE");
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
     * @return bool
     */
    public  function isCurlInstalled() {
        if  (in_array  ('curl', get_loaded_extensions())) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getEnvFilePath($file)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . $file;
    }

}