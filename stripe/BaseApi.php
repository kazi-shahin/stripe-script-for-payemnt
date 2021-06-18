<?php

require_once('Curl.php');

class BaseApi {
    const STRIPE_END_POINT = 'https://api.stripe.com';
    const STRIPE_API_VERSION = 'v1';
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';


    public function __construct()
    {
    }


}