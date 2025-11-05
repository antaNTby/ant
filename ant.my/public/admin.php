<?php

define( '__APP__', __ROOT__ . DIRECTORY_SEPARATOR . 'app' );
define( '__VENDOR__', __ROOT__ . DIRECTORY_SEPARATOR . 'vendor' );

define( '__CONFIG__', __APP__ . DIRECTORY_SEPARATOR . 'config' );
define( '__CONTROLLERS__', __APP__ . DIRECTORY_SEPARATOR . 'controllers' );
define( '__TPL__', __APP__ . DIRECTORY_SEPARATOR . 'templates' );

require __VENDOR__ . DIRECTORY_SEPARATOR . 'autoload.php';

debug( [
    '__PARENT_DIR__'  => __PARENT_DIR__,
    '__ROOT__'        => __ROOT__,
    '__PUBLIC__'      => __PUBLIC__,
    '__APP__'         => __APP__,
    '__VENDOR__'      => __VENDOR__,
    '__CONFIG__'      => __CONFIG__,
    '__CONTROLLERS__' => __CONTROLLERS__,
    '__TPL__'         => __TPL__,

] );
