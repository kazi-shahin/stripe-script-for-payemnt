
## Requirements
- PHP 7.2 and above.
- Built-in libcurl support.

## Installation
For running this example, you need to clone the repo

### Manually
```
git clone git@github.com:kazi-shahin/stripe-script-for-payemnt.git
```

```
php -S 127.0.0.1:8000
```

Please see configuration section below for configuring your Stripe Keys.

## Configuration
After you clone the repo, you need to **configure** your Stripe Keys.  
So, we have 1 files that you need to change:
- `.env`

### .env

On line 1 in this file, you will see something like below.  
You need to configure both your `STRIPE_SECRET_KEY`  in this file. The secret key should be kept safe, it is used to make a full charge from a token or to create a permanent customer to charge later, also every other permanent action on your account.  
```php
/* Defined Stripe Secret Key */
1: STRIPE_SECRET_KEY=sk_test
```

## Folder Structure
In this example, we have some files and folder that you need to concentrate about, as follows:
- `stripe/*`
- `example.php`
- `.env`
- `logs/*`

### Covered Features

- `Charge`
- `Customer`
- `Payment Method`
- `Plan`
- `Product`
- `Subscriptions`
- `Token`

#### As a Developer  How could I make payment to stripe?
- Step 1: Clone the repo
- Step 2: Update .env file with stripe secret key
- Step 3: Generate token of your payment method, Calling `create` method of `Token` class (filename Token.php) with the object
```
[
    'card' => [
        'number' => '4242424242424242',
        'exp_month' => 6,
        'exp_year' => 2022,
        'cvc' => '314',
    ],
]
``` 

- Step 4: Make payment by calling `create` method of `Charge` class (filename Charge.php) with the object
```
[
    "amount"        => '500',  // required, Amount always multiply by 100
    "currency"        => 'usd', // required
    "source"        => 'tok_visa', // User token id of generated token by card information
    "description" =>  '',
    "customer" =>  '',
    "receipt_email" => '',
    "application_fee_amount" => '',
    "transfer_group" => '',
    "capture" => '',

    "metadata" => ["order_id" => ""],
    "shipping" => [
        "address" => '',
        "name" => '',
        "carrier" => '',
        "phone" => '',
        "tracking_number" => '',
    ],
    'transfer_data' => [
        'amount' => '',
        'destination' => '',
    ],
]
```


## Tips
You are not allowed to send the card data to your servers.

