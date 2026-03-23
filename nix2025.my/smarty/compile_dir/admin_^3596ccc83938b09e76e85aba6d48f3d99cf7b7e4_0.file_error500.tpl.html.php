<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:12:32
  from 'file:error500.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c174802286e9_93341142',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3596ccc83938b09e76e85aba6d48f3d99cf7b7e4' => 
    array (
      0 => 'error500.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c174802286e9_93341142 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\nix2025.my\\admin\\tpl';
echo $_smarty_tpl->getValue('type');?>
: <b style=color:red><?php echo $_smarty_tpl->getValue('message');?>
</b> @ <?php echo $_smarty_tpl->getValue('pathinfo');?>

<hr>
<code>
    <?php echo $_smarty_tpl->getValue('trace');?>

</code>
<?php if ($_smarty_tpl->getValue('requestData')) {?>
<h2>REQUEST</h2>
Запрос:  <?php echo $_smarty_tpl->getValue('requestData')['method'];?>
  <?php echo $_smarty_tpl->getValue('requestData')['url'];?>

<pre>
"url":    <?php echo $_smarty_tpl->getValue('requestData')['url'];?>

"method": <?php echo $_smarty_tpl->getValue('requestData')['method'];?>

"fullUrl":<?php echo $_smarty_tpl->getValue('requestData')['fullUrl'];?>

"baseUrl":<?php echo $_smarty_tpl->getValue('requestData')['baseUrl'];?>

"body":   <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('requestData')['body']);?>
   
</pre>
<?php }?>
<hr>
<h1>ERROR 500 <?php echo $_smarty_tpl->getValue('title');?>
</h1>
Тип:  <?php echo $_smarty_tpl->getValue('type');?>
 Файл:  <?php echo $_smarty_tpl->getValue('file');?>
 Строка:  <?php echo $_smarty_tpl->getValue('line');?>

<hr>
<?php echo $_smarty_tpl->getValue('file');?>
:<?php echo $_smarty_tpl->getValue('line');?>



<?php }
}
