<?php
Flight::group( '/admin', function () {

    Flight::route( '/log', function () {

        $logContent = file_get_contents( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log' );
        Flight::set( 'log', $logContent );
        $monolog = Flight::get( 'log' );

        $contentTemplate = __TPL__ . DIRECTORY_SEPARATOR . 'subs' . DIRECTORY_SEPARATOR . 'log.tpl.html';
        $smartyData      = [];
        Flight::fetch( $contentTemplate, $smartyData );
        Flight::render( 'index.tpl.html', [
            'admin_main_content_template' => $contentTemplate,
            'body_height'                 => Flight::get( 'body_height' ),
            'body_width'                  => Flight::get( 'body_width' ),
            'template_exist'              => Flight::view()->templateExists( $contentTemplate ),
            'pageH1'                      => 'Судовой журнал ',
            'title'                       => 'Судовой журнал ',
            'title_ru'                    => 'Просмотр Судовой журнал',
            'aside_reverse'               => Flight::get( 'aside_reverse' ),
            'aside_hide'                  => Flight::get( 'aside_hide' ),
            'monolog'                     => $monolog,
        ] );

    } );

    Flight::route( '/cls/*', function () {
        cls();
        Flight::redirect( Flight::request()->getBaseUrl() . '/admin/log', 200 );
    } );

    Flight::route( '/settings', function () {
        $sub = 'settings';

        Flight::set( 'current_sub', $sub );

        include_once __SUBS__ . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . '_sub.php';
        include_once __SUBS__ . DIRECTORY_SEPARATOR . '__render.php';

    } );

    Flight::route( 'POST /submit/settings', function (
    ) {
        $cookie = Flight::cookie();

        $formName        = Flight::request()->data['formName'];
        $widthIndex      = Flight::request()->data['widthIndex'] ?? '1080p';
        $setMenuPosition = Flight::request()->data['setMenuPosition'];

        $containerWidthValue = setBodyContainerWidth( $widthIndex );
        $minutes             = 30 * 60 * 60 * 24;

        setSecureCookie(
            'COOKIE_WIDTH_INDEX',
            $widthIndex,
            $minutes );
        setSecureCookie(
            'COOKIE_WIDTH_VALUE',
            $containerWidthValue,
            $minutes );
        setSecureCookie(
            'COOKIE_MENU_POSITION',
            $setMenuPosition,
            $minutes );

        Flight::redirect( Flight::request()->getBaseUrl() . '/admin/settings', 200 );
    } );

} ); //group

Flight::route( 'GET /hello', function () {
    echo '<h1>Welcome to the Flight Simple Example!</h1><h2>You are gonna do great things!</h2>';
} );

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
