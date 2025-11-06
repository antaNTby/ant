<?php
/**********************************************
 *         Application Environment            *
 **********************************************/

// Set your timezone (e.g., 'America/New_York', 'UTC')
date_default_timezone_set( 'UTC' );

// Error reporting level (E_ALL recommended for development)
error_reporting( E_ALL );

// Character encoding
if ( function_exists( 'mb_internal_encoding' ) === true ) {
    mb_internal_encoding( 'UTF-8' );
}

// Default Locale Change as needed or feel free to remove.
if ( function_exists( 'setlocale' ) === true ) {
    setlocale( LC_ALL, 'en_US.UTF-8' );
}

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_flight.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_smarty.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_monolog.php';

// Generate a CSP nonce for each request and store in $app
$nonce = bin2hex( random_bytes( 16 ) );
$app->set( 'csp_nonce', $nonce );

/**********************************************
 *           User Configuration               *
 **********************************************/

$myConfig = [
    /**************************************
     *         Database Settings          *
     **************************************/
    'database' => [
                                           // MySQL Example:
        'host'             => 'MySql-8.4', // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'           => 'newDB',     // Database name (e.g., 'flightphp')
        'dbnameOld'        => 'oldDB',     // Database name (e.g., 'flightphp')
        'user'             => 'root',      // Database user (e.g., 'root')
        'password'         => '',          // Database password (never commit real passwords)

                                                                                 // SQLite Example:
        'sqlite_file_path' => __APP__ . DIRECTORY_SEPARATOR . 'database.sqlite', // Path to SQLite file
    ],

    // Google OAuth Credentials
    // 'google_oauth' => [
    //     'client_id'     => 'your_client_id',     // Google API client ID
    //     'client_secret' => 'your_client_secret', // Google API client secret
    //     'redirect_uri'  => 'your_redirect_uri',  // Redirect URI for OAuth callback
    // ],

    // Add more configuration sections below as needed
];

debug( $myConfig );

return $myConfig;
