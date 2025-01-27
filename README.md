# laravel-plugin 
![alt text](https://epayment.kz/images/epay.png)   
[Epay](https://epayment.kz) payment gateway class for Laravel framework.

## Installation

```bash
composer require hfsepayment/laravel-plugin
```

## Usage

```php
<?php

use HFSEpayment\Epayment;

$pay = new Epayment();

$payment = [
   'env'            => "test",
   'client_id'      => "test",
   'client_secret'  => "yF587AV9Ms94qN2QShFzVR3vFnWkhjbAK3sG",
   'secret_hash'    => 'qwerty12345',
   'terminal'       => "67e34d63-102f-4bd1-898e-370781d0074d",
   'invoiceId'      => "300022002",
   'amount'         => 10,
   'currency'       => "KZT",
   'backLink'       => "https://example.kz/success.html",
   'failureBackLink' => "https://example.kz/failure.html",
   'postLink'       => "https://example.kz/",
   'failurePostLink' => "https://example.kz/order/1123/fail",
   'language'       => "rus",
   'telephone'      => "",
   'email'          => "",
   // ...additional props
];
    
$pay->gateway($payment);
```

### Payment properties
For more information visit the official [docs](https://epayment.kz/docs)

| Property                   | Required | Default | Description                                      |
|----------------------------|:--------:|:-------:|--------------------------------------------------|
| `env`                      |   yes    |         | Either `test` or `production`               |
| `client_id`                |   yes    |         |  |
| `client_secret`            |   yes    |         |  |
| `secret_hash`              |   yes    |         | 
| `terminal`                 |   yes    |         |
| `invoiceId`                |   yes    |         |
| `amount`                   |   yes    |         |
| `backLink`                 |   yes    |         |
| `failureBackLink`          |   yes    |         |
| `postLink`                 |   yes    |         |
| `failurePostLink`          |   yes    |         |
| `currency`                 |    no    | `"KZT"` |
| `language`                 |    no    | `"rus"` |
| `description`              |    no    |  `""`   |
| `accountId`                |    no    |  `""`   |
| `telephone`                |    no    |  `""`   |
| `email`                    |    no    |  `""`   |
| More additional properties |    no    | `null`  | If you need to send any property you can add it as a property of array

## License

[MIT](https://choosealicense.com/licenses/mit/)