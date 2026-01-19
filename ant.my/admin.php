<?php
################################################################################
################################################################################
################################################################################
################################################################################
###########                A D M I N . P H P            ########################
################################################################################
################################################################################
################################################################################
################################################################################

define( '__PARENT_DIR__', dirname( __DIR__, 1 ) );
define( '__ROOT__', __DIR__ );
define( '__PUBLIC__', __ROOT__ . DIRECTORY_SEPARATOR . 'public' );

define( '__APP__', __ROOT__ . DIRECTORY_SEPARATOR . 'app' );
define( '__VENDOR__', __ROOT__ . DIRECTORY_SEPARATOR . 'vendor' );
define( '__CONFIG__', __APP__ . DIRECTORY_SEPARATOR . 'config' );
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
define( 'DEFAULT_TPL_HTML', 'layout.tpl.html' );
// define( 'DEFAULT_TPL_HTML', 'index.tpl.html' );
// define( 'DEFAULT_TPL_HTML', 'admin.tpl.html' );

$config = require __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php';
// It is better practice to not use static methods for everything. It makes your
// app much more difficult to unit test easily.
// This is important as it connects any static calls to the same $app object
$app = Flight::app();

//🔹 Получаем логгер
$logger = $app->logger();
//🔹 Получаем логгер
$jlog = $app->jlog();

// 🔹 Проверяем и логируем
if ( !$logger or !$jlog ) {
    throw new Exception( 'Ошибка: логгер не зарегистрирован!' );
}

require __CONFIG__ . DIRECTORY_SEPARATOR . 'services.php';

require __ROUTES__ . DIRECTORY_SEPARATOR . 'routes.php';

Flight::before( 'start', function () {
    Flight::set( 'start_time', microtime( true ) );

} );

Flight::after( 'start', function () {
    if ( Flight::get( 'LOG_REQUEST_TIME' ) ) {

        $end   = microtime( true );
        $start = Flight::get( 'start_time' );

        Flight::jlog()->info( 'Запрос ' . Flight::request()->url . ' занял ' . round( ( $end - $start ) * 1000, 2 ) . ' ms' );

/*
Вы также можете добавить свои заголовки запроса или ответа
чтобы зафиксировать их (будьте осторожны, так как это будет
много данных, если у вас много запросов)
*/
        if ( Flight::has( 'request' ) ) {
            Flight::jlog()->info( 'Заголовки запроса: ' . json_encode( Flight::request()->headers ) );
        }

        if ( Flight::has( 'response' ) ) {
            Flight::jlog()->info( 'Заголовки ответа: ' . json_encode( Flight::response()->headers ) );
        }
    }

} );

Flight::set( 'LOG_REQUEST_TIME', true );

ERROR;

require __CONFIG__ . DIRECTORY_SEPARATOR . 'run.php';
