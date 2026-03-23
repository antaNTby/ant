<?php
#### antDB.php
class antDataBase
{
    static $link;
    static $count = 0;

    public static function connect(
        $host,
        $user,
        $pass,
        $db
    )
    {
        @self::$link = mysqli_connect($host, $user, $pass, $db)
        or exit('No connect (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
        // mysqli_set_charset(self::$link, 'utf8');
        mysqli_set_charset(self::$link, 'utf8mb4');
        return self::$link;
    }

    public static function escape($data)
    {
        if (is_array($data))
        {
            $data = array_map('self::escape', $data);
        }
        else
        {
            // mysqli_set_charset(self::$link, 'utf8');
            mysqli_set_charset(self::$link, 'utf8mb4');
            $data = mysqli_real_escape_string(self::$link, $data);
        }

        return $data;
    }

    public static function Query(
        $sql,
        $print = false
    )
    {
        self::$count++;

        $result = mysqli_query(self::$link, $sql);
        // $result = mysqli_query(self::$link, $sql) or die( mysqli_error(self::$link));

        if ($result === false || $print === 1)
        {

            $error = mysqli_error(self::$link);
            $trace = debug_backtrace();

            $out = [1 => ''];

            if (!empty($error))
            {
                preg_match("#'(.+?)'#is", $error, $out);
            }

            $error_date = date('Y-m-d h:i:s');
            $head       = $error ? "MySQL error: <br>" . $error : null;
            $errQuery   = str_replace($out[1], '<strong style="color:steelblue">' . $out[1] . '</strong>', $trace[0]['args'][0]);
            $error_log  = <<<HTML
<!DOCTYPE html>
!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MySQL error</title>
</head>
<body>

<pre style="background-color:#333; color:#fff; padding:10px;">
{$error_date}
<em style="font-size:70%; color:silver;">{$head}</em>
Query:
<strong style="color:red;">{$errQuery}</strong>
sql:
<strong style="color:darksalmon;">{$sql}</strong>
File:
<strong style="color:#1299DA;">{$trace[0]['file']}:{$trace[0]['line']}</strong> @Line: <strong>{$trace[0]['line']}</strong>
</pre>
<pre style="background-color:#18171B; color:#fff; padding:10px;">Error Trace:</pre>
</body>
</html>
HTML;
            echo ($error_log);
            dd($trace); //symphony
        }

        return $result;
    }
}
