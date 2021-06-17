<?php
require_once('Curl.php');

class StripeSubscription {

    private $endPoint = '/subscriptions';
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
    public function delete($id){

        return $this->curl->setBaseUrl($this->endPoint.'/'.$id)
            ->setMethod('delete')
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