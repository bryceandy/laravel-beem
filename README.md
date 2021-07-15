# Beem package for Laravel apps

This package enables Laravel developers to integrate their websites/APIs with all Beem API services

## Installation

Pre-installation requirements

 * Supports Laravel projects starting version 8.*
 * Minimum PHP version is 7.4
 * Your server must have the cURL PHP extension installed

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

### SMS

To send an SMS message, use the Beem facade and pass the message and recipients as arguments

```php
use Bryceandy\Beem\Facades\Beem;

$recipients = [
    [
        'recipient_id' => (string) 1,
        'dest_addr' => (string) 255784000000
    ],
    [
        'recipient_id' => (string) 2,
        'dest_addr' => (string) 255754000000
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
 Once it is changed, the route becomes `/{en-value}/outbound-sms`.
   
 
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

### Contacts

List all address books

```php 
use Bryceandy\Beem\Facades\Beem;

Beem::addressBooks();
Beem::addressBooks($name); // Search by address book name
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
Beem::contacts($addressBookId, $q);
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

### USSD

In a USSD app, Beem will send data to the callback URL you have specified in the USSD dashboard.

This package comes with an optional implementation that provides:

 * A customizable route `/beem/ussd-callback` that defines the callback url. 
   If you add a value for `BEEM_PATH` in the `.env` file, the path will now be `/{env-value}/ussd-callback`.


 * An event `Bryceandy\Beem\Events\UssdCallbackReceived` which can be used to process the data from beem


#### Collecting callback data

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
                'request' => "enter phone number"
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