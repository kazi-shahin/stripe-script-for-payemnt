<?php
require_once('Curl.php');
require_once('Base.php');

class PaymentMethod extends Base {

    const END_POINT = '/payment_methods';

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
    public function delete($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id)
            ->setMethod(self::DELETE)
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

    /**
     * @param $id
     * @param $data
     * @return bool|string
     */
    public function attach($id, $data){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id.'/attach')
            ->setMethod(self::POST)
            ->setData($data)
            ->send();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function detach($id){

        return $this->curl->setBaseUrl(self::END_PONT.'/'.$id.'/detach')
            ->setMethod(self::POST)
            ->setData([])
            ->send();
    }
}