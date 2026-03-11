<?php
/**********************************************
 *         Application Environment            *
 **********************************************/
// dd( __DIR__ );
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

/**********************************************
 *           User Configuration               *
 **********************************************/
// Generate a CSP nonce for each request and store in $app
$nonce = bin2hex( random_bytes( 16 ) );
Flight::set( 'csp_nonce', $nonce );
/**/
// Инициализируем Dotenv
// __DIR__ указывает, что файл .env лежит в той же папке, что и index.php
$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

Flight::set( 'LOG_REQUEST_TIME', true );
// Flight::set( 'SESSION_EXPIRE_TIMEOUT', 24 * 60 * 60 ); // seconds to expire session
Flight::set( 'SESSION_EXPIRE_TIMEOUT', 1 * 60 * 60 );    // seconds to expire session  -1 час
Flight::set( 'TOKEN_EXPIRE_TIMEOUT', 2 * 24 * 60 * 60 ); // seconds to expire session  -2 суток
// Flight::set( 'SESSION_EXPIRE_TIMEOUT', 20 );          // seconds to expire session  - 2 часа

Flight::set( 'jwt_key', $_ENV['JWT_SECRET'] );
// var_dump( $_ENV['JWT_SECRET'] );

$myConfig = [

    'runway'           => [
        'app_root'    => 'app/',
        'index_root'  => 'app/',
        'public_root' => 'public/',
        'index_path'  => 'public/index.php',
    ],

    /**************************************
     *         Database Settings          *
     **************************************/
    'sqlite_file_path' => __DIR__ . DIRECTORY_SEPARATOR . 'database.sqlite', // Path to SQLite file
    'database'         => [
                                        // MySQL Example:
        'host'     => $_ENV['DB_HOST'], // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'   => $_ENV['DB_NAME'], // Database name (e.g., 'flightphp')
                                        // 'user'             => 'root',      // Database user (e.g., 'root')
        'user'     => $_ENV['DB_USER'], // Database user (e.g., 'root')
        'password' => $_ENV['DB_PASS'], // Database password (never commit real passwords)

        // SQLite Example:
    ],
    'database2'        => [
                                        // MySQL Example:
        'host'     => $_ENV['DB_HOST'], // Database host (e.g., 'localhost', 'db.example.com')
        'dbname'   => 'nixby_UTF8',     // Database name (e.g., 'flightphp')
                                        // 'user'             => 'root',      // Database user (e.g., 'root')
        'user'     => $_ENV['DB_USER'], // Database user (e.g., 'root')
        'password' => $_ENV['DB_PASS'], // Database password (never commit real passwords)
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
