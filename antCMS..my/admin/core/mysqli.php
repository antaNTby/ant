<?php
function db_gmts()
{
    $microtime = microtime(true);
    $sec       = floor($microtime);
    $usec      = fmod($microtime, 1);

    // list($usec, $sec) = explode(' ', microtime(true));
    return $usec + $sec;
}

### create connection
function db_connect(
    $host,
    $user,
    $pass,
    $db
)
{
    global $DB;
    // $DB->connect($host, $user, $pass, $db);
    // $r = true; //mysql_connect($host,$user,$pass);
    // return $r;

    return $DB->connect($host, $user, $pass, $db);
}

### close connection
function db_disconnect()
{
    global $DB;
    return mysqli_close($DB->link);
}

### select database
function db_select_db(
    $link,
    $name
)
{
    global $DB;
    return mysqli_select_db($link, $name);
}

//database query
function db_query(
    $s,
    $print = false,
    $speedtest = false
)
{
    global $sc_4, $sc_8, $gmc;
    global $DB;

    if (isset($gmc) && $gmc == 1)
    {
        $sc_81 = db_gmts();
    }

    $scriptv = db_gmts();
    $res     = [];
    if ($speedtest)
    {
        global $relaccess;
    }

    $start = db_gmts();

    #### САМ ЗАПРОС
    $res['resource'] = $DB->Query($s, $print);
    #### САМ ЗАПРОС

    if ($speedtest)
    {
        if (in_array(100, $relaccess))
        {
            list($usec1, $sec1) = explode(' ', microtime(true));
            $f                  = fopen(SQLDEBUG, 'a');
            fwrite($f, round($usec1 + $sec1 - $start, 5) . '/' . $s . "\n");
            fclose($f);}
    }

    $scriptp   = db_gmts();
    $queryTime = $scriptp - $scriptv;

    if (($res['resource'] === false) or $speedtest)
    {
        $out = [
            'ERR_NO' => mysqli_errno($DB->link),
            'SQL'    => $s,
            'ERROR'  => mysqli_error($DB->link),
            'Link'   => str_replace('/', '\\', $_SERVER['REQUEST_URI']),
            'Date'   => date('Y-m-d h:i:s'),

        ];
        if ($speedtest)
        {
            $out['speedtest'] = $queryTime;
        }

        $Dump = var_export($_REQUEST, true);

        $fp = fopen('log/SQLDEBUG.JSON', 'a');
        fwrite($fp, json_encode($out, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE));
        fwrite($fp, "\r\n");
        fwrite($fp, $Dump);
        fclose($fp);

        // die('Wrong database query!');
    }

    $res['columns'] = [];
    $column_index   = 0;

    if (!is_bool($res["resource"]))
    {
        while ($xwer = @mysqli_fetch_field($res["resource"]))
        {
            $res['columns'][$xwer->name] = $column_index;
            $column_index++;
        }
    }

    if (isset($gmc) && $gmc == 1)
    {
        $sc_82 = db_gmts();
        $sc_4++;
        $sc_8 = $sc_8 + $sc_82 - $sc_81;
    }
    // dump($res);
    return $res;
}

function db_multiquery(
    $chunk,
    $DB_HOST = DB_HOST,
    $DB_USER = DB_USER,
    $DB_PASS = DB_PASS,
    $DB_NAME = DB_NAME,
    $AUTOCLOSE = 0
)
{
// console(  $chunk );
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    // $mysqli->set_charset('utf8mb3');
    $mysqli->set_charset( "utf8mb4" );
    if (mysqli_connect_errno())
    {
        consolelog('db_multiquery :: connect_error: ' . mysqli_connect_error());
        // printf("xEscSQL :: Не удалось подключиться: %s\n", mysqli_connect_error());
        // $mysqli->close();
        exit();
    }

    $chunk = RN($chunk);
    $query = trim($chunk);

    $result = $mysqli->multi_query($query);

    if ($AUTOCLOSE)
    {
        ## /* закрываем соединение */
        $mysqli->close();
    }
    return $result;
}

### row fetching
function db_fetch_row($q)
{
    $res = mysqli_fetch_row($q['resource']);
    if ($res)
    {
        foreach ($q['columns'] as $column_name => $column_index)
        {
            $res[$column_name] = $res[$column_index];
        }
    }
    return $res;
}

###id of last inserted record
function db_insert_id($gen_name = '')
{
    return mysqli_insert_id($DB->link);
}

###id of last inserted record
function db_affected_rows($gen_name = '')
{
    global $DB;
    return mysqli_affected_rows($DB->link);
}

### database error message
function db_error()
{
    return mysqli_error($DB->link);
}

function db_get_all_tables()
{
    $q   = db_query('show tables');
    $res = [];
    while ($row = db_fetch_row($q))
    {
        $res[] = strtolower($row[0]);
    }

    return $res;
}

