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

//🔹 Используем Flight::map() вместо register()
Flight::map( 'logger', function () {
    // $logger  = new Logger( 'app' );
    $logger  = new Logger( SERVER_NAME );
    $handler = new StreamHandler( __ROOT__ . DIRECTORY_SEPARATOR . 'monolog.log', Logger::DEBUG );
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

echo 'config_monolog.php - ok!<br>';
