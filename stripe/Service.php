<?php

class Service{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function validation()
    {
        return $this->data;
    }
}