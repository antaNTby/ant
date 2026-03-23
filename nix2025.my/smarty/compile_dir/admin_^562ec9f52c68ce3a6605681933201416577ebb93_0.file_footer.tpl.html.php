<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:09:53
  from 'file:footer.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c173e199ce32_96838037',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '562ec9f52c68ce3a6605681933201416577ebb93' => 
    array (
      0 => 'footer.tpl.html',
      1 => 1774282148,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69c173e199ce32_96838037 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\new_php8\\nix2025.new\\admin\\tpl';
?><!-- Прилипший футер -->
<footer class="sticky-bottom d-flex p-0 mt-1 flex-column text-bg-dark opacity-25">
    <div class="container-fluid text-center">
        <div class="row align-items-end">
            <div class="col">
                <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')(time(),'%Y-%m-%d %H:%M:%S');?>

            </div>
            <div class="col">
                One
            </div>
            <div class="col">
                © 2025 Права принадлежат тем, кто за них борется.
            </div>
        </div>
    </div>
</footer><?php }
}
