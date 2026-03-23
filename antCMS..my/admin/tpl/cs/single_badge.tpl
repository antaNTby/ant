{* single_checkbox.tpl.html *}
{if isset($p) && is_array($p)}

<span class="badge text-bg-secondary{if isset($p.class_add)} {$p.class_add}{/if}"
 {if isset($p.dataset) && is_array($p.dataset)}
 {foreach $p.dataset as $key=>$item}
 data-{$key}="{$item}"
 {/foreach}
 {/if}>
{$content}
</span>

{else}
@
{/if}

