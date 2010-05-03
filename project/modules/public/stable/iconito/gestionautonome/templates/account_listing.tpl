<h2>Ajout d'un {$ppo->sessionDatas[0].type_nom} dans {$ppo->sessionDatas[0].node_nom}</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|AccountsInfo}
</div>

<div style="margin-top: 20px; padding: 10px 0.7em 0 0.7em;" class="notice-light ui-state-highlight ui-corner-all"> 
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
	<strong>{$ppo->sessionDatas[0].type_nom} ajouté !</strong>
</div>

{foreach from=$ppo->sessionDatas key=k item=sessionData}

  {if $k eq 0}
    <h4>{$sessionData.type_nom} ajouté</h4> 
  {else}
    <h4>{$sessionData.type_nom}{$k} ajouté</h4>
  {/if}
  
  <div class="field">
    <label for="personnel_name" class="form_libelle"> Nom :</label>
    <span id="personnel_name"><strong>{$sessionData.lastname}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_firstname" class="form_libelle"> Prénom :</label>
    <span id="personnel_firstname"><strong>{$sessionData.firstname}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_login" class="form_libelle"> Login :</label>
    <span id="personnel_login"><strong>{$sessionData.login}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_password" class="form_libelle"> Mot de passe :</label>
    <span id="personnel_password"><strong>{$sessionData.password}</strong></span>
  </div>
{/foreach}

<ul class="actions">
  <li><input class="button" type="button" value="Retour" id="back" /></li>
</ul>        

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();

  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#back').click(function() {
    
    if ({/literal}'{$ppo->sessionDatas[0].bu_type}'{literal} == 'USER_ENS') {
      
      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType tab=1 notxml=true}'{literal};
    }
    else {
      
      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
    }
  });
//]]> 
</script>
{/literal}