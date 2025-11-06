<?php

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
Flight::set( 'flight.base_url', null );               // if this is in a subdirectory, you'll need to change this
Flight::set( 'flight.case_sensitive', false );        // if you want case sensitive routes, set this to true
Flight::set( 'flight.log_errors', false );            // if you want to log errors, set this to true
Flight::set( 'flight.handle_errors', true );          // if you want flight to handle errors, set this to true, otherwise Tracy will handle them
Flight::set( 'flight.views.path', __TPL__ );          // set the path to your view/template/ui files
Flight::set( 'flight.views.extension', '.tpl.html' ); // set the file extension for your view/template/ui files
Flight::set( 'flight.content_length', true );         // if flight should send a content length header

Flight::path( __APP__ . DIRECTORY_SEPARATOR );

/**********************************************
 *           FlightPHP Core Settings          *
 **********************************************/

// Get the $app var to use below
if ( empty( $app ) === true ) {
    $app = Flight::app();
}

echo 'config_flight - ok!<br>';
