<?php
/**********************************************
 *         Application Environment            *
 **********************************************/

// Set your timezone (e.g., 'America/New_York', 'UTC')
// date_default_timezone_set( 'UTC' );
date_default_timezone_set( 'Europe/Minsk' );

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

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_loggers.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_smarty.php';

// Generate a CSP nonce for each request and store in $app
$nonce = bin2hex( random_bytes( 16 ) );
Flight::set( 'csp_nonce', $nonce );

/**********************************************
 *           User Configuration               *
 **********************************************/

Flight::set( 'LOG_REQUEST_TIME', true );
// Flight::set( 'SESSION_EXPIRE_TIMEOUT', 24 * 60 * 60 ); // seconds to expire session
Flight::set( 'SESSION_EXPIRE_TIMEOUT', 2 * 60 * 60 / 360 * 3 + 8 ); // seconds to expire session  - 2 часа

$myConfig = [
    /**************************************
     *         Database Settings          *
     **************************************/
    'database'  => [
                                           // MySQL Example:
        'host'             => 'MySql-8.4', // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'           => 'newDB',     // Database name (e.g., 'flightphp')
                                           // 'user'             => 'root',      // Database user (e.g., 'root')
        'user'             => 'antaNT64',  // Database user (e.g., 'root')
        'password'         => 'root',      // Database password (never commit real passwords)

                                                                                 // SQLite Example:
        'sqlite_file_path' => __APP__ . DIRECTORY_SEPARATOR . 'database.sqlite', // Path to SQLite file
    ],
    'database2' => [
                                    // MySQL Example:
        'host'     => 'MySql-8.4',  // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'   => 'nixby_UTF8', // Database name (e.g., 'flightphp')
                                    // 'user'             => 'root',      // Database user (e.g., 'root')
        'user'     => 'antaNT64',   // Database user (e.g., 'root')
        'password' => 'root',       // Database password (never commit real passwords)
    ],
    'database3' => [
                                             // MySQL Example:
        'host'     => '93.125.99.69',        // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'   => 'nixby_UTF8',          // Database name (e.g., 'flightphp')
                                             // 'user'             => 'root',      // Database user (e.g., 'root')
        'user'     => 'nixby_dbadmin',       // Database user (e.g., 'root')
        'password' => 'nixby_dbadmin658!!!', // Database password (never commit real passwords)
    ],

    // Google OAuth Credentials
    // 'google_oauth' => [
    //     'client_id'     => 'your_client_id',     // Google API client ID
    //     'client_secret' => 'your_client_secret', // Google API client secret
    //     'redirect_uri'  => 'your_redirect_uri',  // Redirect URI for OAuth callback
    // ],

    // Add more configuration sections below as needed
];

return $myConfig;