function db_get_all_ss_tables($xmlFileName)
{
    $res               = [];
    $tables            = db_get_all_tables();
    $xmlNodeTableArray = GetXmlTableNodeArray($xmlFileName);
    foreach ($xmlNodeTableArray as $xmlNodeTable)
    {
        $attr      = $xmlNodeTable->GetXmlNodeAttributes();
        $existFlag = false;
        foreach ($tables as $tableName)
        {
            if (strtolower($attr['NAME']) == $tableName)
            {
                $existFlag = true;
            }
        }
        if ($existFlag)
        {
            $res[] = $attr['NAME'];
        }
    }
    return $res;
}

function db_delete_table($tableName)
{
    db_query('DROP table ' . $tableName);
}

function db_delete_all_tables()
{
    $tableArray = db_get_all_tables();
    foreach ($tableArray as $tableName)
    {
        db_query('DROP table ' . $tableName);
    }
}

function db_add_column(
    $tableName,
    $columnName,
    $type,
    $default,
    $nullable
)
{
    if ($nullable)
    {
        $nullableStr = ' NULL ';
    }
    else
    {
        $nullableStr = ' NOT NULL ';
    }

    if ($default != null)
    {
        db_query('alter table ' . $tableName . ' add column ' . $columnName . " $type " . $nullableStr .
            ' default ' . $default);
    }
    else
    {
        db_query('alter table ' . $tableName . ' add column ' . $columnName . " $type " . $nullableStr);
    }
}

function db_rename_column(
    $tableName,
    $oldColumnName,
    $newColumnName,
    $type,
    $default,
    $nullable
)
{
    if ($nullable)
    {
        $nullableStr = ' NULL ';
    }
    else
    {
        $nullableStr = ' NOT NULL ';
    }

    if ($default != null)
    {
        db_query('alter table ' . $tableName . ' change ' . $oldColumnName . ' ' .
            $newColumnName . ' ' . $type . ' ' . $nullableStr . ' default ' . $default);
    }
    else
    {
        db_query('alter table ' . $tableName . ' change ' . $oldColumnName . ' ' .
            $newColumnName . ' ' . $type . ' ' . $nullableStr);
    }
}

function db_delete_column(
    $tableName,
    $columnName
)
{
    db_query('alter table ' . $tableName . ' drop column ' . $columnName);
}

function db_getColumns(
    $_TableName,
    $lowerCase = false
)
{
    $Columns = [];
    $sql     = '
                SHOW COLUMNS FROM `' . $_TableName . '`
        ';
    $Result = db_query($sql);
    if (!db_num_rows($Result['resource']))
    {
        return $Columns;
    }
    while ($_Row = db_fetch_row($Result))
    {
        ($lowerCase)
        ? $Columns[strtolower($_Row['Field'])] = $_Row
        : $Columns[$_Row['Field']]             = $_Row;
    }
    return $Columns;
}

function db_getColumnNames(
    $_TableName,
    $lowerCase = false
)
{
    $Columns = [];
    $sql     = '
                SHOW COLUMNS FROM `' . $_TableName . '`
        ';
    $Result = db_query($sql);
    if (!db_num_rows($Result['resource']))
    {
        return $Columns;
    }
    while ($_Row = db_fetch_row($Result))
    {
        ($lowerCase)
        ? $Columns[strtolower($_Row['Field'])] = $_Row['Type']
        : $Columns[$_Row['Field']]             = $_Row['Type'];
    }
    return $Columns;
}

function db_num_rows($_result)
{
    return mysqli_num_rows($_result);
}

function db_get_server_info()
{
    global $DB;
    $res = mysqli_get_server_info($DB->link);
    return $res;
}

function db_real_escape_string($value)
{
    // $res = mysqli_real_escape_string( $DB->link, $value );
    global $DB;
    $res = $DB->escape($value);
    return $res;
}

function db_phquery()
{
    $args = func_get_args();
    $tmpl = array_shift($args);
    $sql  = sql_placeholder_ex($tmpl, $args, $error);
    if ($sql === false)
    {
        $sql = PLACEHOLDER_ERROR_PREFIX . $error;
    }

    return db_query($sql);
}

function db_fetch_assoc($Result)
{
    return mysqli_fetch_assoc($Result['resource']);
}

function db_data_seek($Result)
{
    return mysqli_data_seek($Result['resource']);
}

// Open phpmyadmin and goto 'More' Tab and select 'Variables' submenu. Scroll down to find sql mode. Edit sql mode and remove 'STRICT_TRANS_TABLES' Save it.
//Изменить    innodb_strict_mode  на    OFF

// Конфигурация MySQL
// Заходим в меню OSPanel: Дополнительно -> Конфигруация -> MySQL_X.X .
// Ищем строку [mysqld] и после нее вставляем sql_mode = "" ,
// затем ищем строку # InnoDB Settings
// и вставляем после неё innodb_strict_mode = OFF

// [mysqld]
// sql_mode = ""

// # InnoDB Settings
// innodb_strict_mode = OFF
