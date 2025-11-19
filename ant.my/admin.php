<?php
################################################################################
################################################################################
################################################################################
################################################################################
###########                A D M I N . P H P            ########################
################################################################################
################################################################################
################################################################################
################################################################################

define( '__PARENT_DIR__', dirname( __DIR__, 1 ) );
define( '__ROOT__', __DIR__ );
define( '__PUBLIC__', __ROOT__ . DIRECTORY_SEPARATOR . 'public' );

define( '__APP__', __ROOT__ . DIRECTORY_SEPARATOR . 'app' );
define( '__VENDOR__', __ROOT__ . DIRECTORY_SEPARATOR . 'vendor' );
define( '__CONFIG__', __APP__ . DIRECTORY_SEPARATOR . 'config' );
define( '__CONTROLLERS__', __APP__ . DIRECTORY_SEPARATOR . 'controllers' );
define( '__TPL__', __APP__ . DIRECTORY_SEPARATOR . 'tpl' );
define( 'SERVER_NAME', $_SERVER['SERVER_NAME'] );
/*
array
[ '__PARENT_DIR__' ]='C:\git\ant'
[ '__ROOT__' ]='C:\git\ant\ant.my'
[ '__PUBLIC__' ]='C:\git\ant\ant.my\public'
[ '__APP__' ]='C:\git\ant\ant.my\app'
[ '__VENDOR__' ]='C:\git\ant\ant.my\vendor'
[ '__CONFIG__' ]='C:\git\ant\ant.my\app\config'
[ '__CONTROLLERS__' ]='C:\git\ant\ant.my\app\controllers'
[ '__TPL__' ]='C:\git\ant\ant.my\app\templates'
[ 'SERVER_NAME' ]='ant.my'
*/

// $PATHES = [
//     '__PARENT_DIR__'  => __PARENT_DIR__,
//     '__ROOT__'        => __ROOT__,
//     '__PUBLIC__'      => __PUBLIC__,
//     '__APP__'         => __APP__,
//     '__VENDOR__'      => __VENDOR__,
//     '__CONFIG__'      => __CONFIG__,
//     '__CONTROLLERS__' => __CONTROLLERS__,
//     '__TPL__'         => __TPL__,
//     'SERVER_NAME'     => SERVER_NAME,

// ];
// debug( $PATHES );

require __VENDOR__ . DIRECTORY_SEPARATOR . 'autoload.php';

if ( file_exists( __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php' ) === false ) {
    Flight::halt( 500, 'Config file not found. Please create a config.php file in the app/config directory to get started.' );
}
// It is better practice to not use static methods for everything. It makes your
// app much more difficult to unit test easily.
// This is important as it connects any static calls to the same $app object
$app = Flight::app();

$config = require __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'services.php';

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();
require __CONFIG__ . DIRECTORY_SEPARATOR . 'routes.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'run.php';
