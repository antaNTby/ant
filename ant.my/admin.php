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
define( 'COPYRIGHT', 'antaNT64 ' . '<i class="bi bi-c-circle"></i> ' . date( 'Y' ) . ' Все права защищены.' );
define( 'BRANDNAME', '<span class="brandname-nix">nix </span><i class="bi bi-app-indicator" style="color:#cc0000;transform: rotate(90deg);"></i><span class="brandname-by">by</span>' );

require __VENDOR__ . DIRECTORY_SEPARATOR . 'autoload.php';

if ( file_exists( __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php' ) === false ) {
    Flight::halt( 500, 'Config file not found. Please create a config.php file in the app/config directory to get started.' );
}
$config = require __CONFIG__ . DIRECTORY_SEPARATOR . 'config.php';
// It is better practice to not use static methods for everything. It makes your
// app much more difficult to unit test easily.
// This is important as it connects any static calls to the same $app object
$app = Flight::app();

//🔹 Получаем логгер
$logger = $app->logger(); // Используем map() → теперь корректно
//🔹 Получаем логгер
$jlog = $app->jlog(); // Используем map() → теперь корректно

// 🔹 Проверяем и логируем
if ( !$logger or !$jlog ) {
    throw new Exception( 'Ошибка: логгер не зарегистрирован!' );
}

require __CONFIG__ . DIRECTORY_SEPARATOR . 'services.php';

// Whip out the ol' router and we'll pass that to the routes file
$router = $app->router();
require __CONFIG__ . DIRECTORY_SEPARATOR . 'routes.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'run.php';
