<?php
##############################################
Flight::group( '/admin', function () {
// Определение маршрута

    Flight::route( 'GET /sub/@sub/show_all(/sort/@sortField(/@sortDirection))', function (

        $sub,
        $sortField,
        $sortDirection

    ) {

        if ( !isCorrectFlightItem( $sub, 'arrSubs' ) ) {
            throw new Exception( 'Неправильная страница' );
            Flight::notFound();
        }

        Flight::set( 'current_sub', $sub );
        Flight::set( 'current_page', -1 );
        Flight::set( 'items_per_page', -1 );
        Flight::set( 'sort_field', $sortField ?? 'sort_order' );
        Flight::set( 'sort_direction', $sortDirection );

        include_once __SUBS__ . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . '_sub.php';
        include_once __SUBS__ . DIRECTORY_SEPARATOR . '__render.php';

    } );

    Flight::route( 'GET /sub/@sub(/page/@page(/limit/@lim))(/sort/@sortField(/@sortDirection))', function (

        $sub,
        $page,
        $lim,
        $sortField,
        $sortDirection

    ) {
        if ( !isCorrectFlightItem( $sub, 'arrSubs' ) ) {
            throw new Exception( 'Неправильная страница' );
            Flight::notFound();
        }

        Flight::set( 'current_sub', $sub );
        Flight::set( 'current_page', $page );
        Flight::set( 'items_per_page', $lim ?? DEFAULT_ITEMS_PER_PAGE );
        Flight::set( 'sort_field', $sortField );
        Flight::set( 'sort_direction', $sortDirection );

        include_once __SUBS__ . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . '_sub.php';
        include_once __SUBS__ . DIRECTORY_SEPARATOR . '__render.php';

    } );

} ); // /admin
