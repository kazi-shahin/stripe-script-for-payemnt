<?php

require_once('StripeCharge.php');



$postdata =  array(
    "amount"        => '500', // required
    "currency"        => 'usd', // required
    "source"        => 'tok_visa',
    "description" =>  'alamin',
    "customer" =>  '',
//    "receipt_email" => '',
//    "application_fee_amount" => '',
//    "transfer_group" => '',
//    "capture" => '',

//    "metadata" => ["order_id" => ""],
//    "shipping" => [
//        "address" => '',
//        "name" => '',
//        "carrier" => '',
//        "phone" => '',
//        "tracking_number" => '',
//    ],
//    'transfer_data' => [
//        'amount' => '',
//        'destination' => '',
//    ],
//

);

$charge = new StripeCharge();


echo '<pre>';
//print_r();
print_r($charge->create($postdata));
//print_r($charge->retrieve('ch_1J3HSBGxRtOk5p73h2wS4HLU'));
//print_r($charge->delete('ch_1J3HSBGxRtOk5p73h2wS4HLU'));
echo '</pre>';


