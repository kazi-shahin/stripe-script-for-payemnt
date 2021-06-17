<?php

require_once('Charge.php');
require_once('Token.php');
require_once('PaymentMethod.php');


$cardToken = array(
    'card' => [
        'number' => '4242424242424242',
        'exp_month' => 6,
        'exp_year' => 2022,
        'cvc' => '314',
    ],
);

$bankToken = array(
    'bank_account' => [
        'country' => 'US',
        'currency' => 'aud',
        'account_holder_name' => 'Jenny Rosen',
        'account_holder_type' => 'individual',
        'routing_number' => '110000000',
        'account_number' => '000123456789',
    ],
);

$piiToken = array(
    'pii' => ['id_number' => '000000000'],
);

$accountToken = array(
    'account' => [
        'individual' => [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ],
        'tos_shown_and_accepted' => true,
    ],
);


$personToken = array(
    'person' => [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'relationship' => ['owner' => true],
    ],
);

$cvcUpdateToken = array(
    'cvc_update' => ['cvc' => '123'],
);


$token = new Token();
echo '<pre>';
print_r($token->createCard($cardToken));
echo '</pre>';


$paymentMethodCreate = array(
    'type' => 'card',
    'card' => [
        'number' => '4242424242424242',
        'exp_month' => 6,
        'exp_year' => 2022,
        'cvc' => '314',
    ],
);

$paymentMethod = new PaymentMethod();
echo '<pre>';
print_r($paymentMethod->create($paymentMethod));
print_r($paymentMethod->retrieve('pm_1EUmzw2xToAoV8choYUtciXR'));
print_r($paymentMethod->update('pm_1EUmzw2xToAoV8choYUtciXR',['metadata' => ['order_id' => '6735']]));
print_r($paymentMethod->all(['customer' => 'cus_JgEB0xk16nTGMV','type' => 'card']));
print_r($paymentMethod->attach('pm_1EUmzw2xToAoV8choYUtciXR',['customer' => 'cus_JgEB0xk16nTGMV']));
echo '</pre>';

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

$charge = new Charge();


echo '<pre>';
//print_r();
//print_r($charge->create($postdata));
//print_r($charge->retrieve('ch_1J3HSBGxRtOk5p73h2wS4HLU'));
//print_r($charge->delete('ch_1J3HSBGxRtOk5p73h2wS4HLU'));
echo '</pre>';


