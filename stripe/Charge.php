<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('Service.php');
require_once('StripeInterface.php');

/**
 * Class Charge
 */
class Charge extends BaseApi implements StripeInterface {

    private $curl;
    const END_PONT = '/charges';

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
            ->send();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function retrieve($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::GET)
            ->send();
    }

    /**
     * @param $chargeId
     * @param $orderId
     * @return bool|string
     */
    public function update($chargeId, $orderId){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$chargeId)
            ->setMethod(self::PUT)
            ->setData(
                [
                    'metadata' => [
                        'order_id' => $orderId
                    ]
                ]
            )
            ->send();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function capture($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id.'/capture')
            ->setMethod(self::POST)
            ->send();
    }

    /**
     * @return bool|string
     */
    public function all(){

        return $this->curl->setBaseUrl(self::END_PONT)
            ->setMethod(self::GET)
            ->send();
    }

}