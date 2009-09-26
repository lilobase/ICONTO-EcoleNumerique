
<h1>{i18n key="stats|stats.moduleDescription"} : {$date_debut|datei18n:"date_short"} &raquo; {$date_fin|datei18n:"date_short"}</h1>

<div class="dates">

<form method="get" action="{$form_dest}">
{foreach from=$urlTab item=value key=name}
<input type="hidden" name="{$name}" value="{$value}" />

{/foreach}
{select name="mois" values=$comboMois selected=$mois extra='class="form"'}
{select name="annee" values=$comboAnnees selected=$annee extra='class="form"'}
<input type="submit" value="{i18n key="genericTools|messages.action.go"}" class="form_button" />
</form>

{i18n key="stats|stats.date.choix"}

<a {if $date eq 'today'}class="sel" {/if}href="{$url}&date=today">{i18n key="stats|stats.date.today"}</a>
 | 
<a {if $date eq 'yesterday'}class="sel" {/if}href="{$url}&date=yesterday">{i18n key="stats|stats.date.yesterday"}</a>
 | 
<a {if $date eq 'last7'}class="sel" {/if}href="{$url}&date=last7">{i18n key="stats|stats.date.last7"}</a>
 | 
<a {if $date eq 'month'}class="sel" {/if}href="{$url}&date=month">{i18n key="stats|stats.date.month"}</a>

<br clear="right" />

</div>

<div>{$stats1}</div>
<div>{$stats2}</div>
<div>{$stats3}</div>
