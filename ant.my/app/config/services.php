<?php

use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use flight\debug\tracy\TracyExtensionLoader;
use flight\Engine;
use flight\Session;
use Overclokk\Cookie\Cookie;
use Pdo\Mysql;
use Symfony\Component\VarDumper\VarDumper;
use Tracy\Debugger;

// Регистрируем (мапим) метод dumps в ядре Flight
Flight::map( 'dumps', function ( ...$vars ) {
    foreach ( $vars as $v ) {
        VarDumper::dump( $v );
    }

    // Возвращаем результат (удобно для вложенных вызовов)

    return count( $vars ) === 1 ? $vars[0] : $vars;
}
);

// Регистрируем метод cookie()
Flight::map( 'cookie', function () {
    return new \Overclokk\Cookie\Cookie(); // Без аргументов в конструкторе
} );

// Регистрация сервиса
Flight::register( 'authService', \app\services\AuthService::class );

// dd( __DIR__ );

Flight::register( 'session', \flight\Session::class, [
    [
        'prefix'         => 'admin_',                                                                // Prefix for the session cookie
        'save_path'      => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'sessions', // Path to save session files
                                                                                                     // ...other options...
        'encryption_key' => $_ENV['JWT_SECRET'],                                                     // Enable encryption with a secure key
        'auto_commit'    => true,                                                                    // Automatically commit session changes on shutdown
        'start_session'  => true,                                                                    // Start the session automatically
        'test_mode'      => false,                                                                   // Enable for testing without affecting PHP's session state
    ],
] );

$session = Flight::session();
Flight::set( 'session', $session );

// Регистрируем маппинг csrfToken
Flight::map( 'csrfToken', function () {
    $session = Flight::session();

    // Если токена еще нет в сессии — генерируем новый
    if ( !$session->get( 'csrf_token' ) ) {
        $token = bin2hex( random_bytes( 32 ) );
        $session->set( 'csrf_token', $token );
    }

    return $session->get( 'csrf_token' );
} );

// Регистрация простого хелпера для уведомлений

Flight::map( 'flash', function (
    string $type,
    string $message
) {
    static $trusted = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
    $session        = Flight::session();
    $type           = in_array( $type, $trusted ) ? $type : 'dark';
    // Получаем текущие сообщения
    $flashes = $session->get( 'flash_messages' ) ?? [];
    // Добавляем новое (используем краткий синтаксис)
    $flashes[] = compact( 'type', 'message' );
    $session->set( 'flash_messages', $flashes );

    return Flight::app(); // Позволяет делать Flight::flash(...)->render(...)
} );

Flight::map( 'Display', function (
    string $template,
    array  $data = []
) {

    $page = new app\controllers\RenderDataController( $template, $data );
    $page->display( $template, $data );
    // dd( $data );

    return Flight::app(); // Позволяет делать Flight::flash(...)->render(...)

} );

// Debugger::enable(); // Auto-detects environment
// Debugger::enable( Debugger::Development ); // Explicitly set environment
// Debugger::enable('23.75.345.200'); // Restrict debug bar to specific IPs
// Debugger::$logDirectory = __DIR__ . $ds . '..' . $ds . 'log'; // Log directory

Debugger::enable( Debugger::Development );                          // Explicitly set environment
Debugger::$logDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'logs'; // Log directory
Debugger::$strictMode   = true;                                   // Show all errors (set to E_ALL & ~E_DEPRECATED for less noise)
Debugger::$maxLen       = 1000;                                   // Max length of dumped variables (default: 150)
Debugger::$maxDepth     = 8;                                      // Max depth of dumped structures (default: 3)
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

    Flight::set( 'flight.content_length', false );
    new TracyExtensionLoader( Flight::app(), ['session_data' => Flight::session()->getAll()] );

    // ( new TracyExtensionLoader( $app ) ); // Load FlightPHP Tracy extensions

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
// bdump($var) - Это выведет переменную на панель Трейси в отдельной панели.
// dumpe($var) - Это выведет переменную, а затем немедленно завершит работу.

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
$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// SQLite Example:
// $dsn = 'sqlite:' . $config['database']['file_path'];
// $dsn = 'sqlite:' . $config['database']['sqlite_file_path'];

// Register Flight::db() service
// In development, use PdoQueryCapture to log queries; in production, use PdoWrapper for performance.
// $pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
// $app->register('db', $pdoClass, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);

## PHP 8.4
// Flight::register( 'db', \flight\database\SimplePdo::class, [

//     "mysql:host={$myConfig['database']['host']};dbname={$myConfig['database']['dbname']}", $myConfig['database']['user'] ?? null, $myConfig['database']['password'] ?? null,
// /**/
//     [
//         PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8mb4\'',
//         PDO::ATTR_EMULATE_PREPARES   => false,
//         PDO::ATTR_STRINGIFY_FETCHES  => false,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     ],
// /**/

// ] );

## PHP 8.5
##  ✅ Используется use Pdo\Mysql; для доступа к новым константам.
##  ✅ Mysql::ATTR_INIT_COMMAND заменяет устаревший PDO::MYSQL_ATTR_INIT_COMMAND.
##  ✅ В DSN сразу указан charset=utf8mb4, что делает установку кодировки надёжной.
##  ✅ Добавлены полезные настройки (ERRMODE_EXCEPTION, FETCH_ASSOC, отключение эмуляции).
##  ✅ Конфигурация полностью избавлена от deprecated‑предупреждений.
##  Такой шаблон можно использовать как основу для всех будущих подключений в Flight.

Flight::register( 'db', \flight\database\SimplePdo::class, [

    // DSN с явным указанием charset
    "mysql:host={$myConfig['database']['host']};dbname={$myConfig['database']['dbname']};charset=utf8mb4",
    $myConfig['database']['user'] ?? null,
    $myConfig['database']['password'] ?? null,

    [
        // Новые константы из Pdo\Mysql
        Mysql::ATTR_INIT_COMMAND       => "SET NAMES 'utf8mb4'",
        Mysql::ATTR_USE_BUFFERED_QUERY => true,
        Mysql::ATTR_LOCAL_INFILE       => false,

        // Общие PDO‑атрибуты
        PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES     => false,
        PDO::ATTR_STRINGIFY_FETCHES    => false,
        PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,
    ],

] );

// dd( Flight::db() );

/**********************************************
 *         Third-Party Integrations           *
 **********************************************/
// Google OAuth Example:
// $app->register('google_oauth', Google_Client::class, [ $config['google_oauth'] ]);

// Redis Example:
// $app->register('redis', Redis::class, [ $config['redis']['host'], $config['redis']['port'] ]);

// Add more service registrations below as needed
