{if $arMois}

<script language="Javascript1.2">

var tabMois = new Array();
var tabJoursFeries = new Array();

</script>


<table class="resultats" border="0" width="100%"><tr><td>
	


<div style="border-bottom:solid 1px gray; margin:2px;">

<div style="float:left; width:220px;">Mois</div>

{foreach from=$arJours item=i key=key}
<div class="jour jour_{$i.nom}" style="font-weight:bolder;">{$i.nom}<br/><a href="javascript:check_jours_col('jours', 'all', {$key+1});">S.</a></div>
{/foreach}
</div>
<br clear="left" />


{assign var='curAnnee' value=''}
{counter name=iMois start=-1 assign=nothing}

{foreach from=$arMois item=mois}

{counter name=iJours start=-1 assign=nothing}
{counter name=iMois assign=numIndex}


{if $mois.annee neq $curAnnee}
	<div style="border-bottom:solid 1px gray; margin:2px; font-size:1.3em; background:#6B6C68; font-weight:bolder; padding:2px;">{$mois.annee}</div>
	{assign var='curAnnee' value=$mois.annee}
{/if}

<div style="border-bottom:solid 1px gray; margin:2px;">

<div style="float:left; width:220px;">{$mois.nom}<br/>S&eacute;l : <a href="javascript:check_jours_mois('jours', '{$mois.annee}', '{$mois.numero}', 'all');">tous</a> <a href="javascript:check_jours_mois('jours', '{$mois.annee}', '{$mois.numero}', 'allouvr', {$numIndex});">tous ouvr&eacute;s</a> <a href="javascript:check_jours_mois('jours', '{$mois.annee}', '{$mois.numero}', 'none', '');">aucun</a> <a href="javascript:check_jours_mois('jours', '{$mois.annee}', '{$mois.numero}', 'reverse', '');">inv.</a></div>

{foreach from=$mois.casesVides item=null}
<div class="jour dehors">&nbsp;</div>
{/foreach}

{foreach from=$mois.jours key=key item=jour}


{counter name=iJours assign=iJours}

{if $iJours == 0}
<script language="Javascript1.2">
//tabMois.push('{$mois.annee}-{$mois.numero}');
tabMois[{$numIndex}] = '{$jour.lettre}';
</script>
{/if}

{if $jour.ferie}
<script language="Javascript1.2">
//tabJoursFeries['{$key}'] = 1;
tabJoursFeries.push('{$key}');
</script>
{/if}

<div class="jour jour_{$jour.lettre}{if $jour.ferie} jour_ferie{/if}{if $rForm->jours[$key]==1} jour_open{/if}">


<div><label for="jours[{$key}]" style="font-size:0.7em;">{$iJours+1}</label></div>
<input {if $jour.disabled}disabled {/if}type="checkbox" name="jours[{$key}]" id="jours[{$key}]" value="1" {if $rForm->jours[$key]==1}checked {/if}/>

<div class="horaires">
{if $arHoraires.$key|@count}
{foreach from=$arHoraires.$key item=horaire}
<div class="horaire">{$horaire->heure_debut}<br />{$horaire->heure_fin}</div>
{/foreach}
{/if}
</div>


</div>

{/foreach}


</div>
<br clear="left" />
{/foreach}


</td></tr></table>

{/if}

<script language="Javascript1.2">

//alert (tabMois);
//alert (tabJoursFeries);

{literal}


/* GESTION DES SESSIONS */

function check_jours_mois (field, annee, mois, mode, numIndex) {
	var form = $('form');
	//var reg = new RegExp("^"+field+"\[[0-9]{4}[-][0-9]{2}[-][0-9]{2}","i");
	//var reg = new RegExp("^"+field+"\["+annee+"[-]{1}[0-9]{2}[-]{1}[0-9]{2}","i");
	//var reg = new RegExp("^"+field+"\["+annee+"[-]{1}[0-9]{2}[-]{1}[0-9]{2}\]$","i");
	var reg = new RegExp("^"+field+"\[[0-9]{4}","i");
	//alert (reg);
	
	switch (mode) {
		case 'allouvr' :
			var premierJour = tabMois[numIndex];
			switch (premierJour) {
				case 'L' : nbCasesVides = 0; break;
				case 'Ma' : nbCasesVides = 1; break;
				case 'Me' : nbCasesVides = 2; break;
				case 'J' : nbCasesVides = 3; break;
				case 'V' : nbCasesVides = 4; break;
				case 'S' : nbCasesVides = 5; break;
				case 'D' : nbCasesVides = 6; break;
			}
			//alert ("premierJour="+premierJour+" / nbCasesVides="+nbCasesVides);
			break;
	}
	
	for (i=0; i<form.length; i++) {
		name = form[i].name;
		switch (mode) {
			case 'all' :
			case 'none' :
			case 'reverse' :
				if (reg.test(name) && name.substr(0,13)==field+'['+annee+'-'+mois && !form[i].disabled)
					form[i].checked = (mode == 'reverse') ? !form[i].checked : ((mode == 'all') ? 1 : 0);
				break;
			case 'allouvr' :
				if (reg.test(name) && name.substr(0,13)==field+'['+annee+'-'+mois && !form[i].disabled) {
					// Ajout du test sur les jours feries
					for (j=0, isFerie=false; !isFerie && j<tabJoursFeries.length; j++) {
						isFerie = (tabJoursFeries[j]==name.substr(6,10));
					}
					j = name.substr(14,2);
					mod = ((j*1)+nbCasesVides)%7;
					if (mod != 6 && mod != 0 && !isFerie)
						form[i].checked = 1;
				}
				break;
		}
	}

}

function check_jours_col (field, mode, colonne) {
	var form = $('form');
	//var reg = new RegExp("^"+field+"\[[0-9]{4}[-][0-9]{2}[-][0-9]{2}","i");
	//var reg = new RegExp("^"+field+"\["+annee+"[-]{1}[0-9]{2}[-]{1}[0-9]{2}","i");
	//var reg = new RegExp("^"+field+"\["+annee+"[-]{1}[0-9]{2}[-]{1}[0-9]{2}\]$","i");
	var reg = new RegExp("^"+field+"\[[0-9]{4}","i");
	
	var numLine = -1;
	var curLine = '';
	for (i=0; i<form.length; i++) {
		name = form[i].name;
		switch (mode) {
			default :
				if (reg.test(name) && !form[i].disabled) {
					m = name.substr(11,2);
					j = name.substr(14,2);
					if (curLine != m) {
						curLine = m;
						numLine++;
						premierJour = tabMois[numLine];
						switch (premierJour) {
							case 'L' : nbCasesVides = 0; break;
							case 'Ma' : nbCasesVides = 1; break;
							case 'Me' : nbCasesVides = 2; break;
							case 'J' : nbCasesVides = 3; break;
							case 'V' : nbCasesVides = 4; break;
							case 'S' : nbCasesVides = 5; break;
							case 'D' : nbCasesVides = 6; break;
						}
						//alert ("premierJour="+premierJour+" / nbCasesVides="+nbCasesVides);
					}
					if ((j*1)+nbCasesVides == colonne)
						form[i].checked = 1;
				}
				break;
		}
	}
}


</script>
{/literal}


