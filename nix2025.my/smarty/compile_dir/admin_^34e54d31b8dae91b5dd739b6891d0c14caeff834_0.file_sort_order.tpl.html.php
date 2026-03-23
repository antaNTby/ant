<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:52
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\sort_order.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e0cd7201_61019930',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '34e54d31b8dae91b5dd739b6891d0c14caeff834' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\sort_order.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e0cd7201_61019930 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach2DoElse = false;
$_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
<div class="vstack gap-1">
    <div class="hstack gap-1">
        <?php if ((null !== ($_smarty_tpl->getValue('index') ?? null)) && $_smarty_tpl->getValue('index') > 0) {?>
        <button type="button" title="Top" name="button__SortTop<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SortTop" class="smarty-control btn btn-secondary btn-bi"><i class="bi-xs bi-chevron-bar-up"></i></button>
                <?php }?>
        <?php if ((null !== ($_smarty_tpl->getValue('index') ?? null)) && $_smarty_tpl->getValue('index') > 0) {?>
        <button type="button" title="Up" name="button__SortUp<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SortUp" class="smarty-control btn btn-secondary btn-bi"><i class="bi-xs bi-chevron-up"></i></button>
        <?php }?>
        <?php if ((null !== ($_smarty_tpl->getValue('index') ?? null)) && $_smarty_tpl->getValue('index') > 0) {?>
        <button type="button" title="Down" name="button__SortDown<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SortDown" class="smarty-control btn btn-secondary btn-bi"><i class="bi-xs bi-chevron-down"></i></button>
        <?php }?>
        <?php if ((null !== ($_smarty_tpl->getValue('index') ?? null)) && $_smarty_tpl->getValue('index') > 0) {?>
                <button type="button" title="Bottom" name="button__SortBottom<?php if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" data-index="<?php echo $_smarty_tpl->getValue('index');?>
" data-action="SortBottom" class="smarty-control btn btn-secondary btn-bi"><i class="bi-xs bi-chevron-bar-down"></i></button>
        <?php }?>
    </div>

    <div class="hstack gap-1">
        <input type="<?php if ((null !== ($_smarty_tpl->getValue('show_input') ?? null)) && $_smarty_tpl->getValue('show_input') == 1) {?>text<?php } else { ?>hidden<?php }?>" inputmode="numeric" class="smarty-control form-control form-control-xs font-monospace text-end" id="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" name="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>" <?php if ((null !== ($_smarty_tpl->getValue('string_format') ?? null))) {?> value='<?php echo sprintf(((string)$_smarty_tpl->getValue('string_format')),$_smarty_tpl->getValue('current_value'));?>
' <?php } else { ?> value="<?php echo $_smarty_tpl->getValue('current_value');?>
" <?php }?> inputmode="numeric" lang="en-US" step="1" style="max-width:10ch; margin-left:1ch">
    </div>

</div><?php }
}
