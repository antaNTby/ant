<?php
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
#### Monolog
// Register the logger with Flight
/*
Flight::register( 'log', Monolog\Logger::class, ['app'], function ( Monolog\Logger $log ) {
    $log->pushHandler( new Monolog\Handler\StreamHandler( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log', Monolog\Logger::DEBUG ) );
} );*/

// Get the $app var to use below
if ( empty( $app ) === true ) {
    $app = Flight::app();
}

//🔹 Используем Flight::map() вместо register()
// Flight::map( 'logger', function () {
Flight::map( 'logger', function () {
    // $logger  = new Logger( 'app' );
    $logger  = new Logger( SERVER_NAME );
    $handler = new StreamHandler( __APP__ . DIRECTORY_SEPARATOR . 'monolog.log', Logger::DEBUG );
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

Flight::map( 'jlog', function () {
    // $logger  = new Logger( 'app' );
    $logger  = new Logger( SERVER_NAME );
    $handler = new StreamHandler( __ROOT__ . DIRECTORY_SEPARATOR . 'debug.json', Logger::DEBUG );
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

// $logger->info( 'Doing work' );
// $jlog->error( 'Doing error' );
// $jlog->debug( 'DO ERROR' );

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
