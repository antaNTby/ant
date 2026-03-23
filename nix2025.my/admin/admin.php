<?php

require __VENDOR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use classes\Cookie;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Smarty\Smarty;
use subs\Companies;
use subs\CurrencyTypes;
use subs\OrderStatuses;

/*
*/

// header( 'Location: admin_app' );

// dump(
// 	[
// 		'__DIR_ADMIN.PHP__' => __DIR__,

// 		'__DIR__'           => __DIR__,

// 		'__ROOT__'          => __ROOT__,

// 		'__ADMIN__'         => __ADMIN__,
// 		'__PUBLIC__'        => __PUBLIC__,
// 		'__VENDOR__'        => __VENDOR__,

// 	]
// );

// This is where you can set some flight config variables.
/*
flight.base_url ?string - Переопределение базового URL запроса. (по умолчанию: null)
flight.case_sensitive bool - Учет регистра при сопоставлении URL. (по умолчанию: false)
flight.handle_errors bool - Разрешить Flight обрабатывать все ошибки внутренне. (по умолчанию: true)
flight.log_errors bool - Записывать ошибки в файл журнала сервера веб-сервера. (по умолчанию: false)
flight.views.path string - Каталог, содержащий файлы шаблонов представлений. (по умолчанию: ./views)
flight.views.extension string - Расширение файла шаблона представления. (по умолчанию: .php)
flight.content_length bool - Установить заголовок Content-Length. (по умолчанию: true)
flight.v2.output_buffering bool - Использовать устаревшее буферизацию вывода. См. переход к v3. (по умолчанию: false)

https://docs.flightphp.com/learn/api#-
*/

Flight::set( 'flight.base_url', __ADMIN__ );          // if this is in a subdirectory, you'll need to change this
Flight::set( 'flight.case_sensitive', false );        // if you want case sensitive routes, set this to true
Flight::set( 'flight.log_errors', false );            // if you want to log errors, set this to true
Flight::set( 'flight.handle_errors', true );          // if you want flight to handle errors, set this to true, otherwise Tracy will handle them
Flight::set( 'flight.views.path', __TPL__ );          // set the path to your view/template/ui files
Flight::set( 'flight.views.extension', '.tpl.html' ); // set the file extension for your view/template/ui files
Flight::set( 'flight.content_length', true );         // if flight should send a content length header

// Flight::path( __VENDOR__ . DIRECTORY_SEPARATOR );
Flight::path( __APP__ . DIRECTORY_SEPARATOR );
Flight::path( __CLASSES__ . DIRECTORY_SEPARATOR );

// dd( $app );
/*
ai-1.- Создать кастомный модификатор: Если вам нужно использовать intval в шаблоне, можно зарегистрировать свой модификатор:
*/

function smarty_modifier_intval( $value )
{
    // альтернатива использовать формат_строки {$value|string_format:"%d"}
    return intval( $value );
}
function smarty_modifier_dump( $value )
{
    return dump( $value );
}
function smarty_modifier_jlog( $value )
{
    return jlog( $value );
}
function smarty_modifier_formatUsd( $value )
{
    return formatUsd( $value );
}

function smarty_modifier_formatUnp( $string )
{
    // Удалим всё, кроме цифр
    $digits = preg_replace( '/\D/', '', $string );
    // Преобразуем в формат 123 456 789

    return preg_replace( '/(\d{3})(\d{3})(\d{3})/', '$1 $2 $3', $digits );
}
function smarty_modifier_zeroPad(
    $number,
    $length = 2,
    $symbol = '0'
) {
    return str_pad( $number, $length, $symbol, STR_PAD_LEFT );
}

