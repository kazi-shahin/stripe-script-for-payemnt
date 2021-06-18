<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('StripeInterface.php');

class Subscription extends BaseApi implements StripeInterface {

    const END_PONT = '/subscriptions';

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

       return $this->curl->setBaseUrl(self::END_PONT)
            ->setMethod(self::POST)
            ->setData($data)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function retrieve($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @param $data
     * @return bool|string
     */
    public function update($id, $data){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::PUT)
            ->setData($data)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function delete($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::DELETE)
            ->sendDataToStripeApi();
    }

    /**
     * @return bool|string
     */
    public function all(){

        return $this->curl->setBaseUrl(self::END_PONT)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }
}