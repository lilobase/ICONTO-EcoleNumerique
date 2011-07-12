<h1>{i18n key="agenda.agendalist.title"}</h1>
<div class="explanation">{i18n key="agenda.agendalist.help"}</div>

<div class="agendaList">
	<form action="{copixurl dest="agenda|agenda|vueSemaine"}" method="post" name="chooseAgenda" target="_parent">
		<ul>
		{foreach from=$ppo->listAgendas item=agenda}
			{assign var="id" value=$agenda->id_agenda}
			{assign var="color" value=$ppo->arColorByIdAgenda[$id]}
			<li style="background-color:#{$color};">
			<input type="checkbox" name="agendas.{$agenda->id_agenda}" id="agenda{$agenda->id_agenda}" value="1" {if isset($ppo->agendasSelectionnes[$id])}checked="checked"{/if} />
			<label for="agenda{$agenda->id_agenda}">{$agenda->title_agenda}</label>
			</li>
		{/foreach}
		</ul>
		<input type="hidden" name="updateAgendaAffiches" value="1" />
		<div class="content-panel content-panel-button">
		<input type="submit" class="button button-confirm" value="{i18n key='agenda.agendalist.confirm'}" />
		</div>
	</form>
</div>

