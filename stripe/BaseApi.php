<?php

require_once('Curl.php');

abstract class BaseApi {

    /**
     * declared stripe api end point
     */
    const STRIPE_END_POINT = 'https://api.stripe.com';

    /**
     * declared stripe api version
     */
    const STRIPE_API_VERSION = 'v1';

    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const LIMIT = 3;


    const INVALID_DATA = "Input invalid data";

}