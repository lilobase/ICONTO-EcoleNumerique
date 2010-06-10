{if $config_ok && $config->conf_active}

<div class="launch">
<a href="{$url}" target="_blank"><span class="big">Cliquez ici</span><br />pour lancer la<br />visioconf&eacute;rence</a>
</div>

{if $config->conf_msg neq ""}<div class="message">{$config->conf_msg}</div>{/if}

{else}
Aucune visioconf&eacute;rence n'est actuellement disponible.
{/if}