<?php
require_once('Curl.php');
require_once('BaseApi.php');

class Token extends BaseApi {

    const END_POINT = '/tokens';
    private $curl;

    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @param $data
     * @return bool|string
     */
    public function create($data){

        return $this->curl->setBaseUrl(self::END_POINT)
            ->setMethod(self::POST)
            ->setData($data)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function retrieve($id){

        return $this->curl->setBaseUrl(self::END_POINT.'/'.$id)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }
}