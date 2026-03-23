<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:aside.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e1872fa6_48495733',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f343ba82b847367780d2d09a61dae5022c1bff85' => 
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
function content_69c173e1872fa6_48495733 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl';
?><div class="d-flex flex-column mx-auto pp-0 overflow-y-auto">
    <div class="h1 w-100 fw-bolder mx-auto text-bg-danger px-3 py-1 my-3 rounded-start-4"><?php echo (defined('SERVER_NAME') ? constant('SERVER_NAME') : null);?>
</div>
    <?php $_smarty_tpl->renderSubTemplate("file:aside_navigator.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
</div><?php }
}
