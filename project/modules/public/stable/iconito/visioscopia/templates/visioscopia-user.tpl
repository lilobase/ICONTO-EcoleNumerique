{literal}
<style>
<!--
DIV.visioscopia DIV.launch {
	text-align: center;
	margin: 50px;
}

DIV.visioscopia DIV.launch SPAN.big {
	font-size: 1.5em;
	font-weight: bold;
}

DIV.visioscopia DIV.launch A {
	background-color: #D3E15F;
	border: 5px solid #D3E15F;
	padding: 10px;
	text-decoration: none;
	color: #04570F;
	display: block;
	width: 150px;
	margin: auto;
}

DIV.visioscopia DIV.launch A:hover {
	border: 5px solid #B3C13F;
}

DIV.message {
	padding: 20px;
	margin: 0 200px 0 200px;
	background-color: #D3E15F;
	border: 5px solid #B3C13F;
}


-->
</style>
{/literal}

{if $config_ok && $config->conf_active}

<div class="launch">
<a href="{$url}" target="_blank"><span class="big">Cliquez ici</span><br />pour lancer la<br />visioconf&eacute;rence</a>
</div>

{if $config->conf_msg neq ""}<div class="message">{$config->conf_msg}</div>{/if}

{else}
Aucune visioconf&eacute;rence n'est actuellement disponible.
{/if}