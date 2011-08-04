{if !$ppo->isUpdated}
  <h2>Ajout d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='ajouté'}
{else}
  <h2>Modification d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='modifié'}
{/if}

{copixzone process=gestionautonome|AccountsInfo}

<p class="mesgSuccess">{$ppo->firstElement.type_nom} {$verb} !</p>

{foreach from=$ppo->sessionDatas key=k item=sessionData}
  {if $k eq 0}
      <h3>{$sessionData.type_nom} {$verb}</h3> 
  {else}
      <h3>{$sessionData.type_nom}{$k} ajouté</h3>
  {/if}
  
  <div class="field">
    <label for="personnel_name" > Nom :</label>
    <span id="personnel_name"><strong>{$sessionData.lastname}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_firstname" > Prénom :</label>
    <span id="personnel_firstname"><strong>{$sessionData.firstname}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_login" > Login :</label>
    <span id="personnel_login"><strong>{$sessionData.login}</strong></span>
  </div>

  <div class="field">
    <label for="personnel_password" > Mot de passe :</label>
    <span id="personnel_password"><strong>{$sessionData.password}</strong></span>
  </div>
{/foreach}

<div class="submit">
  {if $ppo->firstElement.bu_type == "USER_ENS"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=1}" class="button button-previous">Retour</a>
  {elseif $ppo->firstElement.bu_type == "USER_RES"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=2}" class="button button-previous">Retour</a>
  {else}
    <a href="{copixurl dest="gestionautonome||showTree"}" class="button button-previous">Retour</a>
  {/if}
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=html}" class="button button-print">Imprimer</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=csv}" class="button button-save">Télécharger</a>
</div>