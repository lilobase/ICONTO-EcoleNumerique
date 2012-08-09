{if $nbPages neq null}
<div style="margin: 5px; padding: 2px; border-radius: 12px; -moz-border-radius:12px;" class="center">
{i18n key="kernel|kernel.reglettepages.pages" pNb=$nbPages} :

{if $page > 1}<A HREF='{$url}{$separator}page={$page-1}'>&laquo; {i18n key="kernel|kernel.reglettepages.precedent"}</A>{else}&laquo; {i18n key="kernel|kernel.reglettepages.precedent"}{/if} | 

{assign var=sep value=""}{foreach from=$pages1 item=i}{$sep}{if $page eq $i}<B>{$i}</B>{else}<A HREF='{$url}{$separator}page={$i}'>{$i}</A>{/if}{assign var=sep value=", "}{/foreach}
 {$sep1}
{assign var=sep value=""}{foreach from=$pages2 item=i}{$sep}{if $page eq $i}<B>{$i}</B>{else}<A HREF='{$url}{$separator}page={$i}'>{$i}</A>{/if}{assign var=sep value=", "}{/foreach}
 {$sep2}
{assign var=sep value=""}{foreach from=$pages3 item=i}{$sep}{if $page eq $i}<B>{$i}</B>{else}<A HREF='{$url}{$separator}page={$i}'>{$i}</A>{/if}{assign var=sep value=", "}{/foreach}

 | {if $page < $nbPages}<A HREF='{$url}{$separator}page={$page+1}'>{i18n key="kernel|kernel.reglettepages.suivant"} &raquo;</A>{else}{i18n key="kernel|kernel.reglettepages.suivant"} &raquo;{/if}

</div>
{/if}