Flight::register( 'view', Smarty::class, [], function ( Smarty $smarty ) {
    $smarty->setTemplateDir( __TPL__ );                                                                         // здесь лежат шаблоны tpl.html
    $smarty->setCompileDir( __ROOT__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'compile_dir' );  // здесь компилируюся *.php
    $smarty->setConfigDir( __ROOT__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_config' ); // незнаю
    $smarty->setCacheDir( __ROOT__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'smarty_cache' );
    $smarty->compile_id    = 'admin_';
    $smarty->force_compile = true;

    // Обязательно отключите проверку компиляции в производстве для максимальной производительности.
    // $smarty->setCompileCheck( \Smarty\Smarty::COMPILECHECK_OFF );
/*
ai-2.- Затем подключить его в Smarty:
*/
    $smarty->registerPlugin( 'modifier', 'intval', 'smarty_modifier_intval' );
    $smarty->registerPlugin( 'modifier', 'dump', 'smarty_modifier_dump' );
    $smarty->registerPlugin( 'modifier', 'jlog', 'smarty_modifier_jlog' );
    $smarty->registerPlugin( 'modifier', 'formatUsd', 'smarty_modifier_formatUsd' );
    $smarty->registerPlugin( 'modifier', 'formatUnp', 'smarty_modifier_formatUnp' );
    $smarty->registerPlugin( 'modifier', 'zeroPad', 'smarty_modifier_zeroPad' );

    // $smarty->testInstall();
} );

Flight::map( 'render', function (
    string $template = 'index.tpl.html',
    array  $data = []
): void {
    // dd( $data );
    Flight::view()->assign( $data );

    if ( Flight::view()->templateExists( $template ) ) {
        Flight::view()->display( $template );
    } else {
        Flight::halt( 406, '<em>' . $template . '</em>' . '<br><br> Smarty template not exists.<br> 406 Not Acceptable' );
    }

} );

Flight::map( 'fetch', function (
    string $template = 'index.tpl.html',
    array  $data = []
): void {
    Flight::view()->assign( $data );
    if ( Flight::view()->templateExists( $template ) ) {
        Flight::view()->fetch( $template );
    } else {
        Flight::halt( 406, '<em>' . $template . '</em>' . '<br><br> Smarty template not exists.<br> 406 Not Acceptable' );
    }
} );

// если влом писать Флайт
$smarty = Flight::view();
// $smarty->assign( 'blablabla', 'BLABLABLA' );

#### Monolog
#### Monolog
#### Monolog
#### Monolog
// Register the logger with Flight
/*
Flight::register( 'log', Monolog\Logger::class, ['app'], function ( Monolog\Logger $log ) {
    $log->pushHandler( new Monolog\Handler\StreamHandler( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log', Monolog\Logger::DEBUG ) );
} );*/

//🔹 Используем Flight::map() вместо register()
Flight::map( 'logger', function () {
    // $logger  = new Logger( 'app' );
    $logger  = new Logger( SERVER_NAME );
    $handler = new StreamHandler( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log', Logger::DEBUG );
    /*$formatter = new LineFormatter( "[%datetime%] %channel%.%level_name% %message% %context.file%:%context.line%\n", 'M,d H:i:s.u' );*/
    $formatter = new LineFormatter(
        "[%datetime% %level_name%] %context.file%:%context.line%\n***%message%***  %context.pathinfo%\n%context% %extra%\n%context.trace%\n", 'H:i:s.u' ); /*Y-m-d*/

    $formatter->includeStacktraces( true );
    $formatter->allowInlineLineBreaks( true );
    $formatter->ignoreEmptyContextAndExtra( true );
    $handler->setFormatter( $formatter );
    $logger->pushHandler( $handler );

    return $logger;
} );

//🔹 Получаем логгер
$logger = Flight::logger(); // Используем map() → теперь корректно

// 🔹 Проверяем и логируем
if ( !$logger ) {
    throw new Exception( 'Ошибка: логгер не зарегистрирован!' );
}

############
############
############
############
############

Flight::register( 'cookie', Cookie::class, [] );
function setSecureCookie(
    $name,
    $value,
    $expires
) {
    Flight::cookie()->set( $name, $value,
        $expires,
        '/',
        null,
        true,
        true,
        'Lax'
    );
}

Flight::map( 'error', function ( Throwable $ex ) use ( $logger ) {
    // Получаем подробную информацию об ошибке
    $type    = get_class( $ex );
    $file    = $ex->getFile();
    $line    = $ex->getLine();
    $message = $ex->getMessage();
    $trace   = nl2br( $ex->getTraceAsString() );
    // Проверяем доступность глобального запроса

    if ( Flight::has( 'request' ) ) {
        $request = Flight::request();

        $url         = $request->url;
        $method      = $request->method;
        $fullUrl     = $request->getFullUrl();
        $baseUrl     = $request->getBaseUrl();
        $body        = $request->getBody();
        $requestData = isset( $request ) ? compact(
            'url', 'method', 'fullUrl', 'baseUrl', 'body'
        ) : null;
    }

    if ( Flight::has( 'response' ) ) {
        $response = Flight::response();

        $url          = $response->url;
        $method       = $response->method;
        $fullUrl      = $response->getFullUrl();
        $baseUrl      = $response->getBaseUrl();
        $body         = $response->getBody();
        $responseData = isset( $response ) ? compact(
            'url', 'method', 'fullUrl', 'baseUrl', 'body'
        ) : null;
    }

    $data = [
        'title'        => 'ОШИБКА:',
        'message'      => $message,
        'type'         => $type,
        'file'         => $file,
        'line'         => $line,
        'trace'        => $trace,
        'requestData'  => $requestData ?? null,
        'responseData' => $responseData ?? null,
        'pathinfo'     => pathinfo( $ex->getFile() )['basename'] . ':' . $ex->getLine(),
    ];

    $loggerData = [
        'pathinfo' => pathinfo( $ex->getFile() )['basename'] . ':' . $ex->getLine(),
        'type'     => get_class( $ex ),
        'file'     => $ex->getFile(),
        'line'     => $ex->getLine(),
        'trace'    => $ex->getTraceAsString(),
    ];
    $logger->critical( $message, $loggerData );

    $error_dump = Flight::view()->fetch( 'error500.tpl.html', $data );
    Flight::response()->setHeader( 'Content-Type', 'text/html' );
    Flight::halt( 500, $error_dump );

} );

Flight::map( 'notFound', function () {
    $data = [
        'body_height' => Flight::get( 'body_height' ),
        'body_width'  => Flight::get( 'body_width' ),
        'pageH1'      => 'Страница не найдена ',
        'title'       => '404 Страница не найдена ',
    ];
    Flight::response()->setHeader( 'Content-Type', 'text/html' );
    Flight::render( 'notFound.tpl.html', $data );
    // Flight::halt( 404, '404 Страница не найдена 404' );
} );

############
############
############
############
############
require __APP__ . DIRECTORY_SEPARATOR . 'functions.php';
require __APP__ . DIRECTORY_SEPARATOR . 'settings.php';
$config = require __APP__ . DIRECTORY_SEPARATOR . 'connect.php';

############
############
############
############
############

$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';
Flight::set( 'dsn', $dsn );

############
############
############
############
############

/*
Flight::register( 'ldb', flight\database\PdoWrapper::class, ['sqlite:' . $config['sqlite_database_path']], function ( $db ) {
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
} );
Flight::register( 'PdoWrapperDb', flight\database\PdoWrapper::class, [$dsn, $config['database']['user'], $config['database']['password']] );
*/

// Set this as a registered class in Flight
// Flight::register( 'db', 'PDO', ['sqlite:test.db'] );
Flight::register( 'db', 'PDO', [$dsn, $config['database']['user'], $config['database']['password']] );

// create a new container
$container = new Dice\Dice;
// don't forget to reassign it to itself like below!
$container = $container->addRule( 'PDO', [
    // shared means that the same object will be returned each time
    'shared'          => true,
    'constructParams' => [Flight::get( 'dsn' ), DB_USER, DB_PASS],
] );
// This registers the container handler so Flight knows to use it.
Flight::registerContainerHandler( function (
    $class,
    $params
) use ( $container ) {
    return $container->create( $class, $params );
} );

############
############
############
############
############
$query                = Flight::db()->query( "SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "'" );
$AllTablesColumnNames = [];
while ( $row = $query->fetch( \PDO::FETCH_ASSOC ) ) {
    $AllTablesColumnNames[$row['TABLE_NAME']][] = $row['COLUMN_NAME'];
}
Flight::set( 'AllTablesColumnNames', $AllTablesColumnNames );
// dd( Flight::get( 'AllTablesColumnNames' ) );
############
############
############
############
######## ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС
Flight::register( 'currencySub', CurrencyTypes::class );
Flight::register( 'statusesSub', OrderStatuses::class );
Flight::register( 'companiesSub', Companies::class );
######## ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС ЗАРЕГИСТРИРУЙТЕ ВАШ КЛАСС

$router = Flight::router();
include_once __APP__ . DIRECTORY_SEPARATOR . 'routes.php';

############
############
############
############

############
############
############
############

// В вашем bootstrap файле

Flight::before( 'start', function () {
    Flight::set( 'start_time', microtime( true ) );

} );

Flight::after( 'start', function () {
    if ( Flight::get( 'LOG_REQUEST_TIME' ) ) {

        $end   = microtime( true );
        $start = Flight::get( 'start_time' );

        Flight::logger()->info( 'Запрос ' . Flight::request()->url . ' занял ' . round( ( $end - $start ) * 1000, 2 ) . ' ms' );

        // Вы также можете добавить свои заголовки запроса или ответа
        // чтобы зафиксировать их (будьте осторожны, так как это будет
        // много данных, если у вас много запросов)
        if ( Flight::has( 'request' ) ) {
            Flight::logger()->info( 'Заголовки запроса: ' . json_encode( Flight::request()->headers ) );
        }

        if ( Flight::has( 'response' ) ) {
            Flight::logger()->info( 'Заголовки ответа: ' . json_encode( Flight::response()->headers ) );
        }
    }

} );

// $logger->info( 'Инициализация FlightPHP завершена.' );
// $cookie=Flight::cookie();

Flight::set( 'LOG_REQUEST_TIME', false );

Flight::start();
