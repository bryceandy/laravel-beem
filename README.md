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

SMS templates can be edited if you have their `template_id`

```php 
// Template IDs can be obtained from the call above - Beem::smsTemplates()->json()
Beem::editSmsTemplate($templateId, $message, $smsTitle)
```