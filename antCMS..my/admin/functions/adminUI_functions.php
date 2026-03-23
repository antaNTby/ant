<?php
// adminUserInteface_functions.php

function d($var)
{
    return dump($var);
}

function exportColumnsToJson(
    $dtColumnFieldNames,
    $limit = 5
)
{
    $ii      = 0;
    $Columns = [];
    foreach ($dtColumnFieldNames as $key => $value)
    {
        if ($ii < $limit)
        {
            $newColumnItem = [
                'data'       => $value,
                'ind'        => $key,
                'db'         => $value,
                'dt'         => $value,
                'title'      => 'title ' . $value,
                'footer'     => 'footer ' . $ii,
                'orderable'  => true,
                'visible'    => true,
                'searchable' => true,
            ];
        }
        else
        {
            $newColumnItem = [
                'data'       => $value,
                'ind'        => $key,
                'db'         => $value,
                'dt'         => $value,
                'title'      => 'title ' . $value,
                'footer'     => 'footer ' . $ii,
                'orderable'  => true,
                'visible'    => false,
                'searchable' => true,
            ];
        }

        $Columns[] = $newColumnItem;
        $ii++;
        // jlog($newColumnItem);
    }

    $jsonColumns = json_encode($Columns, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE);

    return $jsonColumns;
}

//adds new $department to departments list
function sortByField(
    $item,
    $items,
    $sortField = 'sort_order'
)
{
    $i = 0;
    while ($i < count($items) && $items[$i][$sortField] < $item[$sortField])
    {
        $i++;
    }

    for ($j = count($items) - 1; $j >= $i; $j--)
    {
        $items[$j + 1] = $items[$j];
    }

    $items[$i] = $item;
    return $items;
}

function selectDefaultSub($subs)
{
    $defaultSub = $subs[0]['id'];

    if (is_array($subs) && count($subs) > 1)
    {
        foreach ($subs as $key => $value)
        {
            // dump($value);
            if (isset($value['default']) && $value['default'] == 1)
            {
                $defaultSub = $value['id'];
            }
        }
    }
    // dump($defaultSub);
    return $defaultSub;
}

function showSubSmartyOutput(
    $dpt_sub,
    $folder = PATH_TPL_SUB,
    $ext = EXT_TPL
)
{
    global $smarty;

    try {
        if (file_exists($folder . $dpt_sub . $ext))
        {
            $smarty->assign('admin_sub_dpt', $dpt_sub . $ext);
        }
        else
        {
            $smarty->assign('admin_sub_dpt', 'default_sub.tpl.html');
        }
    }
    catch (SmartyException $e)
    {
        $smarty->assign('smarty_error', true);
        $smarty->assign('smarty_error_message', $e->getMessage());
        dump($smarty->getTemplateVars());
    }
}

function getInputTemplate($inputType)
{
    $res = 'error_input.tpl';

    if (str_starts_with($inputType, "text"))
    {
        $res = 'single_textarea.tpl';
    }
    if (str_starts_with($inputType, "varchar"))
    {
        $res = 'single_inputtext.tpl';
    }
    if (str_starts_with($inputType, "int"))
    {
        $res = 'single_inputnumber.tpl';
    }
    if (str_starts_with($inputType, "tinyint"))
    {
        $res = 'single_inputnumber.tpl';
    }
    if (str_starts_with($inputType, "int(1)"))
    {
        $res = 'single_checkbox.tpl';
    }
    if (str_starts_with($inputType, "tinyint(1)"))
    {
        $res = 'single_checkbox.tpl';
    }
    if (str_starts_with($inputType, "datetime"))
    {
        $res = 'single_datetimepicker.tpl';
    }
    if (str_starts_with($inputType, "timestamp"))
    {
        $res = 'single_datetimepicker.tpl';
    }

    return $res;
}

function uiGetAllTables($db)
{

    $rq  = $db->query('SHOW TABLES')->fetchAll('array');
    $res = [];
    foreach ($rq as $key => $value)
    {
        array_push($res, array_values($value)[0]);
    }
    return $res;
}

function uiGetColumns($config_name)
{
    return $res;
}