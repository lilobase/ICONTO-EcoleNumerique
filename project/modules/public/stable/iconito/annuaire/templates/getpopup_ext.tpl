

<form name="formGo" id="formGo" action="{copixurl dest="annuaire||getPopup"}" method="get">
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="grville" value="{$grville}" />
<input type="hidden" name="profil" value="{$profil}" />


<p class="explain">{i18n key="annuaire.popup.explain1"} <span>{i18n key="annuaire.popup.explain2"}</span></p>



{if $ext}
{assign var="extlogins" value=""}
{counter start=0 assign="cpt"}
<h3>{i18n key="annuaire.ext"}</h3>
<table border="0" class="liste" align="center" cellspacing="2" cellpadding="2">
{foreach from=$ext item=item}
<tr class="list_line{$cpt%2}">
	<td><input type="checkbox" id="logins_{$item->login}" name="logins[]" {$checked} value="{$item->login}" onClick="return window.opener.click_destin('{$item->login}', '{$field}');" /></td>
	<td><label for="logins_{$item->login}">{$item->nom|escape|upper}</label></td>
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
{else}
<p class="explain error">Aucune personne externe...</p>
{/if}



</form>



<p class="endForm"></p>


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


