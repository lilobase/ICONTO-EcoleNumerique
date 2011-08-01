{if !$ppo->isUpdated}
  <h2>Ajout d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='ajouté'}
{else}
  <h2>Modification d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='modifié'}
{/if}

{copixzone process=gestionautonome|AccountsInfo}

<p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
  <strong>{$ppo->firstElement.type_nom} {$verb} !</strong>
</p>

{foreach from=$ppo->sessionDatas key=k item=sessionData}
  {if $k eq 0}
      <h4>{$sessionData.type_nom} {$verb}</h4> 
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

<p>
  {if $ppo->firstElement.bu_type == "USER_ENS"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=1}" class="button">Retour</a>
  {elseif $ppo->firstElement.bu_type == "USER_RES"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=2}" class="button">Retour</a>
  {else}
    <a href="{copixurl dest="gestionautonome||showTree"}" class="button">Retour</a>
  {/if}
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=html}" class="button">Imprimer</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=csv}" class="button">Télécharger</a>
</p>