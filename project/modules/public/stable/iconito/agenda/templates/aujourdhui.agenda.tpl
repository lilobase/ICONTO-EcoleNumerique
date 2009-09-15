<table class="zoneAujourdhui">
	<tr>
		<td class="dateJour">{$jour} {$mois} {$annee}</td>
	</tr>
	{foreach from=$arEvent item=event}
	<tr>
		<td>
			{assign var=id_event value=$event->id_event}
			<!--<div class="event" style="background-color:#{$arColorByEvent.$id_event[0]}">{$event->heuredeb_event} {$event->title_event}</div>-->
			<div class="event" style="background-color:#{$event->color}">{$event->heuredeb_event} {$event->title_event}</div>
		</td>
	</tr>
	{/foreach}
	{foreach from=$arAgendas item=agenda key=id_agenda}
	{assign var=color value=$arColorAgenda.$id_agenda}
	<tr>
		<td>
			<span style="background-color:#{$color}">&nbsp;&nbsp;&nbsp;&nbsp;</span> {$agenda}
			<!--<span style="background-color:#cc22ff">&nbsp;&nbsp;&nbsp;&nbsp;</span> {$agenda}-->
		</td>
	</tr>
	{/foreach}
</table>


