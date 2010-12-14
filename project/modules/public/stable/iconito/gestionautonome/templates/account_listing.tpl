<h2>Ajout d'un {$ppo->sessionDatas[0].type_nom} dans {$ppo->sessionDatas[0].node_nom}</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|AccountsInfo}
</div>

<p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
  <strong>{$ppo->sessionDatas[0].type_nom} ajouté !</strong>
</p>

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
  
  $(document).ready(function(){
 	
 	  $('.button').button();
  
  
    jQuery('#back').click(function() {
    
      if ({/literal}'{$ppo->sessionDatas[0].bu_type}'{literal} == 'USER_ENS') {
      
        document.location.href={/literal}'{copixurl dest=gestionautonome||showTree tab=1}'{literal};
      }
      else {
      
        document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
      }
    });
  }); 
//]]> 
</script>
{/literal}