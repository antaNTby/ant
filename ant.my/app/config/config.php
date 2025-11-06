<?php
/**********************************************
 *         Application Environment            *
 **********************************************/
// Set your timezone (e.g., 'America/New_York', 'UTC')
date_default_timezone_set( 'UTC' );

// Error reporting level (E_ALL recommended for development)
error_reporting( E_ALL );

// Character encoding
if ( function_exists( 'mb_internal_encoding' ) === true ) {
    mb_internal_encoding( 'UTF-8' );
}

// Default Locale Change as needed or feel free to remove.
if ( function_exists( 'setlocale' ) === true ) {
    setlocale( LC_ALL, 'en_US.UTF-8' );
}

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_flight.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_smarty.php';

require __CONFIG__ . DIRECTORY_SEPARATOR . 'config_monolog.php';
