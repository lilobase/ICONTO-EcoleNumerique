

<div id="annu_popup_filtrage" class="block">


<form name="formGo" id="formGo" action="{copixurl dest="annuaire||getPopup"}" method="get">
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="grville" value="{$grville}" />
<input type="hidden" name="profil" value="{$profil}" />


<div class="annu_popup_zone">
<b>{i18n key="annuaire.popup.browse"}</b> : <br/>
{i18n key="annuaire.ville"} : {$combovilles}<br/>
{if !$profil || $profil!='USER_VIL'}{i18n key="annuaire.ecole"} : {$comboecoles}<br/>{/if}
{if !$profil || $profil!='USER_VIL'}{i18n key="annuaire.classe"} : {$comboclasses}<br/>{/if}
{if $profil}<input type="submit" value="{i18n key="annuaire.btn.display"}" class="button button-confirm" /><br />{/if}
</div>


<div class="annu_popup_zone">
{if !$profil}
<b>{i18n key="annuaire.popup.display"}</b> :<br/>
{if $visib.USER_ELE}<label for="profil_ELE">{i18n key="annuaire.eleves"}</label> <input type="checkbox" id="profil_ELE" {if $profils.ELE==1}checked="checked"{/if} name="profils[ELE]" value="1" /><br />{/if}
{if $visib.USER_ENS}<label for="profil_PEC">{i18n key="annuaire.pec"}</label> <input type="checkbox" id="profil_PEC" {if $profils.PEC==1}checked="checked"{/if} name="profils[PEC]" value="1" /><br />{/if}
{if $visib.USER_RES}<label for="profil_PAR">{i18n key="annuaire.parents"}</label> <input type="checkbox" id="profil_PAR" {if $profils.PAR==1}checked="checked"{/if} name="profils[PAR]" value="1" /><br />{/if}
{if $visib.USER_ADM}<label for="profil_ADM">{i18n key="annuaire.adm"}</label> <input type="checkbox" id="profil_ADM" {if $profils.ADM==1}checked="checked"{/if} name="profils[ADM]" value="1" /><br />{/if}
{if $visib.USER_VIL}<label for="profil_VIL">{i18n key="annuaire.agents"}</label> <input type="checkbox" id="profil_VIL" {if $profils.VIL==1}checked="checked"{/if} name="profils[VIL]" value="1" /><br />{/if}
{if $visib.USER_EXT}<label for="profil_EXT">{i18n key="annuaire.ext"}</label> <input type="checkbox" id="profil_EXT" {if $profils.EXT==1}checked="checked"{/if} name="profils[EXT]" value="1" /><br />{/if}


<input type="submit" value="{i18n key="annuaire.btn.display"}" class="button button-confirm" /><br/>
{/if}
</div>
<br class="clearBoth" /><br /></div>

{if $eleves}
{assign var="eleveslogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.eleves"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$eleves item=item}

<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|escape|upper}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="eleveslogins" value="$eleveslogins$cat_login,"}
{counter}
{/foreach}

</table>

{if $droits.checkEleves and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_eleves="{$eleveslogins}"; var tab_eleves=logins_eleves.split(",");</script>
<a href="javascript:click_all('{$field}', 'eleves');">{i18n key="annuaire.checkAllEleves"}</a></div>{/if}

{/if}


{if $personnel}
{assign var="personnellogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.pec"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$personnel item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|escape}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="personnellogins" value="$personnellogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnel and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_personnel="{$personnellogins}"; var tab_personnel=logins_personnel.split(",");</script>
<a href="javascript:click_all('{$field}', 'personnel');">{i18n key="annuaire.checkAllPersonnel"}</a></div>{/if}
{/if}

{if $parents}
{assign var="parentslogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.parents"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$parents item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|escape}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="parentslogins" value="$parentslogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkParents and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_parents="{$parentslogins}"; var tab_parents=logins_parents.split(",");</script>
<a href="javascript:click_all('{$field}', 'parents');">{i18n key="annuaire.checkAllParents"}</a></div>{/if}
{/if}


{if $adm}
{assign var="admlogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.adm"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$adm item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|escape}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="admlogins" value="$admlogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelAdm and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_adm="{$admlogins}"; var tab_adm=logins_adm.split(",");</script>
<a href="javascript:click_all('{$field}', 'adm');">{i18n key="annuaire.checkAllPersonnelAdm"}</a></div>{/if}
{/if}


{if $vil}
{assign var="villogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.agents"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$vil item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|escape}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="villogins" value="$villogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelVil and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_vil="{$villogins}"; var tab_vil=logins_vil.split(",");</script>
<a href="javascript:click_all('{$field}', 'vil');">{i18n key="annuaire.checkAllPersonnelVil"}</a></div>{/if}
{/if}



{if $ext}
{assign var="extlogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.ext"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$ext item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|escape}</td>
	<td>{$item->prenom|escape}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="extlogins" value="$extlogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelext and cpt}<div class="annu_write_all"><script type="text/javascript">var logins_ext="{$extlogins}"; var tab_ext=logins_ext.split(",");</script>
<a href="javascript:click_all('{$field}', 'ext');">{i18n key="annuaire.checkAllPersonnelExt"}</a></div>{/if}
{/if}



</form>


{if !$grville}
<div style="color:red; margin:20px; text-align: center;">{i18n key="annuaire.error.noGrville"}</div>
{elseif !$ville || !$ecole || !$classe}
<div style="color:red; margin:20px; text-align: center;">{i18n key="annuaire.error.chooseVal"}</div>
{/if}


{literal}
  <script type="text/javascript">
    if (window.opener != null) {
    var logins = window.opener.getRef ('{/literal}{$field}{literal}');
    if (logins != null) {
        select = logins.value.replace(/ /g,"");
        select = select.split(',');
				//alert (select);
        var form  = getRef ('formGo');
        for (var i=0 ; i < form.length; i++) {
	    	if (form[i].name==("logins[]")) {
                for (var j=0, trouve=false; !trouve && j < select.length; j++) {
                    if (form[i].value == select[j]) {
                        trouve = true;
                        form[i].checked = true;
                    }
                }
            }
        }
    }
    }
    </script>
{/literal}


