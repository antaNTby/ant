<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:12:54
  from 'file:aside.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c17496173609_71791776',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '75ae7678cc4e24ee52b735b4f5d73f55f9dac428' => 
    array (
      0 => 'aside.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:aside_navigator.tpl.html' => 1,
  ),
))) {
function content_69c17496173609_71791776 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\nix2025.my\\admin\\tpl';
?><div class="d-flex flex-column mx-auto pp-0 overflow-y-auto">
    <div class="h1 w-100 fw-bolder mx-auto text-bg-danger px-3 py-1 my-3 rounded-start-4"><?php echo (defined('SERVER_NAME') ? constant('SERVER_NAME') : null);?>
</div>
    <?php $_smarty_tpl->renderSubTemplate("file:aside_navigator.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
</div><?php }
}
