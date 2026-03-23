<?php
### ФУНКЦИИ
include_once 'functions/crypto_functions.php';
include_once 'functions/registration_functions.php';
include_once 'functions/session_functions.php';
include_once 'functions/debug_functions.php';
include_once 'functions/admin_functions.php';
include_once 'functions/adminUI_functions.php';

function php_gd()
{
    ob_start();
    phpinfo(8);
    $module_info = ob_get_contents();
    ob_end_clean();
    if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches))
    {
        $gdversion = $matches[1];
    }
    else
    {
        $gdversion = 0;
    }
    return $gdversion;
}

function db_version()
{
    list($dbversion) = db_fetch_row(db_query('SELECT VERSION()'));
    return $dbversion;
}

function get_microtime()
{
    $t         = explode(' ', microtime());
    $timestamp = date('Y-m-d H:i:s', $t[1]) . substr((string)$t[0], 1, 4);
    return $timestamp;
}

function gmts()
{
                                                   ## list($usec, $sec) = explode(' ', microtime(true)); // php 7.4
    list($usec, $sec) = explode(' ', microtime()); // php 8.1
    return $usec + $sec;
}

function fix_directory_separator($str)
{

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        $str = str_replace('/', DIRECTORY_SEPARATOR, $str);
    }
    else
    {
        $str = str_replace('\\', DIRECTORY_SEPARATOR, $str);
    }

    return $str;
}

function key_compare_func(
    $key1,
    $key2
)
{
    if ($key1 == $key2)
    {
        return 0;
    }
    elseif ($key1 > $key2)
    {
        return 1;
    }
    else
    {
        return -1;
    }
}

/**
 * Форматированный вывод размера
 */
function format_size($file_size)
{
    if ($file_size >= 1073741824)
    {
        $file_size = round($file_size / 1073741824 * 100) / 100 . ' Gb';
    }
    elseif ($file_size >= 1048576)
    {
        $file_size = round($file_size / 1048576 * 100) / 100 . ' Mb';
    }
    elseif ($file_size >= 1024)
    {
        $file_size = round($file_size / 1024 * 100) / 100 . ' Kb';
    }
    else
    {
        $file_size = $file_size . ' b';
    }

    return $file_size;
}

/**
 * Размер дириктории
 */
function get_dir_size($directory)
{
    if ( ! is_dir($directory))
    {
        return 0;
    }

    $size = 0;

    if ($DIR = opendir($directory))
    {
        while (($dirfile = readdir($DIR)) !== false)
        {
            if (@is_link($directory . '/' . $dirfile) || $dirfile == '.' || $dirfile == '..')
            {
                continue;
            }

            if (@is_file($directory . '/' . $dirfile))
            {
                $size += filesize($directory . '/' . $dirfile);
            }
            elseif (@is_dir($directory . '/' . $dirfile))
            {
                $dirSize = get_dir_size($directory . '/' . $dirfile);

                if ($dirSize >= 0)
                {
                    $size += $dirSize;
                }
                else
                {
                    return -1;
                }
            }
        }

        closedir($DIR);
    }

    return $size > 0 ? $size : 0;
}

function get_mime_type($file)
{
    $file_extension = strtolower(mb_substr(strrchr($file, '.'), 1));

    switch ($file_extension)
    {
        case 'psd':
            $ctype = 'image/x-photoshop';
            break;
        case 'rar':
            $ctype = 'application/x-rar-compressed';
            break;
        case 'zip':
            $ctype = 'application/x-zip-compressed';
            break;
        case 'pdf':
            $ctype = 'application/pdf';
            break;
        case 'bz2':
            $ctype = 'application/bzip2';
            break;
        case 'doc':
        case 'dot':
        case 'wiz':
        case 'wzs':$ctype = 'application/msword';
            break;
        case 'eps':
            $ctype = 'application/postscript';
            break;
        case 'pot':
        case 'ppa':
        case 'pps':
        case 'ppt':
        case 'pwz':
            $ctype = 'application/vnd.ms-powerpoint';
            break;
        case 'rtf':
            $ctype = 'application/rtf';
            break;
        case 'rnx':
            $ctype = 'application/vnd.rn-realmedia';
            break;
        case 'hlp':
            $ctype = 'hlp';
            break;
        case 'gtar':
            $ctype = 'application/x-gtar';
            break;
        case 'gzip':
        case 'tgz':$ctype = 'application/x-gzip';
            break;
        case 'lnx':
            $ctype = 'application/x-latex';
            break;
        case 'exe':
            $ctype = 'application/x-msdownload';
            break;
        case 'swf':
            $ctype = 'application/x-shockwafe-flash';
            break;
        case 'xml':
            $ctype = 'application/xml';
            break;
        case 'midi':
            $ctype = 'audio/midi';
            break;
        case 'mp3':
        case 'mp2':
        case 'mpga':
            $ctype = 'audio/mpeg';
            break;
        case 'wav':
            $ctype = 'audio/wav';
            break;
        case 'bmp':
            $ctype = 'audio/wav';
            break;
        case 'gif':
            $ctype = 'image/gif';
            break;
        case 'jpeg':
        case 'jpg':
        case 'jpe':
            $ctype = 'image/jpeg';
            break;
        case 'png':
            $ctype = 'image/png';
            break;
        case 'tif':
        case 'tiff':$ctype = 'image/tiff';
            break;
        case 'ico':
            $ctype = 'image/x-icon';
            break;
        case 'csv':
            $ctype = 'text/comma-separated-values';
            break;
        case 'css':
            $ctype = 'text/css';
            break;
        case 'htm':
        case 'html':
        case 'shtml':
            $ctype = 'text/html';
            break;
        case 'txt':
        case 'klp':
        case 'tex':
        case 'php':
        case 'asp':
        case 'aspx':
        case 'php3':
        case 'php4':
        case 'php5':
        case 'sql':$ctype = 'text/plain';
            break;
        case 'xml':
            $ctype = 'text/xml';
            break;
        case 'xhtm':
            $ctype = 'text/xhtml';
            break;
        case 'wml':
            $ctype = 'text/wml';
            break;
        case 'mpeg':
        case 'mpg':
        case 'mpe':
        case 'mlv':
        case 'mpa':
        case 'wma':
        case 'wmv':
            $ctype = 'video/mpeg';
            break;
        case 'avi':
            $ctype = 'video/x-msvideo';
            break;
        case 'mov':
            $ctype = 'video/quicktime';
            break;
        case 'xls':
            $ctype = 'application/vnd.ms-excel';
            break;
        case 'ai':
            $ctype = 'application/postscript';
            break;
        case 'rm':
            $ctype = 'application/vnd.rn-realmedia';
            break;
        case 'gz':
            $ctype = 'application/x-gzip';
            break;
        case 'js':
            $ctype = 'application/x-javascript';
            break;
        case 'pl':
        case 'cc':$ctype = 'text/plain';
            break;
        case 'qt':
            $ctype = 'video/quicktime';
            break;
        default:$ctype = 'application/force-download';
    }

    return $ctype;
}

