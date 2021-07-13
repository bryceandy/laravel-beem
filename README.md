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

After you have your Beem vendor account and obtained the keys on the dashboard, add their values in the `.env` variables

```dotenv
BEEM_KEY=yourApiKey
BEEM_SECRET=yourSecretKey
```