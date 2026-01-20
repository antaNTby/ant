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
    $handler = new StreamHandler( __LOGS__ . DIRECTORY_SEPARATOR . 'monolog.log', Logger::DEBUG );
    /*$formatter = new LineFormatter( "[%datetime%] %channel%.%level_name% %message% %context.file%:%context.line%\n", 'M,d H:i:s.u' );*/
    $formatter = new LineFormatter(
        "[%datetime% %level_name%] %context.file%:%context.line%  %message%   %context.pathinfo%\n%context% %extra%\n%context.trace%\n", 'H:i:s.u' ); /*Y-m-d*/

    $formatter->includeStacktraces( true );
    $formatter->allowInlineLineBreaks( true );
    $formatter->ignoreEmptyContextAndExtra( true );
    $handler->setFormatter( $formatter );
    $logger->pushHandler( $handler );

    return $logger;
} );

##  Flight::map( 'jlog', function () {
##  // $logger  = new Logger( 'app' );
##  $logger  = new Logger( SERVER_NAME );
##  $handler = new StreamHandler( __LOGS__ . DIRECTORY_SEPARATOR . 'jlog.log', Logger::DEBUG );
##  /*$formatter = new LineFormatter( "[%datetime%] %channel%.%level_name% %message% %context.file%:%context.line%\n", 'M,d ##  H:i:s.u' );*/
##  $formatter = new LineFormatter(
##    "[%datetime% %level_name%] %context.file%:%context.line%  %message%   %context.pathinfo%\n%context% %extra%\n%c##  ontext.trace%\n", 'H:i:s.u' ); /*Y-m-d*/
##  $formatter->includeStacktraces( true );
##  $formatter->allowInlineLineBreaks( true );
##  $formatter->ignoreEmptyContextAndExtra( true );
##  $handler->setFormatter( $formatter );
##  $logger->pushHandler( $handler );
##  return $logger;
##  } );

### Уровни
### log
### debug
### info
### notice
### warning
### error
### critical
### alert
### emergency

// $logger->info( 'Doing work' );
// $jlog->error( 'Doing error' );
// $jlog->debug( 'DO ERROR' );
