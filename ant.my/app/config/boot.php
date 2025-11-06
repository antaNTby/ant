<?php
Flight::before( 'start', function () {
    Flight::set( 'start_time', microtime( true ) );

} );

Flight::after( 'start', function () {
    if ( Flight::get( 'LOG_REQUEST_TIME' ) ) {

        $end   = microtime( true );
        $start = Flight::get( 'start_time' );

        Flight::logger()->info( 'Запрос ' . Flight::request()->url . ' занял ' . round( ( $end - $start ) * 1000, 2 ) . ' ms' );

/*
Вы также можете добавить свои заголовки запроса или ответа
чтобы зафиксировать их (будьте осторожны, так как это будет
много данных, если у вас много запросов)
*/
        if ( Flight::has( 'request' ) ) {
            Flight::logger()->info( 'Заголовки запроса: ' . json_encode( Flight::request()->headers ) );
        }

        if ( Flight::has( 'response' ) ) {
            Flight::logger()->info( 'Заголовки ответа: ' . json_encode( Flight::response()->headers ) );
        }
    }

} );
Flight::set( 'LOG_REQUEST_TIME', false );

// At this point, your app should have all the instructions it needs and it'll
// "start" processing everything. This is where the magic happens.
$app->start();
/*
 .----..---.  .--.  .----.  .---.     .---. .-. .-.  .--.  .---.    .----. .-. .-..----. .----..-.  .-.
{ {__ {_   _}/ {} \ | {}  }{_   _}   {_   _}| {_} | / {} \{_   _}   | {}  }| { } || {}  }| {}  }\ \/ /
.-._} } | | /  /\  \| .-. \  | |       | |  | { } |/  /\  \ | |     | .--' | {_} || .--' | .--'  }  {
`----'  `-' `-'  `-'`-' `-'  `-'       `-'  `-' `-'`-'  `-' `-'     `-'    `-----'`-'    `-'     `--'
*/

// Flight::logger()->info( 'TEST TEST TEST : ' . SERVER_NAME );
