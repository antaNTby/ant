<?php

Flight::set( 'LOG_REQUEST_TIME', false );

// At this point, your app should have all the instructions it needs and it'll
// "start" processing everything. This is where the magic happens.
Flight::start();
/*
 .----..---.  .--.  .----.  .---.     .---. .-. .-.  .--.  .---.    .----. .-. .-..----. .----..-.  .-.
{ {__ {_   _}/ {} \ | {}  }{_   _}   {_   _}| {_} | / {} \{_   _}   | {}  }| { } || {}  }| {}  }\ \/ /
.-._} } | | /  /\  \| .-. \  | |       | |  | { } |/  /\  \ | |     | .--' | {_} || .--' | .--'  }  {
`----'  `-' `-'  `-'`-' `-'  `-'       `-'  `-' `-'`-'  `-' `-'     `-'    `-----'`-'    `-'     `--'
*/

// dump( $app );
// dump( $logger );

// dump( $app );
// dump( $config );

// Get the $app var to use below
if ( empty( $app ) === true ) {
    $app = Flight::app();
}

//🔹 Получаем логгер
$logger = Flight::logger(); // Используем map() → теперь корректно
//🔹 Получаем логгер
$jlog = Flight::jlog(); // Используем map() → теперь корректно

// 🔹 Проверяем и логируем
if ( !$logger ) {
    throw new Exception( 'Ошибка: логгер не зарегистрирован!' );
}
