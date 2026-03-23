<?php
####debug_decode.php
function value($variable)
{
    if (!isset($variable))
    {
        return 'undefined';
    }

    $res = '';
    if (is_null($variable))
    {
        $res .= 'NULL';
    }
    elseif (is_array($variable))
    {
        $res .= '<b>array</b>';
        $res .= '<ul>';
        foreach ($variable as $key => $value)
        {
            $res .= '<li>';
            $res .= '[ ' . value($key) . ' ]=' . value($value);
            $res .= '</li>';
        }
        $res .= '</ul>';
    }
    elseif (is_int($variable))
    {
        $res .= "<b>integer</b>\n";
        $res .= (string)$variable;
    }
    elseif (is_bool($variable))
    {
        $res .= "<b>bool</b>\n";
        if ($variable)
        {
            $res .= '<i>True</i>';
        }
        else
        {
            $res .= '<i>False</i>';
        }
    }
    elseif (is_string($variable))
    {
        $res .= "<b>string</b>\n";
        $res .= "'" . (string)$variable . "'";
    }
    elseif (is_float($variable))
    {
        $res .= "<b>float</b>\n";
        $res .= (string)$variable;
    }

    return $res;
}
function debug($variable)
{
    if (!isset($variable))
    {
        echo ('undefined');
    }
    else
    {
        echo '<div align="left">';
        echo (value($variable) . '<br>');
        echo '</div>';
    }
}
//убирает экранирующие слеши
function stripslashes_deep($value)
{
    $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);

    return $value;
}

function smartylog(
    $variable
)
{
    $fp = fopen(SMARTYDEBUGJSON, 'w+');
    fwrite($fp, json_encode($variable, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE) . "\r\n");
    fclose($fp);
}

function jlog(
    $text,
    $text2 = ''
)
{
    debugfile($text);
    if ($text2 != '')
    {
        debugfile($text2);
    }
    return true;
}

function consolelog(
    $variable,
    $gettype = 0
)
{
    $fp = fopen(FILEDEBUGJSON, 'a');
    fwrite($fp, '__' . gettype($variable) . "__  ::  \r\n");
    fwrite($fp, json_encode(xHtmlSpecialCharsDecode($variable), JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE) . "\r\n");
    fclose($fp);
}

function cls()
{
    // $t             = explode( " ", microtime() );
    // $err_timestamp = date( "Y-m-d H:i:s", $t[1] ) . substr( (string)$t[0], 1, 4 );
    $fp = fopen(FILEDEBUGJSON, 'w+');
    fwrite($fp, '>>>>>>>>>> ' . get_microtime() . " <<<<<<<<<<<<<<  \r\n");
    fclose($fp);
}

function directlog(
    $variable,
    $gettype = 0
)
{
    $fp = fopen(FILEDEBUGJSON, 'a');
    // fwrite( $fp, "__" . gettype( $variable ) . "__  ::  \r\n" );
    if ($gettype)
    {
        fwrite($fp, '__' . gettype($variable) . '__  :: ');
    }

    fwrite($fp, $variable . "\r\n");
    fclose($fp);
}

function debugfile(
    $variable,
    $remark = '',
    $showTime = false
)
{
    $fp = fopen(FILEDEBUGJSON, 'a');

    $remark = (strlen($remark) >= 1) ? json_encode(xHtmlSpecialCharsDecode($remark)) : false;

    // $Time   = ( $showTime ) ?  get_current_time() . " " . microtime( true ) : false;
    if ($showTime)
    {
        $err_timestamp = get_microtime();
    }

    if ($showTime)
    {
        fwrite($fp, "$err_timestamp :: ");
    }
    if ($remark)
    {
        fwrite($fp, "  $remark \r\n");
    }
    fwrite($fp, '__' . gettype($variable) . "__  ::  \r\n");
    fwrite($fp, json_encode($variable, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE));
    fwrite($fp, "\r\n");
    fclose($fp);
}

function xHtmlSpecialCharsDecode(
    $_Data,
    $_Params = [],
    $_Key = []
)
{
    if (is_null($_Data))
    {
        consolelog($_Data);
        return null;
    }

    if (!is_array($_Data))
    {
        if (is_string($_Data))
        {
            return html_entity_decode($_Data, ENT_QUOTES);
        }
        else
        {
            return '';
        }
    }

    if (!is_array($_Key))
    {
        $_Key = [$_Key];
    }

    foreach ($_Data as $__Key => $__Data)
    {
        if (count($_Key) && !is_array($__Data))
        {
            if (in_array($__Key, $_Key))
            {
                $_Data[$__Key] = xHtmlSpecialCharsDecode($__Data, $_Params, $_Key);
            }
        }
        else
        {
            $_Data[$__Key] = xHtmlSpecialCharsDecode($__Data, $_Params, $_Key);
        }
    }
    return $_Data;
}
