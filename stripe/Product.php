<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('StripeInterface.php');

class Product extends BaseApi implements StripeInterface {

    const END_POINT = '/products';
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

    /**
     * @param $id
     * @param $data
     * @return bool|string
     */
    public function update($id, $data){

        return $this->curl->setBaseUrl(self::END_POINT.'/'.$id)
            ->setMethod(self::PUT)
            ->setData($data)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function delete($id){

        return $this->curl->setBaseUrl(self::END_POINT.'/'.$id)
            ->setMethod(self::DELETE)
            ->sendDataToStripeApi();
    }

    /**
     * @return bool|string
     */
    public function all(){

        return $this->curl->setBaseUrl(self::END_POINT)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }

}