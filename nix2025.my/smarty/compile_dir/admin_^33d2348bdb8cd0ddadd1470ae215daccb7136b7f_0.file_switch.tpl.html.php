<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:43
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\switch.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173d70cf347_46658002',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '33d2348bdb8cd0ddadd1470ae215daccb7136b7f' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\switch.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173d70cf347_46658002 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach3DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach3DoElse = false;
$_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
<div class="form-check form-switch<?php if ((null !== ($_smarty_tpl->getValue('inline') ?? null))) {?> form-check-inline<?php }
if ((null !== ($_smarty_tpl->getValue('reverse') ?? null))) {?> form-check-reverse<?php }?>">
    <input class="smarty-control form-check-input<?php if ((null !== ($_smarty_tpl->getValue('indeterminate') ?? null)) && $_smarty_tpl->getValue('indeterminate')) {?> indeterminate-checkbox<?php }?>"
     type="checkbox"
     role="switch"
     id="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
     name="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
     <?php if ((null !== ($_smarty_tpl->getValue('indeterminate') ?? null)) && $_smarty_tpl->getValue('indeterminate')) {?> indeterminate="indeterminate" value="-1"<?php } else { ?>
     <?php if ((null !== ($_smarty_tpl->getValue('current_value') ?? null)) && $_smarty_tpl->getValue('current_value') != 0) {?>checked="checked" value="1"<?php } else { ?>value="0"<?php }?>
     <?php }?>
     <?php if ((null !== ($_smarty_tpl->getValue('readonly') ?? null)) && $_smarty_tpl->getValue('readonly')) {?>readonly<?php }?> <?php if ((null !== ($_smarty_tpl->getValue('disabled') ?? null)) && $_smarty_tpl->getValue('disabled')) {?>disabled<?php }?> switch="switch">

    <?php if ((null !== ($_smarty_tpl->getValue('label') ?? null))) {?><label class="form-check-label" for="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"><?php echo $_smarty_tpl->getValue('label');?>
</label><?php }?>
</div><?php }
}
