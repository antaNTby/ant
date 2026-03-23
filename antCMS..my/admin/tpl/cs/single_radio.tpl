{* single_checkbox.tpl.html *}
{if isset($p) && is_array($p)}

{if $label}
<div class="form-check{if isset($p.class_div)} {$p.class_div}{/if}">
{else}
<div{if isset($p.class_div)} class="{$p.class_div}"{/if}>
{/if}
  <input type="radio"
  class="form-check-input{if isset($p.class_add)} {$p.class_add}{/if}{if isset($p.isIndeterminate) && $p.isIndeterminate eq 1} indeterminate{/if}"
  {if isset($p.id)} id="radio_{$p.id}"{/if}
  {if isset($p.name)} name="radio_{$p.name}"{/if}
  {if isset($p.value)} value="{$p.value}"{/if}
  {if isset($p.dataset) && is_array($p.dataset)}
  {foreach $p.dataset as $key=>$item}
  data-{$key}="{$item}"
  {/foreach}
  {/if}
  {if isset($p.value)} value="{$p.value}"{/if}
  aria-label="{$p.name}"
  {if isset($p.isDisabled) && $p.isDisabled eq 1} disabled{/if}
  {if isset($p.isChecked) && $p.isChecked eq 1} checked{/if}>
  {if $label}
  <label class="form-check-label text-nowrap{if isset($p.class_add_label)} {$p.class_add_label}{/if}" for="radio_{$p.id}">
  &nbsp;{$label}
  </label>
  {/if}
</div>

{else}
@
{/if}

<pre class="d-none">
class_div => {if isset($p.class_div)}{$p.class_div}{else}null{/if}<br>
id => {if isset($p.id)}{$p.id}{else}null{/if}<br>
class_add => {if isset($p.class_add)}{$p.class_add}{else}null{/if}<br>
name => {if isset($p.name)}{$p.name}{else}null{/if}<br>
value => {if isset($p.value)}{$p.value}{else}null{/if}<br>
aria_label => {if isset($p.aria_label)}{$p.aria_label}{else}null{/if}<br>
isDisabled => {if isset($p.isDisabled)}{$p.isDisabled}{else}null{/if}<br>
isChecked => {if isset($p.isChecked)}{$p.isChecked}{else}null{/if}<br>
isIndeterminate => {if isset($p.isIndeterminate)}{$p.isIndeterminate}{else}null{/if}<br>
</pre>