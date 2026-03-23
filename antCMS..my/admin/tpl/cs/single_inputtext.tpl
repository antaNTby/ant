{* single_checkbox.tpl.html debug_print_var *}
{if isset($p) && is_array($p)}

{if $label}
<label for="input_{$p.id}" class="form-label{if isset($p.class_add_label)} {$p.class_add_label}{/if}">{$label}</label>
{/if}
<input  type="text"
 class="form-control bg-white{if isset($p.class_add)} {$p.class_add}{/if}"
 {if isset($p.id)} id="input_{$p.id}"{/if}
 {if isset($datalist) && is_array($datalist)} list="datalistdatalist_{$p.id}"{/if}
 {if isset($p.dataset) && is_array($p.dataset)}
 {foreach $p.dataset as $key=>$item}
 data-{$key}="{$item}"
 {/foreach}
 {/if}
 value="{$p.value}"
 {if isset($p.isDisabled) && $p.isDisabled eq 1} disabled{/if}
 {if isset($p.isReadonly) && $p.isReadonly eq 1} readonly{/if}

 placeholder="Type{if isset($datalist) && is_array($datalist)} | Select{/if} ...">

<datalist id="datalistdatalist_{$p.id}">
  <option value="#formatter">
  <option value="#null">
  <option value="#custom">
  <option class="dropdown-divider">
    {if isset($datalist) && is_array($datalist)}
    {foreach $datalist as $key=>$item}
    <option value="{$item}">
    {/foreach}
    {/if}
</datalist>

{else}
@
{/if}