function file_download(
    $filename,
    $retbytes = true
)
{
    $chunksize = 1 * (1024 * 1024);
    $buffer    = '';
    $cnt       = 0;

    $handle = fopen($filename, 'rb');

    if ($handle === false)
    {
        return false;
    }
    while ( ! feof($handle))
    {
        $buffer = fread($handle, $chunksize);

        echo $buffer;

        flush();

        if ($retbytes)
        {
            $cnt += strlen($buffer);
        }
    }

    $status = fclose($handle);

    if ($retbytes && $status)
    {
        return $cnt;
    }

    return $status;
}

function is_php_code($check_code)
{
    $check_code = stripslashes($check_code);
    $check_code = str_replace(' ', '', $check_code);
    $check_code = strtolower($check_code);

    if (strpos($check_code, '<?php') !== false ||
        strpos($check_code, '<?') !== false ||
        strpos($check_code, '<? ') !== false ||
        strpos($check_code, '<?=') !== false ||
        strpos($check_code, '<script language="php">') !== false ||
        strpos($check_code, 'language="php"') !== false ||
        strpos($check_code, "language='php'") !== false ||
        strpos($check_code, 'language=php') !== false)
    {
        return true;
    }

    return false;
}

function check_permission_acp($perm)
{
    if ( ! check_permission($perm))
    {
        if ( ! defined('NOPERM'))
        {
            define('NOPERM', 1);
        }

        return false;
    }

    return true;
}

function pluck(
    $a,
    $prop
)
{
    $out = [];
    for ($i = 0, $len = count($a); $i < $len; $i++)
    {
        $out[] = $a[$i][$prop];
    }
    return $out;
}

function idxCol(
    $columns,
    $needle,
    $prop = "dt"
)
{
    $keys  = pluck($columns, $prop);
    $index = 0;
    if (($i = array_search($needle, ($keys))) !== false)
    {
        $index = $i;
    }
    return $index;
}

function translitText($str)
{
    $tr = [
        "А" => "A", "Б"  => "B", "В"   => "V", "Г"  => "G",
        "Д" => "D", "Е"  => "E", "Ё"   => "E", "Ж"  => "J", "З"   => "Z", "И" => "I",
        "Й" => "Y", "К"  => "K", "Л"   => "L", "М"  => "M", "Н"   => "N",
        "О" => "O", "П"  => "P", "Р"   => "R", "С"  => "S", "Т"   => "T",
        "У" => "U", "Ф"  => "F", "Х"   => "H", "Ц"  => "TS", "Ч"  => "CH",
        "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы"   => "YI", "Ь"  => "",
        "Э" => "E", "Ю"  => "YU", "Я"  => "YA", "а" => "a", "б"   => "b",
        "в" => "v", "г"  => "g", "д"   => "d", "е"  => "e", "ё"   => "e", "ж" => "j",
        "з" => "z", "и"  => "i", "й"   => "y", "к"  => "k", "л"   => "l",
        "м" => "m", "н"  => "n", "о"   => "o", "п"  => "p", "р"   => "r",
        "с" => "s", "т"  => "t", "у"   => "u", "ф"  => "f", "х"   => "h",
        "ц" => "ts", "ч" => "ch", "ш"  => "sh", "щ" => "sch", "ъ" => "y",
        "ы" => "yi", "ь" => "", "э"    => "e", "ю"  => "yu", "я"  => "ya",
    ];
    return strtr($str, $tr);
}
