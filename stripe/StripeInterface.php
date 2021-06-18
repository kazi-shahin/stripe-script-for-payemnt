<?php


interface StripeInterface
{
    public function create($data);
    public function retrieve($id);
    public function update($chargeId, $orderId);
    public function all();

    #public function delete()
    #public function capture()

}