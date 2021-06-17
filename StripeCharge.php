<?php
require_once('Curl.php');

/**
 * Class Charge
 */
class StripeCharge {

    private $curl;
    private $endPoint = '/charges';
    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @param $data
     * @return bool|string
     */
    public function create($data){

       return $this->curl->setBaseUrl($this->endPoint)
            ->setMethod('post')
            ->setData($data)
            ->send();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function retrieve($id){

        return $this->curl->setBaseUrl($this->endPoint.'/'.$id)
            ->setMethod('get')
            ->send();
    }

    /**
     * @param $id
     * @param $data
     * @return bool|string
     */
    public function update($id, $data){

        return $this->curl->setBaseUrl($this->endPoint.'/'.$id)
            ->setMethod('put')
            ->setData($data)
            ->send();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function capture($id){

        return $this->curl->setBaseUrl($this->endPoint.'/'.$id.'/capture')
            ->setMethod('post')
            ->send();
    }

    /**
     * @return bool|string
     */
    public function all(){

        return $this->curl->setBaseUrl($this->endPoint)
            ->setMethod('get')
            ->send();
    }

}