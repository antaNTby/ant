<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:12:53
  from 'file:index.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c17495eab9e4_75519380',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '52e8fbb54c50c42654a55e00089fb9066c6d23a4' => 
    array (
      0 => 'index.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:aside.tpl.html' => 1,
    'file:notFound.tpl.html' => 1,
    'file:svg.tpl.html' => 1,
    'file:header_navbar.tpl.html' => 1,
    'file:footer.tpl.html' => 1,
  ),
))) {
function content_69c17495eab9e4_75519380 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\nix2025.my\\admin\\tpl';
?><!DOCTYPE html>
<html lang="be-Cyrl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" sizes=16x16 href="/lib/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <title><?php echo $_smarty_tpl->getValue('title');?>
</title>
            <link rel="stylesheet" href="/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/lib/bootstrap-icons/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="/lib/admin.css">
    </head>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MY_TOP_BANNER", 'MY_TOP_BANNER', null);
if ($_smarty_tpl->getValue('show_top_banner')) {?>
<div id="top-banner" class="d-block m-1 alert alert-info">MY_TOP_BANNER</div>
<?php }
$_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MY_BOTTOM_BANNER", 'MY_BOTTOM_BANNER', null);
if ($_smarty_tpl->getValue('show_bottom_banner')) {?>
<div id="bottom-banner" class="d-block m-1 alert alert-info">MY_BOTTOM_BANNER</div>
<?php }
$_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MY_ASIDE", 'MY_ASIDE', null);
if (!$_smarty_tpl->getValue('aside_hide')) {?>
<aside class="sidebar d-flex bg-body-secondary<?php if ($_smarty_tpl->getValue('aside_reverse') == 0) {?> border-end border-dark border-1<?php } else { ?> border-start border-dark border-1<?php }?>">
    <?php $_smarty_tpl->renderSubTemplate("file:aside.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
</aside>
<?php }
$_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, "MY_MAIN", 'MY_MAIN', null);?>
<main class="flex-grow-1 px-3 bg-white">
    <h1 class="fw-lighter"><?php echo $_smarty_tpl->getValue('pageH1');?>
</h1>
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'MY_TOP_BANNER');?>

        <div class="p-1 bg-light-subtle">
                <?php if ($_smarty_tpl->getValue('template_exist')) {?>
        <?php $_smarty_tpl->renderSubTemplate(((string)$_smarty_tpl->getValue('admin_main_content_template')), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
        <?php } else { ?>
        <?php $_smarty_tpl->renderSubTemplate("file:notFound.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
        <?php }?>
    </div>
    <?php echo $_smarty_tpl->getSmarty()->getRuntime('Capture')->getBuffer($_smarty_tpl, 'MY_BOTTOM_BANNER');?>

</main>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);?>

<body>
    <?php $_smarty_tpl->renderSubTemplate("file:svg.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

    <input id="current_sub" type="hidden" value="<?php echo $_smarty_tpl->getValue('current_sub');?>
">
    <input id="primary_key" type="hidden" value="<?php echo $_smarty_tpl->getValue('primary_key');?>
">
    
    <div class="container-fluid d-flex flex-column p-0 shadow-sm" style="<?php echo $_smarty_tpl->getValue('body_height');
echo $_smarty_tpl->getValue('body_width');?>
">
        <header id="header">
            <?php $_smarty_tpl->renderSubTemplate("file:header_navbar.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
        </header><!-- /header -->
        <!-- Липкий навбар -->
        <!-- Основное содержимое -->
        <div class="wrapper d-flex flex-row border-bottom border-2">
            <?php if ($_smarty_tpl->getValue('aside_hide')) {?>
            <?php echo $_smarty_tpl->getValue('MY_MAIN');?>

            <?php } else { ?>
            <?php if ($_smarty_tpl->getValue('aside_reverse')) {?>
            <?php echo $_smarty_tpl->getValue('MY_MAIN');?>

            <?php echo $_smarty_tpl->getValue('MY_ASIDE');?>

            <?php } else { ?>
            <?php echo $_smarty_tpl->getValue('MY_ASIDE');?>

            <?php echo $_smarty_tpl->getValue('MY_MAIN');?>

            <?php }?>
            <?php }?>
        </div>
        <?php $_smarty_tpl->renderSubTemplate("file:footer.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
    </div>
    <?php echo '<script'; ?>
 src="/lib/bootstrap/dist/js/bootstrap.bundle.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/lib/admin.js" type="module"><?php echo '</script'; ?>
>
</body>

</html><?php }
}
