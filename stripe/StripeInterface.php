<?php


interface StripeInterface
{
    public function create($data);
    public function retrieve($id);
    public function update($chargeId, $referenceId);
    public function all();
}