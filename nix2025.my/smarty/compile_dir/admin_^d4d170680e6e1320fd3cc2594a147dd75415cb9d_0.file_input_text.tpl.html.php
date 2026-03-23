<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:52
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\input_text.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e0c07e64_64747318',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd4d170680e6e1320fd3cc2594a147dd75415cb9d' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\input_text.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e0c07e64_64747318 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach1DoElse = false;
?>
    <?php $_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

<input 
type="text"
class="smarty-control form-control form-control-sm <?php echo $_smarty_tpl->getValue('text_align');?>
"
id="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" lang="ru-RU"
name="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('current_value'), ENT_QUOTES, 'UTF-8', true);?>
"
<?php if ((null !== ($_smarty_tpl->getValue('readonly') ?? null)) && $_smarty_tpl->getValue('readonly')) {?>readonly<?php }
if ((null !== ($_smarty_tpl->getValue('disabled') ?? null)) && $_smarty_tpl->getValue('disabled')) {?>disabled<?php }?>

> 
<?php }
}
