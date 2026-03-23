<?php
###default_dpt.php

$page_message      = '';
$page_message_type = 'success';
$department        = $Departments[$current_dpt_index];
$department_sub    = $department['sub_departments'][$current_sub_index];

$admin_page          = $department_sub['admin_page'];
$table_name          = $department_sub['table_name'];
$columnsJsonFileName = $department_sub['columnsJsonFileName'];
$table_primaryKey    = $department_sub['table_primaryKey'];

$OK                 = 0;
$saveNewJsonColumns = false;
$dtColumns          = [];

if (!is_null($table_name))
{
    $dbTableFields     = db_getColumnNames($table_name);
    $dbTableFieldNames = array_keys($dbTableFields); // выводим в логг все названия полей
    $smarty->assign('dbTableFields', $dbTableFields);
    $smarty->assign('dbTableFieldNames', $dbTableFieldNames);

    if (!is_null($columnsJsonFileName))
    {
        if (file_exists($columnsJsonFileName))
        {
            $jsonColumns  = file_get_contents($columnsJsonFileName);
            $page_message = 'datatables columns loaded from: ' . $columnsJsonFileName . '';
        }
        else
        {

            $dtColumnFieldNames = $dbTableFieldNames;

            $jsonColumns       = exportColumnsToJson($dtColumnFieldNames, $limit = 4);
            $isSaved           = file_put_contents($columnsJsonFileName, $jsonColumns);
            $page_message      = $columnsJsonFileName . ' Is saved size: ' . format_size((int)$isSaved);
            $page_message_type = 'warning';
        }

        $dtColumns = json_decode($jsonColumns, true); //as array

        if (!is_null($table_primaryKey))
        {
            $dbTable    = $table_name;
            $primaryKey = $table_primaryKey;
            $OK         = 1;
        }
    }
}

if ($OK && isset($_GET['operation']))
{
    if ($_GET['operation'] === 'initDefaultDataTable')
    {
        global $pdo_connect;

        if ($OK)
        {
            $ssp_result = adminSSP::simple($_POST, $pdo_connect, $dbTable, $primaryKey, $dtColumns);
        }
        else
        {
            $ssp_result = [];
        }

        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($ssp_result));
    }

################
    ################
    if ($_GET['operation'] === 'editCell')
    {
        global $pdo_connect;

        $DATA   = json_decode(file_get_contents('php://input'), true, JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_IGNORE);
        $result = false;

        $columnIndex = $DATA['columnIndex'];
        $newValue    = $DATA['newValue'];
        $editID      = $DATA['editID'];

        $Column = [
            'ind'  => $dtColumns[$columnIndex]['ind'],
            'data' => $dtColumns[$columnIndex]['data'],
            'db'   => $dtColumns[$columnIndex]['db'],
            'dt'   => $dtColumns[$columnIndex]['dt'],
        ];

        if ($OK)
        {

            $where = [
                "{$primaryKey}" => $editID,
            ];

            $data = [
                "{$Column['db']}" => $newValue,

            ];

            $r = $db->table($dbTable)->where($where)->update($data);

            $page_message = 'CELL HAS BEEN EDITED';
        }

        header('Content-Type: application/json; charset=utf-8');

        exit(json_encode([
            'page_message' => $page_message,
            'table_name'   => $dbTable,
            'primaryKey'   => $primaryKey,
            'columnIndex'  => $columnIndex,
            'Column'       => $Column,
            'sql'          => $db->getQuery(),
            'query_count'  => $db->queryCount(),
            'phpDATA'      => $DATA, //as array
        ]));
        // 'sql'          => $sql,
        // "editedID"=> (int)$editedID,
    }

################
    ################
}

$smarty->assign('page_message', $page_message);
$smarty->assign('page_message_type', $page_message_type);

// d(array(
//     $admin_page,
//     $table_name,
//     $columnsJsonFileName,
//     $table_primaryKey
// ));
