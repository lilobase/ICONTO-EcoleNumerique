{literal}<link rel="stylesheet" type="text/css" href="styles/module_annuaire.css" />{/literal}


<div id="ecole_infos"><img class="coude" src="img/groupe/lucien_coude.gif" />

<b>{$ecole.nom}</b> ({$ecole.desc})<br />
{$ecole.ALL->eco_num_rue}{$ecole.ALL->eco_num_seq}, {$ecole.ALL->eco_adresse1}<br />
{$ecole.ALL->eco_code_postal} {$ecole.ALL->eco_commune}<br />
<img width="11" height="9" src="img/annuaire/icon_tel.gif" alt="Téléphone" title="Téléphone" border="0" hspace="1" /> {$ecole.ALL->eco_tel}



{ if $ecole.directeur }
<h2>Directeur</h2>
<div>{$ecole.directeur.info.prenom} {$ecole.directeur.info.nom|upper} {if $ecole.directeur.info.login}<a href="{copixurl dest="minimail||getNewForm" login=$ecole.directeur.info.login}"><img width="12" height="9" src="img/minimail/new_minimail.gif" alt="Lui envoyer un minimail" title="Lui envoyer un minimail" border="0" /></a>{/if}</div>

{/if}



{ if $classes }

<h2>Classes et enseignants</h2>

{foreach from=$classes item=class}
<div><b><A HREF="{copixurl dest="|view" classe=$class.id}">{$class.info.nom}</A></b>
: 
{foreach from=$class.enseignants item=enseignant}
 {$enseignant.prenom} {$enseignant.nom|upper} {if $enseignant.login}<A HREF="{copixurl dest="minimail||getNewForm" login=$enseignant.login}"><IMG WIDTH="12" HEIGHT="9" SRC="img/minimail/new_minimail.gif" ALT="Lui envoyer un minimail" TITLE="Lui envoyer un minimail" BORDER="0" /></A>{/if}
{/foreach}

</DIV>

{/foreach}

{/if}


</DIV>


{ if $classe }

<H2>Classe {$classe.nom}</H2>

<H3>Enseignant(s)</H3>
<DIV ID="eleves">
{foreach from=$classe.enseignants item=enseignant}
<div><img SRC="img/annuaire/sexe{$enseignant.sexe}b.png" width="15" height="17" /> {$enseignant.prenom} {$enseignant.nom|upper} {if $enseignant.login}<A HREF="{copixurl dest="minimail||getNewForm" login=$enseignant.login}"><IMG WIDTH="12" HEIGHT="9" SRC="img/minimail/new_minimail.gif" ALT="Lui envoyer un minimail" TITLE="Lui envoyer un minimail" BORDER="0" /></A>{/if}</DIV>
{/foreach}
</div>

<h3>Elèves</h3>
<div id="eleves">
{foreach from=$classe.eleves item=eleve}
<div><img src="img/annuaire/sexe{$eleve.info.sexe}a.png" width="15" height="17" /> {$eleve.info.prenom} {$eleve.info.nom|upper} {if $eleve.info.login}<A HREF="{copixurl dest="minimail||getNewForm" login=$eleve.info.login}"><IMG WIDTH="12" HEIGHT="9" SRC="img/minimail/new_minimail.gif" ALT="Lui envoyer un minimail" TITLE="Lui envoyer un minimail" BORDER="0" /></A>{/if}</DIV>
{/foreach}
</div>



{/if}
		
		
		
		{$annu}