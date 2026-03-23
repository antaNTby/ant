<?php
/* Smarty version 5.8.0, created on 2026-03-23 20:12:54
  from 'file:footer.tpl.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69c1749629cd82_68853216',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'afb674fff30421b56174f57930ed1feab5990dac' => 
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
function content_69c1749629cd82_68853216 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\git\\ant\\nix2025.my\\admin\\tpl';
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
