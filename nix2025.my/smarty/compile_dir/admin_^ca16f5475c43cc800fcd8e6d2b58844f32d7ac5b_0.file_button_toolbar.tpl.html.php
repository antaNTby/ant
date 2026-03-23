<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\button_toolbar.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e12026d2_52563328',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca16f5475c43cc800fcd8e6d2b58844f32d7ac5b' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\button_toolbar.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e12026d2_52563328 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach5DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach5DoElse = false;
$_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
<div class="hstack gap-1">
    <?php if ($_smarty_tpl->getValue('btnSaveAll')) {?>
    <button type="submit" name="button__SaveAll<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-table-id="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SaveAll" class="smarty-control btn btn-outline-secondary btn-lg text-nowrap mx-2" disabled><i class="bi bi-floppy"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> SaveAll</span></button>
        <?php }?>
    <?php if ($_smarty_tpl->getValue('btnClearAll')) {?>
    <div class="vr"></div>
    <button type="button" name="button__ClearAll<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-table-id="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="ClearAll" class="smarty-control btn btn-secondary btn-lg text-nowrap mx-2"><i class="bi bi-eraser"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> ClearAll</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnFixSort')) {?>
    <div class="vr"></div>
    <button type="button" name="button__FixSort<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-table-id="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="FixSort" class="smarty-control btn btn-secondary btn-lg text-nowrap mx-2"><i class="bi bi-sort-numeric-down"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> FixSort</span></button>
    <?php }?>
    <?php if ($_smarty_tpl->getValue('btnDeleteAll')) {?>
    <div class="vr"></div>
    <button type="button" name="button__DeleteAll<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-table-id="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="DeleteAll" class="smarty-control btn btn-danger btn-lg text-nowrap mx-2"><i class="bi bi-trash3"></i> <span class="d-sm-inline-block d-md-none d-lg-inline-block"> DeleteAll</span></button>
    <?php }?>
</div><?php }
}
