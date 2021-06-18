<?php


interface StripeInterface
{
    public function create();
    public function retrieve();
    public function update();
    public function all();

    #public function delete()
    #public function capture()

}