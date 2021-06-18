<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('StripeInterface.php');

/**
 * Class Charge
 */
class Charge extends BaseApi implements StripeInterface {

    /**
     * declared stripe charge api end point
     */
    const END_PONT = '/charges';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Charge constructor.
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

       if(!is_array($data)) return self::INVALID_DATA;

       return $this->curl->setBaseUrl(self::END_PONT)
            ->setMethod(self::POST)
            ->setData($data)
            ->sendDataToStripeApi();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function retrieve($id){

        if(!is_string($id)) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::GET)
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @param $orderId
     * @return mixed
     */
    public function update($id, $orderId){

        if(!is_string($id) || !is_string($orderId)) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::PUT)
            ->setData(
                [
                    'metadata' => [
                        'order_id' => $orderId
                    ]
                ]
            )
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function capture($id){

        if(!is_string($id)) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id.'/capture')
            ->setMethod(self::POST)
            ->sendDataToStripeApi();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function all($limit = 3){

        if(!is_int($limit)) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT)
            ->setMethod(self::GET)
            ->setData(
                [
                    'limit' => $limit
                ]
            )
            ->sendDataToStripeApi();
    }

}