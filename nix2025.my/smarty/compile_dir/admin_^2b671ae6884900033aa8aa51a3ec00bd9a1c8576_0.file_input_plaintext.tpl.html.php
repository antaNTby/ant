<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:52
  from 'file:C:\git\new_php8\nix2025.new\admin\tpl\smartyControls\input_plaintext.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e0b74d11_91629291',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b671ae6884900033aa8aa51a3ec00bd9a1c8576' => 
    array (
      0 => 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls\\input_plaintext.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e0b74d11_91629291 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl\\smartyControls';
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('params'), 'value', false, 'key');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach0DoElse = false;
?>
    <?php $_smarty_tpl->assign($_smarty_tpl->getValue('key'), $_smarty_tpl->getValue('value'), false, NULL);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

<input 
type="text"
class="smarty-control form-control-plaintext <?php echo $_smarty_tpl->getValue('font_style');?>
 <?php echo $_smarty_tpl->getValue('text_align');?>
"
id="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
name="<?php echo $_smarty_tpl->getValue('controlName');
if ((null !== ($_smarty_tpl->getValue('index') ?? null))) {?>[<?php echo $_smarty_tpl->getValue('index');?>
]<?php }?>"
value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('current_value'), ENT_QUOTES, 'UTF-8', true);?>
"
readonly
> 
<?php }
}
