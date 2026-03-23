<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\input_number.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e110e825_66699923',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '45bfa954101295b3f8ea828eb6c70c07bbe778f1' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\input_number.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e110e825_66699923 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach4DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach4DoElse = false;
?>
    <?php $_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

<input 
type="number" inputmode="numeric"
class="smarty-control form-control form-control-sm font-monospace <?php echo $_smarty_tpl->getValue('text_align');?>
"
id="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
name="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
<?php if ((null !== ($_smarty_tpl->getValue('string_format') ?? null))) {?>
value='<?php echo sprintf(((string)$_smarty_tpl->getValue('string_format')),$_smarty_tpl->getValue('current_value'));?>
'
<?php } else { ?>
value="<?php echo $_smarty_tpl->getValue('current_value');?>
"
<?php }?>
inputmode="numeric" lang="en-US" step="any"
<?php if ((null !== ($_smarty_tpl->getValue('readonly') ?? null)) && $_smarty_tpl->getValue('readonly')) {?>readonly disabled<?php }
if ((null !== ($_smarty_tpl->getValue('positive_only') ?? null)) && $_smarty_tpl->getValue('positive_only')) {?> min="0"<?php }?>
> 


<?php }
}
