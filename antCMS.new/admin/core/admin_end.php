<?php

if (isset($_SESSION['log']))
{
    $smarty->assign('adminlogname', $_SESSION['log']);
}

// dump($_GET);
// dump($_SESSION);

if (isset($_GET['db']))
{
    $url = 'adminer.php?username=' . DB_USER . '&db=' . DB_NAME; //adminer.php?username=nixby_dbadmin&db=db_antCMS&table=ant_customers
    Redirect($url);
}

$container_width = ADMIN_CONTAINER_WIDTH;
if (isset($_COOKIE['TEMPLATE_WIDTH']))
{
    $container_width = $_COOKIE['TEMPLATE_WIDTH'];
}

$allowedContainerWidths = ['container', 'container-sm', 'container-md', 'container-lg', 'container-xl', 'container-xxl', 'container-fluid'];
$smarty->assign('allowedContainerWidths', $allowedContainerWidths);
if (isset($_GET['change_template_width']))
{
    $new_template_width = $_GET['change_template_width'];

    $container_width = (in_array($new_template_width, $allowedContainerWidths))
    ? $new_template_width
    : ADMIN_CONTAINER_WIDTH;
    set_cookie('TEMPLATE_WIDTH', $container_width);

    Redirect(ADMIN_FILE);
}

$allowedToastPlacements = [
    'top-0 start-0',
    'top-0 start-50 translate-middle-x',
    'top-0 end-0',
    'top-50 start-0 translate-middle-y',
    'top-50 start-50 translate-middle',
    'top-50 end-0 translate-middle-y',
    'bottom-0 start-0',
    'bottom-0 start-50 translate-middle-x',
    'bottom-0 end-0',
];

$allowedToastPlacementNames = [
    'Top left',
    'Top center',
    'Top right',
    'Middle left',
    'Middle center',
    'Middle right',
    'Bottom left',
    'Bottom center',
    'Bottom right',
];

$smarty->assign('allowedToastPlacements', $allowedToastPlacements);
$smarty->assign('allowedToastPlacementNames', $allowedToastPlacementNames);

$placementID     = ADMIN_TOAST_PLACEMENT_ID;
$toast_placement = $allowedToastPlacements[$placementID];

if (isset($_COOKIE['TOAST_PLACEMENT_ID']))
{
    $placementID     = $_COOKIE['TOAST_PLACEMENT_ID'];
    $toast_placement = $allowedToastPlacements[$placementID];
}

if (isset($_GET['change_toast_placement']))
{
    //не меняем если отключаем вывод тоастов  ($_GET['change_toast_placement']==-1)
    $placementID = (array_key_exists((int)$_GET['change_toast_placement'], $allowedToastPlacements)) ? $_GET['change_toast_placement'] : $_COOKIE['TOAST_PLACEMENT_ID'];

    $toast_placement = $allowedToastPlacements[$placementID];
    set_cookie('TOAST_PLACEMENT_ID', $placementID);
    Redirect(ADMIN_FILE);
}

if (!extension_loaded('gd'))
{
    $gd_ver = 0;
}
else
{
    $gd_ver = php_gd();
}
$rd = db_version();
$smarty->assign('mver', $rd);
$phpver = phpversion();
$smarty->assign('pver', $phpver);

include_once 'core/controlSnippetsProcessor.php';

//show Smarty output
try
{
    $smarty->assign('container_width', $container_width);
    $smarty->assign('placementID', $placementID);
    $smarty->assign('toast_placement', $toast_placement);
    $smarty->display('admin.tpl.html');
    // $smarty->display('admin2.tpl.html');
}
catch (SmartyException $e)
{
    $smarty->assign('smarty_error', true);
    $smarty->assign('smarty_error_message', $e->getMessage());
    dump($smarty->getTemplateVars());
}

if (0 or ADMIN_SMARTY_LOG_VARS)
{
    // dump($flatDepartments);
    // dump($smarty->getTemplateVars('flatDepartments'));
    dump($smarty->getTemplateVars());
    // $all_tpl_vars = $smarty->getTemplateVars();
    // smartylog($all_tpl_vars);
}
