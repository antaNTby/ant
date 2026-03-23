<?php

function isCorrectFlightItem(
    string $val,
    string $arrName
): bool {
    return in_array( strtolower( $val ), Flight::get( $arrName ) );
}

function setBodyContainerWidth( string $index = '1080p' )
{
    $widths = [
        '720p'  => 1250,
        '1080p' => 1860,
        '1440p' => 2500,
        '4k'    => 3700,
    ];
    $heights = [
        '720p'  => 720,
        '1080p' => 1080,
        '1440p' => 1440,
        '4k'    => 2560,
    ];
    $newWidth  = $widths[$index] ?? 1860;
    $newHeight = $heights[$index] ?? 1080;
    // $newHeight =  'min-height: 100vh;max-height: 100vh;height: 100vh;' ;

    Flight::set( 'body_height', "max-width:{$newWidth}px; width:{$newWidth}px; min-width:{$newWidth}px;" );
    Flight::set( 'body_width', 'min-height: 100vh;max-height: 100vh;height: 100vh;' );

    return $newWidth;
}

// function __rulesSmartyControl( array $rules ): object
// {
//     $res = (object) array_merge( [
//         'controlType'   => '',
//         'font_style'    => '',
//         'group_index'   => null,
//         'indeterminate' => null,
//         'inline'        => null,
//         'label'         => '0/1',
//         'positive_only' => 0,
//         'readonly'      => 0,
//         'reverse'       => null,
//         'string_format' => '%.2f',
//         'text_align'    => '',
//         'key'           => 'key',
//     ], $rules );
//     // jlog( $res );

//     return $res;
// }

function monolog(
    string $message = '',
    string $style = 'info'
): void {

    if ( !is_array( $arr ) ) {
        $arr = [$arr];
    }

    switch ( $style ) {
        case 'error':
            // code...
            Flight::logger()->error( $message );
            break;
        case 'debug':
            // code...
            Flight::logger()->debug( $message );
            break;
        case 'info':
            // code...
            Flight::logger()->info( $message );
            break;

        default:
            // code...
            Flight::logger()->info( $message );
            break;
    }
}

function cls(
): void {

    $logContent = file_get_contents( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log' );

    file_put_contents( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.backup', $logContent ); // Перезаписываем файл пустым
    file_put_contents( __ADMIN__ . DIRECTORY_SEPARATOR . 'monolog.log', '' );             // Перезаписываем файл пустым
}

function pluck(
    $a,
    $prop
) {
    $out = [];
    for ( $i = 0, $len = count( $a ); $i < $len; $i++ ) {
        $out[] = $a[$i][$prop];
    }

    return $out;
}

/**
 * Форматированный вывод размера
 */
function format_size( $file_size )
{
    if ( $file_size >= 1073741824 ) {
        $file_size = round( $file_size / 1073741824 * 100 ) / 100 . ' Gb';
    } elseif ( $file_size >= 1048576 ) {
        $file_size = round( $file_size / 1048576 * 100 ) / 100 . ' Mb';
    } elseif ( $file_size >= 1024 ) {
        $file_size = round( $file_size / 1024 * 100 ) / 100 . ' Kb';
    } else {
        $file_size = $file_size . ' b';
    }

    return $file_size;
}

function formatUsd(
    $price = 1,
    $rval = 4,
    $decimalSeparator = '.',
    $thousandsSeparator = '',
    $unitSymbol = ''
) {
    return $unitSymbol . _formatPrice( $price, $rval );
}

function _formatPrice(
    $price,
    $rval = 2,
    $decimalSeparator = '.',
    $thousandsSeparator = ''
) {
    // Проверка входных данных
    if ( !is_numeric( $price ) ) {
        return 1.0000;
    }

    return number_format( round( $price, $rval ), $rval, $decimalSeparator, $thousandsSeparator );
}

function zeroPad(
    $number,
    $length = 2,
    $symbol = '0',
    $prefix = '',
    $tail = ''
) {
    return $prefix . str_pad( $number, $length, $symbol, STR_PAD_LEFT ) . $tail;
}
// echo zeroPad( 23, 5, '.', '#', ' ---> ' );

/*function formatUsd(
	$price = 1,
	$UnitSymbol = '',
	$rval = 4
) {
	$res = _formatPrice( $price, $rval, '.', '' );

	return $UnitSymbol . $res;
}

// function _formatPrice( $price, $rval = 2, $decimalSeparator = '.', $term = ' ' ) {
function _formatPrice(
	$price,
	$rval = 2,
	$decimalSeparator = '.',
	$thousands_separator = ''
) {
	if ( !is_numeric( $price ) ) {
		return 'Ошибка: неверное число';
	}

	return number_format( round( $price, $rval ), $rval, $decimalSeparator, $thousands_separator );
}
*/

function russianSoundex( $word )
{
    $map = [
        'А' => '0', 'Б' => '1', 'В' => '1', 'Г' => '2', 'Д' => '3',
        'Е' => '0', 'Ё' => '0', 'Ж' => '4', 'З' => '5', 'И' => '0',
        'Й' => '0', 'К' => '2', 'Л' => '6', 'М' => '7', 'Н' => '7',
        'О' => '0', 'П' => '1', 'Р' => '9', 'С' => '8', 'Т' => '3',
        'У' => '0', 'Ф' => '1', 'Х' => '2', 'Ц' => '5', 'Ч' => '4',
        'Ш' => '4', 'Щ' => '4', 'Ъ' => '0', 'Ы' => '0', 'Ь' => '0',
        'Э' => '0', 'Ю' => '0', 'Я' => '0',
    ];

    // Приводим строку к верхнему регистру и проверяем кодировку
    $word        = mb_strtoupper( trim( $word ), 'UTF-8' );
    $firstLetter = mb_substr( $word, 0, 1, 'UTF-8' );
    $soundexCode = $firstLetter; // Первая буква остается

    for ( $i = 1; $i < mb_strlen( $word, 'UTF-8' ); $i++ ) {
        $letter  = mb_substr( $word, $i, 1, 'UTF-8' );
        if ( isset( $map[$letter] ) ) {
            $code  = $map[$letter];
            // Исключаем повторяющиеся цифры
            if ( $code !== substr( $soundexCode, -1 ) ) {
                $soundexCode .= $code;
            }
        }
    }

    // Ограничиваем код до 4 символов

    return mb_substr( $soundexCode, 0, 4, 'UTF-8' );
}

/*// Примеры использования
echo russianSoundex( 'ёлочка' ) . '<br>';   // Выведет: Е070
echo russianSoundex( 'йолочка' ) . '<br>'; // Выведет: Й070

// Пример использования
echo russianSoundex( 'папа' ) . '<br>'; // Выведет: Е070
echo russianSoundex( 'мама' ) . '<br>'; // Выведет: Й070
*/
