<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:52
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\row_buttons.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e0e97764_15423129',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b683814f49256b44aafb5d885151079cb23e7bb6' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\row_buttons.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e0e97764_15423129 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach3DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach3DoElse = false;
$_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
<div class="hstack gap-1">
    <?php if ($_smarty_tpl->getValue('btnClone')) {?>
    <button type="button" name="button__Clone<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="Clone" class="smarty-control btn btn-light btn-sm text-nowrap mx-1"><i class="bi bi-copy"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> Clone</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnClear')) {?>
    <button type="button" name="button__Clear<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="Clear" class="smarty-control btn btn-light btn-sm text-nowrap mx-1"><i class="bi bi-eraser"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> Clear</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnAddNew')) {?>
    <button type="button" name="button__AddNew<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="AddNew" class="smarty-control btn btn-light btn-sm text-nowrap mx-1"><i class="bi bi-plus-lg"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> AddNew</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnFixSort') && (null !== ($_smarty_tpl->getValue('index') ?? null)) && $_smarty_tpl->getValue('index') > 0) {?>
    <button type="button" name="button__FixSort<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="FixSort" class="smarty-control btn btn-light btn-sm text-nowrap mx-1"><i class="bi bi-sort-numeric-down"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> FixSort</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnSaveRow')) {?>
        <button type="button" name="button__SaveRow<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SaveRow" class="smarty-control btn btn-outline-secondary btn-sm text-nowrap mx-1" disabled><i class="bi bi-save"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> SaveRow</span></button>
        <?php }?>
    <?php if ($_smarty_tpl->getValue('btnDelete')) {?>
    <div class="vr ms-auto"></div>
    <button type="button" name="button__Delete<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="Delete" class="smarty-control btn btn-outline-danger btn-sm text-nowrap ms-auto"><i class="bi bi-trash3"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> Delete</span></button>
    <?php }?>
</div><?php }
}
