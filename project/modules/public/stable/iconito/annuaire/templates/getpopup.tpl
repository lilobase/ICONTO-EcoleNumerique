
<script>
    var $field = '{$field}';
</script>
<style>
{literal}
.headerSortUp {
    background:red;
    }
{/literal}
</style>

<div id="annu_popup_filtrage" class="block">


<form name="formGo" id="formGo" action="{copixurl dest="annuaire||getPopup"}" method="get">
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="grville" value="{$grville}" />
<input type="hidden" name="profil" value="{$profil}" />
<input type="hidden" name="right" value="{$right}" />


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


{if $users}
    <p class="explain">{i18n key="annuaire.popup.explain1"} <span>{i18n key="annuaire.popup.explain2"}</span></p>
    {counter start=0 assign="cpt"}
    <table class="liste tablesorter">
        <thead>
            <tr>
                <th class="{literal}{sorter: false}{/literal}"></th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Nom d'utilisateur</th>
                <th>Profil</th>
            </tr>    
        </thead>
        <tbody>
        {foreach from=$users item=user}
            <tr class="list_line{$cpt%2}">
                <td><input type="checkbox" value="{$user->login}" class="enUser" /></td>
                <td>{$user->nom|escape|upper}</td>
                <td>{$user->prenom|escape}</td>
                <td>{user label=$user->login userType=$user->bu_type userId=$user->bu_id}</td>
                <td>{$user->bu_type|profil}</td>
            </tr>
            {counter}
        {/foreach}
        </tbody>

    </table>
        
    {if $droits.checkAll}<p class="annu_write_all">{i18n key="annuaire.select"} <a href="#" class="enSelect enSelectAll" data-all="1">{i18n key="annuaire.selectAll"}</a>, <a href="#" class="enSelect enSelectNone" data-all="0">{i18n key="annuaire.selectNone"}</a></p>{/if}    

{else}
    <p class="mesgInfo">{i18n key="annuaire.popup.noUsers"}</p>
{/if}

</form>


{if !$grville}
    <p class="mesgError">{i18n key="annuaire.error.noGrville"}</p>
{elseif !$ville || !$ecole || !$classe}
    <p class="mesgInfo">{i18n key="annuaire.error.chooseVal"}</p>
{/if}

<p class="endForm"></p>

