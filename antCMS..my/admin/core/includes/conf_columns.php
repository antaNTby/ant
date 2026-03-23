<?php
###default_dpt.php

$page_message      = '';
$page_message_type = 'success';
$department        = $Departments[$current_dpt_index];
$department_sub    = $department['sub_departments'][$current_sub_index];

$smarty->assign('page_message', $page_message);
$smarty->assign('page_message_type', $page_message_type);
$smarty->assign('php_department', $department);
$smarty->assign('php_sub', $department_sub);

##############

// $allTablesNames = db_get_all_tables();
$allTablesNames = uiGetAllTables($db);

$configSelectedIndex = 0;
$configSelectedIndex = $_GET['configSelectedIndex'] ?? 0;

if ($configSelectedIndex > count($allTablesNames))
{
    $configSelectedIndex = 0;
}

$smarty->assign('allTablesNames', $allTablesNames);
$smarty->assign('configSelectedIndex', $configSelectedIndex);
$config_name = $allTablesNames[$configSelectedIndex];

$operation = $_GET['operation'] ?? 'loadDataTablesColumnDescriptions';

$rbcolumnsDefault = [
    'config_name' => 'Конфигурация',
    'data'        => 'column name',
    'db'          => 'DB column name',
    'dt'          => 'DataTables column name',
    'title'       => 'Заголовок',
    'visible'     => 'is visible',
    'searchable'  => 'is searchable',
    'orderable'   => 'is orderable',
    'editable'    => 'is editable',
    'sort_order'  => 'sort order',
    'inputType'   => 'DB Type',
    'enable'      => 'Включить',
    'actions'     => 'Действия',
];

$columnsJsonFileName = PATH_CONFIGS . 'trade_companies' . '__columns.json';
// $table_primaryKey    = 'companyID';

