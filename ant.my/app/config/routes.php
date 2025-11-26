<?php

Flight::route( 'GET /', function () {

    $contentTemplate = __TPL__ . DIRECTORY_SEPARATOR . 'home.tpl.html';
    $smartyData      = [];
    Flight::fetch( $contentTemplate, $smartyData );
    Flight::render( 'index.tpl.html', [
        'admin_main_content_template' => $contentTemplate,
        'body_height'                 => Flight::get( 'body_height' ),
        'body_width'                  => Flight::get( 'body_width' ),
        'template_exist'              => Flight::view()->templateExists( $contentTemplate ),
        'pageH1'                      => 'Стартовая страница ',
        'title'                       => 'Стартовая страница ',
        'title_ru'                    => 'Просмотр Стартовая страница',
        'aside_reverse'               => Flight::get( 'aside_reverse' ),
        'aside_hide'                  => Flight::get( 'aside_hide' ),
    ] );
} );

Flight::route( 'OPTIONS *', function () {
    // https: //docs.flightphp.com/learn/security#cors
    header( 'Access-Control-Allow-Origin: *' );
    // header('Access-Control-Allow-Origin: https://your-domain.com');
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
    header( 'Access-Control-Allow-Headers: Content-Type, Authorization' );
    header( 'Access-Control-Max-Age: 86400' ); // Кеширование
    Flight::halt( 200 );
} );

require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ui_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api_routes.php';
// require __APP__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'sub_routes.php';
