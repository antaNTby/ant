<?php
// header( 'X-Global-Test: Working' );
// date_default_timezone_set( 'Europe/Minsk' );
################################################################################
################################################################################
################################################################################
################################################################################
###########                A D M I N . P H P            ########################
################################################################################
################################################################################
################################################################################
################################################################################
// Настройка времени жизни сессии
// ini_set( 'session.gc_maxlifetime', 9 ); // 2 часа
// session_set_cookie_params( 9 );

/*
C:\Users\a>subl --project c:\git\ant\ant.my\_ant.my_.sunlime-project
*/
define( '__PARENT_DIR__', dirname( __DIR__, 1 ) );

define( '__ROOT__', __DIR__ );
define( '__PUBLIC__', __ROOT__ . DIRECTORY_SEPARATOR . 'public' );
define( '__APP__', __ROOT__ . DIRECTORY_SEPARATOR . 'app' );
define( '__VENDOR__', __ROOT__ . DIRECTORY_SEPARATOR . 'vendor' );

define( '__CONFIG__', __APP__ . DIRECTORY_SEPARATOR . 'config' );
define( '__LOGS__', __APP__ . DIRECTORY_SEPARATOR . 'logs' );
define( '__CONTROLLERS__', __APP__ . DIRECTORY_SEPARATOR . 'controllers' );
define( '__ROUTES__', __APP__ . DIRECTORY_SEPARATOR . 'routes' );
define( '__TPL__', __APP__ . DIRECTORY_SEPARATOR . 'tpl' );

require __VENDOR__ . DIRECTORY_SEPARATOR . 'autoload.php';

if ( file_exists( __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php' ) === false ) {
    Flight::halt( 500, 'Config file not found. Please create a config.php file in the app/config directory to get started.' );
}

// CONFIG ALL
define( 'SERVER_NAME', $_SERVER['SERVER_NAME'] );
define( 'COPYRIGHT', 'Copyright ' . date( 'Y' ) . ' © ' . 'antaNT64.' );
define( 'DEFAULT_TPL_HTML', 'b2b/index.tpl.html' );
// define( 'DEFAULT_TPL_HTML', 'index.tpl.html' );
// define( 'DEFAULT_TPL_HTML', 'admin.tpl.html' );

$config = require __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_flight.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_loggers.php';
$logger = $app->logger();
if ( !$logger ) {
    throw new Exception( 'Ошибка: логгер не зарегистрирован!' );
}

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_smarty.php';
$smarty->assign( 'myConfig', $config );
$smarty->assign( 'nonce', Flight::get( 'csp_nonce' ) );

// Инициализация сессии Flight до любых роутов и вывода
require __CONFIG__ . DIRECTORY_SEPARATOR . 'services.php';
$session = Flight::session();

// 2. Чистая и правильная строка CSP без тестовых меток
// Прописав header() напрямую в index.php (до всех Middleware и роутов),
// мы гарантировали, что этот заголовок уйдет первым.
// Теперь ваш сайт доверяет внешним шрифтам, а скрипты работают через безопасный nonce.
$csp_string = "default-src 'self'; " .
    "script-src 'nonce-{$nonce}' 'strict-dynamic'; " .
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.cdnfonts.com; " .
    "font-src 'self' https://fonts.gstatic.com https://fonts.cdnfonts.com; " .
    "img-src 'self' data:;";

header( "Content-Security-Policy: $csp_string" );

require __ROUTES__ . DIRECTORY_SEPARATOR . 'routes.php';

// Flight::before( 'start', function () use ( $nonce ) {

//     header( 'Content-Security-Policy: ' .
//         // Скрипты: доверяем nonce и разрешаем динамическую загрузку
//         "script-src 'nonce-$nonce' 'strict-dynamic'; " .
//         // Стили: разрешаем себя, инлайны и оба сервиса шрифтов
//         "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.cdnfonts.com; " .
//         // Шрифты: разрешаем загрузку самих файлов шрифтов (важно для Google/CDN)
//         "font-src 'self' https://fonts.gstatic.com https://fonts.cdnfonts.com; " .
//         // Остальное по умолчанию (картинки и т.д.)
//         "default-src 'self'; " .
//         "img-src 'self' data:;" );
// } );

// Flight::before( 'start', function () use ( $nonce ) {
//     header( 'Content-Security-Policy: ' .
//         // Убираем 'self' отсюда, чтобы не было предупреждения
//         "script-src 'nonce-$nonce' 'strict-dynamic'; " .
//         "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.cdnfonts.com; " .
//         "font-src 'self' https://fonts.gstatic.com https://fonts.cdnfonts.com; " .
//         "default-src 'self';" );
// } );

// Flight::before( 'start', function () {

//     Flight::set( 'start_time', microtime( true ) );
// } );

// Flight::after( 'start', function () {
//     if ( Flight::get( 'LOG_REQUEST_TIME' ) ) {

//         $end   = microtime( true );
//         $start = Flight::get( 'start_time' );

//         Flight::logger()->notice( 'Запрос ' . Flight::request()->url . ' занял ' . round( ( $end - $start ) * 1000, 2 ) . ' ms' );

//         /*
//             Вы также можете добавить свои заголовки запроса или ответа
//             чтобы зафиксировать их (будьте осторожны, так как это будет
//             много данных, если у вас много запросов)
//             */
//         if ( Flight::has( 'request' ) ) {
//             Flight::logger()->notice( 'Заголовки запроса: ' . json_encode( Flight::request()->headers ) );
//         }

//         if ( Flight::has( 'response' ) ) {
//             Flight::logger()->notice( 'Заголовки ответа: ' . json_encode( Flight::response()->headers ) );
//         }
//     }

// } );

Flight::set( 'LOG_REQUEST_TIME', true );
// Flight::set( 'SESSION_EXPIRE_TIMEOUT', 24 * 60 * 60 ); // seconds to expire session
Flight::set( 'SESSION_EXPIRE_TIMEOUT', 1 * 60 * 60 );    // seconds to expire session  -1 час
Flight::set( 'TOKEN_EXPIRE_TIMEOUT', 2 * 24 * 60 * 60 ); // seconds to expire session  -2 суток
// Flight::set( 'SESSION_EXPIRE_TIMEOUT', 20 );          // seconds to expire session  - 2 часа

Flight::set( 'jwt_key', $_ENV['JWT_SECRET'] );
// var_dump( $_ENV['JWT_SECRET'] );

/*
 .----..---.  .--.  .----.  .---.     .---. .-. .-.  .--.  .---.    .----. .-. .-..----. .----..-.  .-.
{ {__ {_   _}/ {} \ | {}  }{_   _}   {_   _}| {_} | / {} \{_   _}   | {}  }| { } || {}  }| {}  }\ \/ /
.-._} } | | /  /\  \| .-. \  | |       | |  | { } |/  /\  \ | |     | .--' | {_} || .--' | .--'  }  {
`----'  `-' `-'  `-'`-' `-'  `-'       `-'  `-' `-'`-'  `-' `-'     `-'    `-----'`-'    `-'     `--'
*/
Flight::start();