if (!is_null($config_name))
{
    $dbTableFields     = db_getColumnNames($config_name);
    $dbTableFieldNames = array_keys($dbTableFields);   // выводим в логг все названия полей
    $dbTableFieldTypes = array_values($dbTableFields); // выводим в логг все названия полей
    $smarty->assign('dbTableFields', $dbTableFields);
    $smarty->assign('dbTableFieldNames', $dbTableFieldNames);
    $smarty->assign('dbTableFieldTypes', $dbTableFieldTypes);

/*
##########################
##########################
INSERT|UPDATE
##########################
##########################
 */

    if (($operation == 'addNewConfig') || ($operation == 'resetConfig'))
    {
        // dump($dbTableFields);
        $cortages = [];
        $ii       = 0;
        foreach ($dbTableFields as $name => $type)
        {
            $cortages["{$name}"]['ind']       = $ii;
            $cortages["{$name}"]['sqlType']   = $type;
            $cortages["{$name}"]['inputType'] = getInputTemplate(strtolower($type));
            $ii++;
        }

        $cc = 0;
        foreach ($cortages as $key => $value)
        {
            $data = [
                'config_name' => $config_name,
                'data'        => $key,
                'ind'         => $cc,
                'db'          => $key,
                'dt'          => $key,
                'title'       => "$key @ $config_name",
                'visible'     => 1,
                'searchable'  => 1,
                'orderable'   => 1,
                'editable'    => 1,
                'sort_order'  => $cc * 10,
                'enable'      => true,
                'actions'     => null,
                'sql_type'    => $value['sqlType'],
                'input_type'  => $value['inputType'],
            ];

            $where = [
                'config_name' => $config_name,
                'data'        => $key,
            ];

            $r        = $db->table(ANT_RBCOLUMNS)->count('id', 'ccount')->where($where)->get();
            $doInsert = $r->ccount;

            if ((int)$doInsert == 0)
            {
                $r       = $db->table(ANT_RBCOLUMNS)->insert($data);
                $message = 'Новая конфигурация создана';
            }
            else
            {
                $r       = $db->table(ANT_RBCOLUMNS)->where($where)->update($data);
                $message = 'Конфигурация сброшена в исходное состояние';
            }
            $cc++;
            // dump([$db->queryCount(), $db->getQuery()]);
        }

        $timerSec = 1; // задержка для обработки страницы
        $smarty->assign('timerSec', $timerSec);
        header('Content-Type: text/html; charset=utf8');
        $phpToast      = new Toasts();
        $urlToRedirect = ADMIN_FILE . '?dpt=conf&sub=columns&configSelectedIndex=' . $configSelectedIndex;
        $phpToast::run('success', $message, $urlToRedirect, $timerSec);
        // $phpToast::success('successsuccesssuccesssuccess');
        RedirectMetaRefresh($urlToRedirect, $timerSec);
    }

    if (($operation == 'loadDataTablesColumnDescriptions') || ($operation == 'loadDataTablesColumnDescriptionsFromDB'))
    {
        $where = [
            'config_name' => $config_name,
        ];

        $Rows = $db->table(ANT_RBCOLUMNS)->where($where)->orderBy('sort_order')->getAll('array');

        // теперь нужно какждому полу дать controlSnippet
        $Configurations = [];

        foreach ($Rows as $rowIndex => $Row)
        {
/*
dump($Row);
^ {#32 ▼
+"id": 141
+"config_name": "ant_category_product"
+"data": "productID"
+"ind": 0
+"db": "productID"
+"dt": "productID"
+"title": "ant_category_product"
+"visible": 0
+"searchable": 0
+"orderable": 1
+"editable": 1
+"sort_order": 0
+"enable": 1
+"actions": null
+"sql_type": "int(11)"
+"input_type": "single_inputnumber.tpl"
}
 */

            foreach ($Row as $fieldName => $fieldData)
            {
                $Configurations[$rowIndex]['fieldValues']['ind']      = $rowIndex;
                $Configurations[$rowIndex]['fieldValues'][$fieldName] = $Row[$fieldName];

                // dump($dbTableFields);

############ attributes
                $attributes = [
                    'config-name' => $config_name,
                    // 'config-name2' => $Row["config_name"],
                     'primary-id'  => $Row["id"],
                    'field-name'  => $fieldName,
                    'row-number'  => $rowIndex,
                    // 'old-value'    => $Row["$fieldName"],
                     'old-value'   => $fieldData,
                    'class'       => 'control-snippet',
                ];

                // $Configurations[$rowIndex]['fieldAttributes'][$fieldName] = $attributes;

############ params
                $params = [

                    'dataset' => $attributes,
                    'id'      => "{$fieldName}_{$rowIndex}",
                    'name'    => $fieldName,
                    'value'   => $Row[$fieldName],
                ];

                ## ставим checked для чекбоксов с value="1"
                $params['isChecked'] = ($params['value'] == 1) ? 1 : 0;

                ## если поле отключено , дизаблим инпуты и красим их в мутный цвет
                if ($Row["enable"] != 1)
                {
                    $params['class_add']  = 'opacity-50';
                    $params['isDisabled'] = 1;
                    $params['isReadonly'] = 1;
                }

                ## ПРИ ЗАГРУЗКЕ СТРАНИЦЫ
                $params['class_add']  = 'opacity-50';
                $params['isDisabled'] = 1;
                $params['isReadonly'] = 1;

                ## отменяем предыдущее действи для самого столбца enable
                // if ($fieldName === 'enable')
                // {
                //     $params['isDisabled'] = 0;
                //     $params['isReadonly'] = 0;
                // }

                $Configurations[$rowIndex]['fieldParams'][$fieldName] = $params;

############ options
                ## написать функцию возвращающую список полей для db и для dt в виде массива
                #
                #

                $options = [];

                if ($fieldName === 'db' || $fieldName === 'dt' || $fieldName === 'title')
                {
                    $fieldArr      = db_getColumnNames($config_name);
                    $fieldsInTable = [];
                    if (is_array($fieldArr) && !empty($fieldArr))
                    {
                        $fieldsInTable = array_keys($fieldArr);
                        // dd($fieldsInTable);
                        $options = $fieldsInTable;
                    }
                }
                // dump($options);
                if (is_array($options) && count($options))
                {
                    $Configurations[$rowIndex]['fieldOptions'][$fieldName] = $options;
                }
                else
                {
                    $Configurations[$rowIndex]['fieldOptions'][$fieldName] = null;
                }

############ /options
            }
        }
        $smarty->assign('Configurations', $Configurations);

        // dump($Configurations[1]);
    }
}
