<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('StripeInterface.php');

class Subscription extends BaseApi implements StripeInterface {

    /**
     * declared stripe subscriptions api end point
     */
    const END_PONT = '/subscriptions';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Subscription constructor.
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
     * @param null $referenceId
     * @return mixed
     */
    public function update($id, $referenceId=null){

        if(!is_string($id) ) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::PUT)
            ->setData(
                [
                    'metadata' => [
                        'order_id' => $referenceId
                    ]
                ]
            )
            ->sendDataToStripeApi();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){

        if(!is_string($id)) return self::INVALID_DATA;

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::DELETE)
            ->sendDataToStripeApi();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function all($limit = self::LIMIT){

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