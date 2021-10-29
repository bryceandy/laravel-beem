<p align="center"><img src="https://beem.africa/wp-content/uploads/2020/12/Beem-menu-logo-02.svg" width="400"></p>

# Beem package for Laravel apps

[![Actions Status](https://github.com/bryceandy/laravel-beem/workflows/Tests/badge.svg)](https://github.com/bryceandy/laravel-beem/actions)
<a href="https://packagist.org/packages/bryceandy/laravel-beem"><img src="https://poser.pugx.org/bryceandy/laravel-beem/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/bryceandy/laravel-beem"><img src="https://poser.pugx.org/bryceandy/laravel-beem/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/bryceandy/laravel-beem"><img src="https://poser.pugx.org/bryceandy/laravel-beem/license.svg" alt="License"></a>

This package enables Laravel developers to integrate their websites/APIs with all Beem API services

**Table of Contents**

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    1. [SMS](#1-sms)
        - [SMS Delivery Reports](#sms-delivery-reports)
        - [Collecting the delivery data](#collecting-the-delivery-data)
        - [SMS Templates](#sms-templates)
        - [Two-Way SMS](#two-way-sms)
            - [Collecting the outbound SMS data](#collecting-the-outbound-sms-data)
    2. [Contacts](#2-contacts)
    3. [USSD](#3-ussd)
        - [Collecting callback data](#collecting-ussd-callback-data)
        - [Checking the balance](#checking-the-ussd-balance)
    4. [Airtime](#4-airtime)
        - [Airtime callback success](#airtime-callback-success)
        - [Collecting callback data](#collecting-airtime-callback-data)
        - [Querying airtime transaction status](#querying-airtime-transaction-status)
        - [Checking the balance](#checking-the-airtime-balance)
    5. [Payment Collection](#5-payment-collection)
        - [Collecting callback data](#collecting-payment-collection-callback-data)
        - [Checking the balance](#checking-the-payment-collection-balance)
    6. [Payment Checkout](#6-payment-checkout)
        - [Payment checkout callback](#payment-checkout-callback)
        - [Collecting callback data](#collecting-payment-checkout-callback-data)
    7. [Disbursements](#7-disbursements)
    8. [OTP](#8-otp)
- [Debugging Tips](#debugging-tips)
- [License](#license)
- [Contributing](#contributing)

## Installation

Pre-installation requirements

 * Supports Laravel projects starting version 8.*
 * Minimum PHP version is 7.4
 * Your server must have the cURL PHP extension (ext-curl) installed

Then proceed to install

```bash
composer require bryceandy/laravel-beem
```

## Configuration

To access Beem's APIs, you will need to provide the package with access to your Beem API Key and Secret Key.
 
For this we need to publish the package's configuration file using:

```bash
php artisan vendor:publish --tag=beem-config
```

After you have created a Beem vendor account and obtained the keys on the dashboard, add their values in the `.env` variables

```dotenv
BEEM_KEY=yourApiKey
BEEM_SECRET=yourSecretKey
```

## Usage

### 1. SMS

To send an SMS message, use the Beem facade and pass the message and recipients as arguments

```php
use Bryceandy\Beem\Facades\Beem;

$recipients = [
    [
        'recipient_id' => (string) 1,
        'dest_addr' => (string) 255784000000,
    ],
    [
        'recipient_id' => (string) 2,
        'dest_addr' => (string) 255754000000,
    ],
];
    
Beem::sms('This is the message', $recipients);
```

Optionally, you can include your custom sender name only if the request has been accepted on your vendor dashboard.

The default sender name is 'INFO'.

```php
Beem::sms('Another message', $recipients, 'SENDER-NAME');
```

For scheduled SMS, you can use a datetime value or `Carbon\Carbon` instance, but make sure the timezone is GMT+0

```php
$time = now()->addHours(10);

Beem::smsWithSchedule('Reminder message', $recipients, $time, 'SENDER-NAME');
```

You can also check the remaining SMS balance using

```php
Beem::smsBalance()->json();
```

#### SMS Delivery Reports

If you have specified a callback URL in the account profile, you can use that route in any way. 

Optionally, this package comes with:

 * A customizable callback route `/beem/sms-delivery-report`. The prefix can be changed using a `.env` value for `BEEM_PATH`.
   Once it is changed, the route becomes `/{env-value}/sms-delivery-report` (remember to update this callback on the vendor dashboard).


 * An event `Bryceandy\Beem\Events\SmsDeliveryReportReceived` to collect Beem's data once they are sent to the callback. 
   

##### Collecting the delivery data

To use the event above, assign a listener in the `App\Providers\EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\SmsDeliveryReportReceived::class => [
            \App\Listeners\ProcessDeliveryReport::class,
        ],
    ];
}
```

After you create the listener class, you can use Beem's delivery report

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\SmsDeliveryReportReceived;

class ProcessDeliveryReport
{
    /**
     * Handle the event.
     *
     * @param  SmsDeliveryReportReceived $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(SmsDeliveryReportReceived $event)
    {
        $requestId = $event->request['request_id'];
        $recipientId = $event->request['recipient_id'];
        $mobileNumber = $event->request['dest_addr'];
        $status = $event->request['status'];
        //...
        
        // After processing this report, send back an OK response to Beem
        return response()->json([]);
    }
}
```

Fetching SMS sender names on your vendor account

```php 
Beem::smsSenderNames()->json();
```

New sender names can be requested through the API as well. Follow Beem guidelines on sender name formats

```php 
$name = 'BRYCEANDY';
$sampleMessage = 'A sample message';

Beem::requestSmsSenderName($name, $sampleMessage);
```

#### SMS templates

The following can be used to view all SMS templates for the vendor

```php
Beem::smsTemplates()->json();
```

New SMS templates can also be added:

```php 
$message = 'Have a nice day!';
$smsTitle = 'Greetings';

Beem::addSmsTemplate($message, $smsTitle);
```

SMS templates can be edited or deleted if you have their `template_id`

```php 
// Template IDs can be obtained from the call above - Beem::smsTemplates()->json()

Beem::editSmsTemplate($templateId, $message, $smsTitle);

Beem::deleteSmsTemplate($templateId);
```

#### Two-Way SMS

After requesting a number on the SMS dashboard, you will have to edit it to assign a callback URL.

You can use the given route in any way, but this package comes with:

 * A customizable callback route `/beem/outbound-sms`. The prefix can be changed using a `.env` value for `BEEM_PATH`.
 Once it is changed, the route becomes `/{env-value}/outbound-sms`.
   
 
 * An event `Bryceandy\Beem\Events\TwoWaySmsCallbackReceived` to collect Beem's data once they are sent to the callback.


##### Collecting the outbound sms data

Assign an event listener for the event above in the `EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\TwoWaySmsCallbackReceived::class => [
            \App\Listeners\ProcessOutboundSms::class,
        ],
    ];
}
```

Then after creating the listener, you can collect the sms data and send a reply

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\TwoWaySmsCallbackReceived;
use Bryceandy\Beem\Facades\Beem;

class ProcessOutboundSms
{
    /**
     * Handle the event.
     *
     * @param  TwoWaySmsCallbackReceived $event
     * @return void
     */
    public function handle(TwoWaySmsCallbackReceived $event)
    {
        $from = $event->request['from'];
        $to = $event->request['to'];
        $text = $event->request['message']['text'];
        //...
        
        // After processing the received text, send a reply in your preferred way
        $recipients = [
            [
                'recipient_id' => (string) 1,
                'dest_addr' => $from
            ],
        ];

        Beem::sms('Your order is being processed!', $recipients, $to);
    }
}
```

### 2. Contacts

List all address books

```php 
use Bryceandy\Beem\Facades\Beem;

Beem::addressBooks()->json();
Beem::addressBooks($name)->json(); // Search by address book name
```

Use the following to add a new address book

```php 
Beem::addAddressBook($name, $description);
```

Address books can be edited or deleted if you have their `addressbook_id`

```php 
// Obtain the address book IDs from - Beem::addressBooks();

Beem::editAddressBook($addressBookId, $name, $description);

Beem::deleteAddressBook($addressBookId);
```

List contacts of a specific address book. Optionally filter by first name, last name or mobile number

```php 
// $q values are either 'fname', 'lname', or 'mob_no'
Beem::contacts($addressBookId, $q)->json();
```

Adding a new contact to an address book requires a mobile number and, an array of address book IDs.
Other fields are optional;

```php 
$addressBookIds = ['abcd123', '456efg'];
$mobileNumber = '255784123456';

Beem::addContact($addressBookIds, $mobileNumber);
Beem::addContact($addressBookIds, $mobileNumber, $firstName, $lastName, $title, $gender, $mobileNumber2, $email, $country, $city, $area, $birthDate);

// $title can be Mr./Mrs./Ms.
// $gender can be male, female
// $birthDate can be a datetime value or Carbon instance
```

Contacts can be edited by the `contact_id`. Other required fields are address book IDs and the mobile number.

```php
// Contact IDs can be obtained from the previous method - Beem::contacts()

Beem::editContact($contactId, $addressBookIds, $mobileNumber, $firstName);
Beem::editContact($contactId, $addressBookIds, $mobileNumber, $firstName, $lastName, $title, $gender, $mobileNumber2, $email, $country, $city, $area, $birthDate);
```

Lastly, a contact can be deleted by specifying the `contact_id` and their `addressbook_id`

```php 
$contactId = ['4sgb9ddfgb'];
$addressBookIds = ['abcdefg', '123456'];

Beem::deleteContact($addressBookIds, $contactId);
```

### 3. USSD

In a USSD app, Beem will send data to the callback URL you have specified in the USSD dashboard.

This package comes with an optional implementation that provides:

 * A customizable route `/beem/ussd-callback` that defines the callback url. 
   If you add a value for `BEEM_PATH` in the `.env` file, the path will now be `/{env-value}/ussd-callback`.


 * An event `Bryceandy\Beem\Events\UssdCallbackReceived` which can be used to process the data from beem


#### Collecting USSD callback data

Assign an event listener for the event above in the `EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\UssdCallbackReceived::class => [
            \App\Listeners\ProcessUssdCallback::class,
        ],
    ];
}
```

Then in the listener class `ProcessUssdCallback`, you can collect the data for processing

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\UssdCallbackReceived;

class ProcessUssdCallback
{
    /**
     * Handle the event.
     *
     * @param  UssdCallbackReceived $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(UssdCallbackReceived $event)
    {
        $command = $event->request['command'];
        $msisdn = $event->request['msisdn'];
        $session_id = $event->request['session_id'];
        $operator = $event->request['operator'];
        $payload = $event->request['payload'];
        
        // After processing this data, send your custom response to Beem
        
        $sampleResponse = [
            'msisdn' => '2556730893370',
            'operator' => 'vodacom',
            'session_id' => '14545',
            'command' => 'initiate',
            'payload' => [
                'request_id' => 0,
                'request' => 'enter phone number',
            ],
        ];
        
        return response()->json($sampleResponse);
    }
}
```

#### Checking the USSD balance

```php 
use Bryceandy\Beem\Facades\Beem;

Beem::ussdBalance()->json();
```

### 4. Airtime

Start by sending airtime when you have sufficient funds on the airtime dashboard

```php 
use Bryceandy\Beem\Facades\Beem;

$referenceId = 123456;

Beem::airtimeRecharge('255789123456', '1000.00', $referenceId);
```

#### Airtime callback success

If you have defined a callback URL in the airtime dashboard profile, Beem will send data once the transaction is completed.

Optionally, you can use a callback implementation of this package which provides:

 * A callback route `/beem/airtime-callback`, that can be customized by adding a `BEEM_PATH` value in the `env` file.
 Once you set this variable, your callback route becomes `/{env-value}/airtime-callback` (remember to change this on the profile).
   

 * An event `Bryceandy\Beem\Events\AirtimeCallbackReceived` that can be used to listen to all callbacks.

##### Collecting airtime callback data

Use the event above and assign a new listener in the `App\Providers\EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\AirtimeCallbackReceived::class => [
            \App\Listeners\ProcessAirtimeCallback::class,
        ],
    ];
}
```

Then after creating the `App\Listeners\ProcessAirtimeCallback` class, fetch the callback data

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\AirtimeCallbackReceived;

class ProcessAirtimeCallback
{
    /**
     * Handle the event.
     *
     * @param  AirtimeCallbackReceived $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(AirtimeCallbackReceived $event)
    {
        $code = $event->request['code'];
        $message = $event->request['message'];
        //...
        
        // After processing this data, send an OK response to Beem
        return response()->json([]);
    }
}
```

#### Querying airtime transaction status

Check the status of a recharge request. Use the `transaction_id` obtained from the `->json()` response of the request.

```php 
//$request = Beem::airtimeRecharge('255789123456', '1000.00', $referenceId)->json();

Beem::airtimeTransaction($request['transaction_id'])->json()
```

#### Checking the airtime balance

```php 
use Bryceandy\Beem\Facades\Beem;

Beem::airtimeBalance()->json();
```

### 5. Payment Collection

This package comes with another callback implementation for the payment collection. Available to you is:

 * The payment collection event `Bryceandy\Beem\Events\PaymentCollectionReceived`, which allows you to handle Bpay payments instantly.
 

 * A customizable callback route `/beem/payment-collection`. If you decide to use this callback implementation, remember to update the callback URL on dashboard the product.

#### Collecting payment collection callback data

Using the event above, create a listener to handle the callback and assign them in the `App\Providers\EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\PaymentCollectionReceived::class => [
            \App\Listeners\ProcessPaymentCollection::class,
        ],
    ];
}
```

Then after creating the listener class `App\Listeners\ProcessPaymentCollection`, fetch the data you need

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\PaymentCollectionReceived;

class ProcessPaymentCollection
{
    /**
     * Handle the event.
     *
     * @param  PaymentCollectionReceived $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(PaymentCollectionReceived $event)
    {
        $transactionId = $event->request['transaction_id'];
        $amount = $event->request['amount_collected'];
        $subscriber = $event->request['subscriber_msisdn'];
        $network = $event->request['network_name'];
        $referenceNumber = $event->request['reference_number'];
        //...
        
        // After processing this data, send a response to Bpay 
        return response()->json([
            'transaction_id' => $transactionId,
            'successful' => true,
        ]);
    }
}
```

#### Checking the payment collection balance

```php 
use Bryceandy\Beem\Facades\Beem;

Beem::paymentCollectionBalance()->json();
```

### 6. Payment Checkout

The package provides an eloquent functionality to handle Beem's payment checkout by redirection.

Collect the required data and use the redirect facade anywhere in your controller or classes;

```php
use Bryceandy\Beem\Facades\BeemRedirect;

$amount = '2000';
$transactionId = '96f9cc09-afa0-40cf-928a-d7e2b27b2408';
$referenceNumber = 'SAMPLE-12345';

return BeemRedirect::checkout($amount, $transactionId, $referenceNumber);
// Or include the mobile number
// BeemRedirect::checkout($amount, $transactionId,$referenceNumber, '255798333444');
// Tip: always use a return statement, in order to send the user to the payment page
```

#### Payment checkout callback

This package comes with another callback implementation for the payment checkout. Available to you is:

* The payment checkout event `Bryceandy\Beem\Events\PaymentCheckoutCallbackReceived`, which fires when payment checkout callbacks hit your callback URL.


* A customizable callback route `/beem/payment-checkout`. If you decide to use this callback implementation, remember to update the callback URL on dashboard the product.

#### Collecting payment checkout callback data

Using the event above, create a listener and assign it in the `App\Providers\EventServiceProvider`

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Bryceandy\Beem\Events\PaymentCheckoutCallbackReceived::class => [
            \App\Listeners\ProcessPaymentCheckout::class,
        ],
    ];
}
```

Then after creating the listener class `App\Listeners\ProcessPaymentCheckout`, fetch the data you need

```php
<?php

namespace App\Listeners;

use Bryceandy\Beem\Events\PaymentCheckoutCallbackReceived;

class ProcessPaymentCheckout
{
    /**
     * Handle the event.
     *
     * @param  PaymentCheckoutCallbackReceived $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(PaymentCheckoutCallbackReceived $event)
    {
        $transactionId = $event->request['transactionId'];
        $amount = $event->request['amount'];
        $referenceNumber = $event->request['referenceNumber'];
        $status = $event->request['status'];
        //...
        
        // After processing this data, send a response to Bpay as follows
        $statusMessage = 'This payment has been completed';

        return response()->json(
            compact('amount', 'status', 'referenceNumber', 'statusMessage', 'transactionId')
        );
    }
}
```

### 7. Disbursements

To send payments by disbursements, use the facade with appropriate arguments

```php
use Bryceandy\Beem\Facades\Beem;

Beem::disburse($amount, $clientReferenceId, $accountNumber, $walletNumber, $walletCode);

// Optionally, specify the currency, the default is TZS
Beem::disburse($amount, $clientReferenceId, $accountNumber, $walletNumber, $walletCode, $currency);
```

### 8. OTP

To request a pin, you require the user's number and, the app ID that you created on the OTP dashboard.

```php
use Bryceandy\Beem\Facades\Beem;

Beem::requestOtp($appId, $phoneNumber)->json();
```

To verify that the user sent the correct PIN, you will send a pinID from the response of the request and, the PIN the user sent.

```php
Beem::verifyOtp($pinId, $pin)->json();
```

## Debugging Tips

To debug the `Bryceandy\Beem\Facades\Beem` facade

 * Use the `->json()` method to fetch data of the response.


 * Use the boolean `->successful()` method to see if the request was successful

## License

MIT license

## Contributing

If you spot any bugs or want to add a feature, feel free to send a detailed PR with working tests to improve the project