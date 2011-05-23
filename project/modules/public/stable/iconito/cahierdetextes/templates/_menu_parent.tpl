<div id="submenu">
<div class="menuitems">
    <ul>
        <li><a class="event {if $ppo->current == "voirTravaux"}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.day"}</span></a></li>
        <li><a class="page {if $ppo->current == "voirListeTravaux"}current{/if}" href="{copixurl dest="cahierdetextes||voirListeTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.list"}</span></a></li>
        <li><a class="chart {if $ppo->current == "voirTravauxParDomaine"}current{/if}" href="{copixurl dest="cahierdetextes||voirTravauxParDomaine" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.domains"}</span></a></li>
        <li class="newGroupItems"><a class="article {if $ppo->current == "voirMemos"}current{/if}" href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}"><span class="valign"></span><span>{if $ppo->nombreMemos > 0}<span class="memos-count">{$ppo->nombreMemos}</span>{/if} {i18n key="cahierdetextes.message.memos"}</span></a></li>
	</ul>
</div>
</div>