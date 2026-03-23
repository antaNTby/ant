<?php

### //init Smarty 4.2.1
### require "core/smarty4.2.1/Smarty.class.php"; // require "core/smarty/smarty.class.php";
### $smarty                     = new Smarty();  //core smarty object
### $smarty_mail                = new Smarty();  //for e-mails
### $smarty->force_compile      = true;
### $smarty_mail->force_compile = true;
### // set Smarty include files dir
### $smarty->template_dir = "tpl";
### $smarty->compile_id   = "antCMS00"; // у кэш-файлов разных шаблонов будут разные имена (префиксы).

###trade_orders.php

$DPT_SUB = 'trade_orders';
// echo "$DPT_SUB PHP is here!!!";

$db_fields   = array();
$subTables[] = ORDERS_TABLE;
$db_fields[] = db_getColumnNames($subTables[0]);

$whiteList = array(
    'customer_firstname',
    'orderID',
    // 'currency_code',
     // 'customerID',
     // 'order_time',
     // 'shipping_cost',
     // 'order_discount',
     'order_amount',
    'currency_value',
);

$blackList = array(
    'shipping_firstname',
    'shipping_lastname',
    'shipping_country',
    'shipping_state',
    'shipping_city',
    'shipping_address',
    'billing_firstname',
    'billing_lastname',
    'billing_country',
    'billing_state',
    'billing_city',
    'billing_address',
    'cc_number',
    'cc_holdername',
    'cc_expires',
    'cc_cvv',
    'order_aka',
    'customer_aka',
);
$dt_columns = array();
// $blackList =[];

## array_diff_key — Вычисляет расхождение массивов, сравнивая ключи
## Возвращает массив (array), содержащий все элементы array с ключами, которых нет во всех последующих массивах.
$dt_columns1 = array_diff(array_keys($db_fields[0]), $blackList);
## array_diff_ukey — Вычисляет расхождение массивов, используя callback-функцию для сравнения ключей
## Возвращает массив (array), содержащий все элементы array, отсутствующие в каком-либо из всех остальных массивов.
// $dt_columns2 = array_diff_ukey(array_keys($db_fields[0]), $whiteList, 'key_compare_func');

// foreach ($db_fields[0] as $key => $value)
// {
//     // echo " $key => $value<br>";
// }

foreach ($dt_columns1 as $key => $value)
{
    if (in_array($value, $whiteList))
    {
        $dt_columns2[] = $value;
    }
}

// echo debug(array_keys($db_fields[0])) . '<br><br>';
// echo debug(array_values($db_fields[0]))."<br><br>";

cls();
jlog($dt_columns1);
echo debug($dt_columns1);
// jlog(array_keys($db_fields[0]));
jlog($dt_columns2);
echo debug($dt_columns2);

echo debug(array_keys($dt_columns2));
echo debug(array_values($dt_columns2));
// jlog(array_values($db_fields[0]));

//show Smarty output
$smarty->assign('subTables', $subTables);
$smarty->assign('db_fields', $db_fields);
showSubSmartyOutput($DPT_SUB);

use RedBeanPHP\R;
R::setup('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

// $cd->tableName  = $table_name;
// $cd->data       = 'data';
// $cd->db         = 'db';
// $cd->dt         = 'dt';
// $cd->title      = 'title';
// $cd->visible    = 1;
// $cd->searchable = 1;
// $cd->orderable  = 1;
// $cd->editable   = 1;
// $cd->sort       = 0;
// $cd->enable     = true;
// $cd->actions    = 'actions';

$nn = 0;
foreach ($cortages as $key => $value)
{
    // code...
    // dump("$key => $value");
    $cd = R::dispense('rbcolumns');

    $cd->tableName  = $table_name;
    $cd->data       = $key;
    $cd->db         = $key;
    $cd->dt         = $key;
    $cd->title      = "$key in $table_name";
    $cd->visible    = 1;
    $cd->searchable = 1;
    $cd->orderable  = 1;
    $cd->editable   = 1;
    $cd->sort       = $nn * 10;
    $cd->enable     = true;
    $cd->actions    = null;
    $cd->sqlType    = $value['sqlType'];
    $cd->inputType  = $value['inputType'];

    $nn++;
    $id = R::store($cd);
    jlog($id);
}



/*
$attributes = array(
'a'  => 111,
'ab' => 222,
'ac' => 'dd'
);

$smarty->assign('attributes', $attributes);

$db_check_p = array(

'class_div'       => 'p-1 d-flex justify-content-center',
'id'              => 'is',
// 'class_add'       => 'h5',
'name'            => 'test0',
'value'           => 1,
'aria_label'      => 0,
'isDisabled'      => 0,
'isChecked'       => 1,
'isIndeterminate' => 0

);
$db_check_p2 = array(

'class_div'       => 'p-1 d-flex justify-content-center',
'id'              => 'ijjs',
// 'class_add'       => 'h4',
// 'class_add_label' => 'h4',
'name'            => 'test01',
'value'           => 0,
'aria_label'      => 0,
'isDisabled'      => 0,
'isChecked'       => 0,
'isIndeterminate' => 1

);
$smarty->assign('db_check_p', $db_check_p);
$smarty->assign('db_check_p2', $db_check_p2);

// create template object with its private variable scope
$tpl = $smarty->createTemplate('cs/single_inputtext.tpl');

$tpl->assign('label', "Бабель");
$tpl->assign('p',$db_check_p2);
$tpl->assign('dataset',$attributes);

// display the template
$tpl->display();
 */
// $myTestControlSnippet = $tpl->fetch('cs/single_inputtext.tpl.html');
// dump($myTestControlSnippet);
