<?php
require_once('Curl.php');
require_once('BaseApi.php');

class Token extends BaseApi {

    /**
     * declared stripe tokens api end point
     */
    const END_POINT = '/tokens';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Token constructor.
     */
    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){

        return $this->curl->setBaseUrl(self::END_POINT)
            ->setMethod(self::POST)
            ->setData($data)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function retrieve($id){

        return $this->curl->setBaseUrl(self::END_POINT.'/'.$id)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }
}