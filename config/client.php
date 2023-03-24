<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Company name
  |--------------------------------------------------------------------------
  |
  */

  'company' => env('CRA_COMPANY_NAME', 'Cristina Rutz Architekten'),

  /*
  |--------------------------------------------------------------------------
  | E-Mail settings
  |--------------------------------------------------------------------------
  |
  */

  'email' => [
    'from' => env('CRA_MAIL_FROM', 'marcel@jamon.digital'),
    'recipient' => env('CRA_MAIL_RECIPIENT', 'm@marceli.to'),
    'bcc' => env('CRA_MAIL_BCC', 'info@cristinarutz.ch'),
    'recipient_test' => env('CRA_MAIL_RECIPIENT_TEST', 'm@marceli.to')
  ],

  /*
  |--------------------------------------------------------------------------
  | Domain
  |--------------------------------------------------------------------------
  |
  */

  'domain' => env('CRA_DOMAIN', 'https://forum-architektur.ch'),

  /*
  |--------------------------------------------------------------------------
  | Chunk size for cron jobs
  |--------------------------------------------------------------------------
  |
  */

  'cron_chunk_size' => 3,
]
?>