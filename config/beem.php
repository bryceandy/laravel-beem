<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Beem API Key
    |--------------------------------------------------------------------------
    |
    | You can obtain this key after creating a Beem vendor account, then
    | visit the Profile tab and click on "Authentication Information"
    |
    */
    'api_key' => env('BEEM_KEY'),

    /*
   |--------------------------------------------------------------------------
   | Beem Secret Key
   |--------------------------------------------------------------------------
   |
   | You can obtain this key after creating a Beem vendor account, then visit
   | the Profile tab and click on "Authentication Information" then Generate
   |
   */
    'secret_key' => env('BEEM_SECRET'),

    /*
   |--------------------------------------------------------------------------
   | Beem Path
   |--------------------------------------------------------------------------
   |
   | This path name will be used as a prefix for all routes available
   |
   */
    'path' => env('BEEM_PATH', 'beem'),

];
