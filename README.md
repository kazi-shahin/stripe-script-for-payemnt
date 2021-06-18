
## Requirements
- PHP 7.2 and above.
- Built-in libcurl support.

## Installation
For running this example, you need to clone the repo

### Manually
```
git clone https://github.com/omise/omise-php
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

## Tips
You are not allowed to send the card data to your servers.

