{literal}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<title>{/literal}{i18n key="kernel|kernel.codes.mod_annuaire"}{literal}</title>
<link href="{copixresource path="styles/iconito.css"}" rel="stylesheet" type="text/css" media="screen" />
<link href="{copixresource path="styles/module_annuaire.css"}" rel="stylesheet" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="favicon.ico">
{/literal}
<script type="text/javascript" language="Javascript1.2" src="{copixurl}js/iconito/iconito.js"></script>
<script type="text/javascript" language="Javascript1.2" src="{copixurl}js/iconito/lang_{$LANGUE}.js"></script>
<script type="text/javascript" language="Javascript1.2" src="{copixurl}js/prototype-1.6.0.3.js"></script>
{literal}
</head>

<body>

<div id="divUserProfil" onclick="hideUser();"></div>

<div class="page" style="width:500px;border:0;margin:0;margin-left: auto;	margin-right: auto;">

<div class="content">
{/literal}


<div class="title">{$TITLE_PAGE}</div>

<div class="options"><a href="javascript:self.close();">{i18n key="annuaire.btn.close"}</a></div>

<div class="main annuaire kernel">



<!-- DEBUT PAGE -->


<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_annuaire.css" />
<script language="Javascript1.2" SRC="{copixurl}js/iconito/module_annuaire.js"></script>

<div id="annu_popup_filtrage">


<form name="formGo" id="formGo" action="" method="get">
<input type="hidden" name="module" value="annuaire" />
<input type="hidden" name="action" value="getPopup" />
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="grville" value="{$grville}" />
<input type="hidden" name="profil" value="{$profil}" />


<div class="annu_popup_zone">
<b>{i18n key="annuaire.popup.browse"}</b> : <br/>
{i18n key="annuaire.ville"} : {$combovilles}<br/>
{if !$profil || $profil!='USER_VIL'}{i18n key="annuaire.ecole"} : {$comboecoles}<br/>{/if}
{if !$profil || $profil!='USER_VIL'}{i18n key="annuaire.classe"} : {$comboclasses}<br/>{/if}
{if $profil}<input type="submit" value="{i18n key="annuaire.btn.display"}" class="form_button" /><br/>{/if}
</div>


<div class="annu_popup_zone">
{if !$profil}
<b>{i18n key="annuaire.popup.display"}</b> :<br/>
{if $visib.USER_ELE != 'NONE'}<LABEL FOR="profil_ELE">{i18n key="annuaire.eleves"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_ELE" {if $profils.ELE==1}CHECKED{/if} NAME="profils[ELE]" VALUE="1" /><br/>{/if}
{if $visib.USER_ENS != 'NONE'}<LABEL FOR="profil_PEC">{i18n key="annuaire.pec"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_PEC" {if $profils.PEC==1}CHECKED{/if} NAME="profils[PEC]" VALUE="1" /><br/>{/if}
{if $visib.USER_RES != 'NONE'}<LABEL FOR="profil_PAR">{i18n key="annuaire.parents"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_PAR" {if $profils.PAR==1}CHECKED{/if} NAME="profils[PAR]" VALUE="1" /><br/>{/if}
{if $visib.USER_ADM != 'NONE'}<LABEL FOR="profil_ADM">{i18n key="annuaire.adm"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_ADM" {if $profils.ADM==1}CHECKED{/if} NAME="profils[ADM]" VALUE="1" /><br/>{/if}
{if $visib.USER_VIL != 'NONE'}<LABEL FOR="profil_VIL">{i18n key="annuaire.agents"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_VIL" {if $profils.VIL==1}CHECKED{/if} NAME="profils[VIL]" VALUE="1" /><br/>{/if}
{if $visib.USER_EXT != 'NONE'}<LABEL FOR="profil_EXT">{i18n key="annuaire.ext"}</LABEL> <INPUT TYPE="CHECKBOX" ID="profil_EXT" {if $profils.EXT==1}CHECKED{/if} NAME="profils[EXT]" VALUE="1" /><br/>{/if}


<input type="submit" value="{i18n key="annuaire.btn.display"}" class="form_button" /><br/>
{/if}
</div>
<br clear="all" /><br clear="all" /></div>

{if $eleves}
{assign var="eleveslogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.eleves"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$eleves item=item}

<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="eleveslogins" value="$eleveslogins$cat_login,"}
{counter}
{/foreach}

</table>

{if $droits.checkEleves and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_eleves="{$eleveslogins}"; var tab_eleves=logins_eleves.split(",");</script>
<a href="javascript:click_all('{$field}', 'eleves');">{i18n key="annuaire.checkAllEleves"}</a></div>{/if}

{/if}


{if $personnel}
{assign var="personnellogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.pec"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$personnel item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="personnellogins" value="$personnellogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnel and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_personnel="{$personnellogins}"; var tab_personnel=logins_personnel.split(",");</script>
<a href="javascript:click_all('{$field}', 'personnel');">{i18n key="annuaire.checkAllPersonnel"}</a></div>{/if}
{/if}

{if $parents}
{assign var="parentslogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.parents"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$parents item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="parentslogins" value="$parentslogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkParents and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_parents="{$parentslogins}"; var tab_parents=logins_parents.split(",");</script>
<a href="javascript:click_all('{$field}', 'parents');">{i18n key="annuaire.checkAllParents"}</a></div>{/if}
{/if}


{if $adm}
{assign var="admlogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.adm"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$adm item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="admlogins" value="$admlogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelAdm and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_adm="{$admlogins}"; var tab_adm=logins_adm.split(",");</script>
<a href="javascript:click_all('{$field}', 'adm');">{i18n key="annuaire.checkAllPersonnelAdm"}</a></div>{/if}
{/if}


{if $vil}
{assign var="villogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.agents"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$vil item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="villogins" value="$villogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelVil and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_vil="{$villogins}"; var tab_vil=logins_vil.split(",");</script>
<a href="javascript:click_all('{$field}', 'vil');">{i18n key="annuaire.checkAllPersonnelVil"}</a></div>{/if}
{/if}



{if $ext}
{assign var="extlogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.ext"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$ext item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="CHECKBOX" id="logins[]" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td>{$item->nom|upper|htmlentities}</td>
	<td>{$item->prenom|htmlentities}</td>
	<td>{user label=$item->login userType=$item->bu_type userId=$item->bu_id linkAttribs='STYLE="text-decoration:none;"'}</td>
</tr>
{assign var="cat_login" value=$item->login}
{assign var="extlogins" value="$extlogins$cat_login,"}
{counter}
{/foreach}
</table>
{if $droits.checkPersonnelext and cpt}<div class="annu_write_all"><script language="javascript1.2">var logins_ext="{$extlogins}"; var tab_ext=logins_ext.split(",");</script>
<a href="javascript:click_all('{$field}', 'ext');">{i18n key="annuaire.checkAllPersonnelExt"}</a></div>{/if}
{/if}



</form>


{if !$grville}
<div style="color:red; margin:20px; text-align: center;">{i18n key="annuaire.error.noGrville"}</div>
{elseif !$ville || !$ecole || !$classe}
<div style="color:red; margin:20px; text-align: center;">{i18n key="annuaire.error.chooseVal"}</div>
{/if}


{literal}
  <script language="Javascript1.2">
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


<br clear="all" />


<!-- FIN PAGE -->
{literal}

<br clear="all"/>
</div>

</div><!-- content -->
</div><!-- page -->
</body>
</html>
{/literal}