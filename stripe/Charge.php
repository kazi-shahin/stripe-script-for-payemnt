<?php
require_once('Curl.php');
require_once('BaseApi.php');
require_once('Service.php');

/**
 * Class Charge
 */
class Charge extends BaseApi {

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
     * @param $id
     * @param $data
     * @return bool|string
     */
    public function update($id, $data){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::PUT)
            ->setData($data)
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