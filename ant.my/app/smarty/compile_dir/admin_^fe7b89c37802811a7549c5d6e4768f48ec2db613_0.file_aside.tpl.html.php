<?php
/* Smarty version 5.6.0, created on 2025-11-06 13:26:45
  from 'file:aside.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.6.0',
  'unifunc' => 'content_690ca2155d2878_18856153',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fe7b89c37802811a7549c5d6e4768f48ec2db613' => 
    array (
      0 => 'aside.tpl.html',
      1 => 1757568371,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:aside_navigator.tpl.html' => 1,
  ),
))) {
function content_690ca2155d2878_18856153 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\ant.my\\app\\templates';
?><div class="d-flex flex-column mx-auto pp-0 overflow-y-auto">
    <div class="h1 w-100 fw-bolder mx-auto text-bg-danger px-3 py-1 my-3 rounded-start-4"><?php echo (defined('SERVER_NAME') ? constant('SERVER_NAME') : null);?>
</div>
    <?php $_smarty_tpl->renderSubTemplate("file:aside_navigator.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
</div><?php }
}
