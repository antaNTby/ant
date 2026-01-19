<?php

/**********************************************
 *           FlightPHP Core Settings          *
 **********************************************/

// This autoloads your code in the app directory so you don't have to require_once everything
// You'll need to namespace your classes with "app\folder\" to include them properly
Flight::path( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' );

Flight::set( 'flight.base_url', null );        // if this is in a subdirectory, you'll need to change this
Flight::set( 'flight.case_sensitive', false ); // if you want case sensitive routes, set this to true
Flight::set( 'flight.log_errors', true );      // Log errors to file. Recommended: true in production
// Flight::set( 'flight.log_errors', false ); // Log errors to file. Recommended: true in production
// Flight::set( 'flight.handle_errors', true );          // Let Tracy handle errors if false. Set true to use Flight's error handler
Flight::set( 'flight.handle_errors', false );         // Let Tracy handle errors if false. Set true to use Flight's error handler
Flight::set( 'flight.views.path', __TPL__ );          // set the path to your view/template/ui files
Flight::set( 'flight.views.extension', '.tpl.html' ); // set the file extension for your view/template/ui files
Flight::set( 'flight.content_length', false );        // if flight should send a content length header

Flight::path( __APP__ . DIRECTORY_SEPARATOR );

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

// Flight::map( 'notFound', function () {
//     echo 'map notFound';
//     dump( Flight::app() );
// } );

// Flight::map( 'notFound', function () {
//     $data = [
//         'body_height' => Flight::get( 'body_height' ),
//         'body_width'  => Flight::get( 'body_width' ),
//         'pageH1'      => 'Страница не найдена ',
//         'title'       => '404 Страница не найдена ',
//     ];
//     Flight::response()->setHeader( 'Content-Type', 'text/html' );
//     Flight::render( 'notFound.tpl.html', $data );
//     // Flight::halt( 404, '404 Страница не найдена 404' );
// } );

// Flight::map( 'notFound', function () {
//     $url = Flight::request()->url;

//     // You could also use Flight::render() with a custom template.
//     $output = <<<HTML
//         <h1>404 Not Found</h1>
//         <h3>The page you have requested {$url} could not be found.</h3>
//         HTML;

//     Flight::response()
//         ->clearBody()
//         ->status( 404 )
//         ->write( $output )
//         ->send();
// } );
