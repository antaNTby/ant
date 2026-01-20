<?php

use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use flight\debug\tracy\TracyExtensionLoader;
use flight\Engine;
use Tracy\Debugger;

/*********************************************
 *         FlightPHP Service Setup           *
 *********************************************
 * This file registers services and integrations
 * for your FlightPHP application. Edit as needed.
 *
 * @var array  $config  From config.php
 * @var Engine $app     FlightPHP app instance
 **********************************************/

/*********************************************
 *           Session Service Setup           *
 *********************************************
 * To enable sessions in FlightPHP, register the session service.
 * Docs: https://docs.flightphp.com/awesome-plugins/session
 *
 * Example:
 *   $app->register('session', \flight\Session::class, [
 *       [
 *           'prefix' 		=> 'flight_session_', 	  // Prefix for the session cookie
 *           'save_path'    => 'path/to/my/sessions', // Path to save session files
 *           // ...other options...
 *       ]
 *   ]);
 *
 * For advanced options, see the plugin documentation above.
 **********************************************/

$app->register( 'session', \flight\Session::class, [
    [
        'prefix'    => 'flight_session_',                          // Prefix for the session cookie
        'save_path' => __APP__ . DIRECTORY_SEPARATOR . 'sessions', // Path to save session files
                                                                   // ...other options...
    ],
] );

/*********************************************
 *           Tracy Debugger Setup            *
 *********************************************
 * Tracy is a powerful error handler and debugger for PHP.
 * Docs: https://tracy.nette.org/
 *
 * Key Tracy configuration options:
 *   - Debugger::enable([mode], [ip]);
 *       - mode: Debugger::Development or Debugger::Production
 *       - ip: restrict debug bar to specific IP(s)
 *   - Debugger::$logDirectory: where error logs are stored
 *   - Debugger::$strictMode: show all errors (true/E_ALL), or filter out deprecated notices
 *   - Debugger::$showBar: show/hide debug bar (auto-detected, can be forced)
 *   - Debugger::$maxLen: max length of dumped variables
 *   - Debugger::$maxDepth: max depth of dumped structures
 *   - Debugger::$editor: configure clickable file links (see docs)
 *   - Debugger::$email: send error notifications to email
 *
 * Example Tracy setups:
 *   Debugger::enable(); // Auto-detects environment
 *   Debugger::enable(Debugger::Development); // Explicitly set environment
 *   Debugger::enable('23.75.345.200'); // Restrict debug bar to specific IPs
 *
 * For more options, see https://tracy.nette.org/en/configuration
 **********************************************/
Debugger::enable(); // Auto-detects environment
// Debugger::enable(Debugger::Development); // Explicitly set environment
// Debugger::enable('23.75.345.200'); // Restrict debug bar to specific IPs
// Debugger::$logDirectory = __DIR__ . $ds . '..' . $ds . 'log'; // Log directory
Debugger::$logDirectory = __LOGS__; // Log directory
Debugger::$strictMode   = true;     // Show all errors (set to E_ALL & ~E_DEPRECATED for less noise)
Debugger::$maxLen       = 1000;     // Max length of dumped variables (default: 150)
Debugger::$maxDepth     = 5;        // Max depth of dumped structures (default: 3)
// Debugger::$dumpTheme    = 'dark';

#### Debugger::$editor = 'sublimeTracy://open?url=file://%file:%line'; // sublime не воспринимает urlecode, поэтому работать не будет
# Debugger::$editor = 'vscode://file/%file:%line'; // будет формировать сылку для vscode
Debugger::$editor = null; // не будет формировать сылку оставляет текст
### Debugger::$editor = 'sublimeTracy'; // sublime не воспринимает urlecode, поэтому работать не будет
// Debugger::$showLocation = true;
Debugger::$showLocation = Tracy\Dumper::LOCATION_SOURCE; // Sets only the display of the call location

// Debugger::$email = 'your@email.com'; // Send error notifications
// Debugger::$email = 'zzzzz@gmail.com'; // Send error notifications
######
######
if ( Debugger::$showBar === true && php_sapi_name() !== 'cli' ) {
    ( new TracyExtensionLoader( $app ) ); // Load FlightPHP Tracy extensions
}

/*
If you do not want to show the Tracy Bar, set:
Debugger::$showBar = false;
*/
#### ПРИМЕРЫ

/*
$arr = [10, 20.2, true, null, 'hello'];
dump($arr);
// or Debugger::dump($arr);
*/

// $jlog->info( Debugger::$logDirectory );
// $logger->info( Debugger::class );
// $logger->info( Debugger::$dumpTheme );
// dump( $app );
// bdump( [2, 4, 6, 8], 'even numbers up to ten' );
// dump( [1, 3, 5, 7, 9, 'odd numbers up to ten'] );
// Debugger::log( 'Unexpected error' ); // text message
// Debugger::log( 'Critical error', Debugger::ERROR ); // also sends an email notification https://tracy.nette.org/en/guide

// создать файл sublimeTracy Protocol.reg -добавления в реестр протокола:
/*
Windows Registry Editor Version 5.00

[HKEY_CLASSES_ROOT\sublimeTracy]
@="URL:sublimeTracy Protocol"
"URL Protocol"=""

[HKEY_CLASSES_ROOT\sublimeTracy\shell]

[HKEY_CLASSES_ROOT\sublimeTracy\shell\open]

[HKEY_CLASSES_ROOT\sublimeTracy\shell\open\command]
@="\"C:\\Program Files\\Sublime Text\\sublime_text.exe\" \"%1\""
*/

###################

/**********************************************
 *           Database Service Setup           *
 **********************************************/
// Uncomment and configure the following for your database:

// MySQL Example:
// $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// SQLite Example:
// $dsn = 'sqlite:' . $config['database']['file_path'];

// Register Flight::db() service
// In development, use PdoQueryCapture to log queries; in production, use PdoWrapper for performance.
// $pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
// $app->register('db', $pdoClass, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);

/**********************************************
 *         Third-Party Integrations           *
 **********************************************/
// Google OAuth Example:
// $app->register('google_oauth', Google_Client::class, [ $config['google_oauth'] ]);

// Redis Example:
// $app->register('redis', Redis::class, [ $config['redis']['host'], $config['redis']['port'] ]);

// Add more service registrations below as needed
