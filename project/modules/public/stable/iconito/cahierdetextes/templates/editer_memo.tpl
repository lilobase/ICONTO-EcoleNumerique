{copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h2>
  {if $ppo->memo->id eq null}
    {i18n key="cahierdetextes.message.addMemo"}
  {else}
    {i18n key="cahierdetextes.message.editMemo"}
  {/if}
</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="cahierdetextes.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
	</ul>
{/if}

<form name="memo_add" id="memo_add" action="{copixurl dest="cahierdetextes||editerMemo"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
    <input type="hidden" name="memoId" id="memoId" value="{$ppo->memo->id}" />
    <input type="hidden" name="jour" id="jour" value="{$ppo->jour}" />
    <input type="hidden" name="mois" id="mois" value="{$ppo->mois}" />
    <input type="hidden" name="annee" id="annee" value="{$ppo->annee}" />
        
    <div class="field">
      <label for="memo_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.date"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
      <p class="input"><input class="form datepicker" type="text" name="memo_date_creation" id="memo_date_creation" value="{if $ppo->memo->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->memo->date_creation}{/if}" required /></p>
    </div>
      
    <div class="field">
      <label for="memo_date_validite" class="form_libelle">{i18n key="cahierdetextes.message.validityDate"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
      <p class="input"><input class="form datepicker" type="text" name="memo_date_validite" id="memo_date_validite" value="{$ppo->memo->date_validite}" required /></p>
    </div>

    <div class="textarea">
      <label for="memo_message" class="form_libelle">{i18n key="cahierdetextes.message.memo"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
      {copixzone process=kernel|edition field='memo_message' format=$ppo->format content=$ppo->memo->message height=200 width=450}
    </div>
    
    <div class="field">
      <label for="memo_fichiers" class="form_libelle">{i18n key="cahierdetextes.message.relatedDocuments"}</label>
      <div class="input">
       <span id="textButtonDelete">{i18n key="cahierdetextes.message.delete"}</span>
       <ul class="memo_fichiers" id="memo_fichiers">
        {foreach from=$ppo->fichiers item=fichier}
          <li><input type="hidden" name="memo_fichiers[]" value="{$fichier.type}-{$fichier.id}" /> <span>{$fichier.nom}</span> <a href="#" class="delete-node button button-delete">{i18n key="cahierdetextes.message.delete"}</a></li>
        {/foreach}
       </ul>
       {copixzone process=kernel|wikibuttons field='memo_fichiers' format='id' object=$ppo->nodeInfos withAlbum=false}
      </div>
    </div>
    
    <div class="field">
      <p class="label">{i18n key="cahierdetextes.message.askParentsSignature"}</p>
      <p class="input" id="fieldSignature">
        <input type="radio" name="memo_avec_signature" id="memo_avec_signature_non" value="0" checked="checked" /><label for="memo_avec_signature_non" />{i18n key="copix|copix.no"}</label>
        <input type="radio" name="memo_avec_signature" id="memo_avec_signature_oui" value="1" {if $ppo->memo->avec_signature}checked="checked"{/if} /><label for="memo_avec_signature_oui" />{i18n key="copix|copix.yes"}</label>
        <span>
        <label for="memo_date_max_signature">{i18n key="cahierdetextes.message.toSignOn"}</label> <input class="form datepicker" type="text" name="memo_date_max_signature" id="memo_date_max_signature" value="{$ppo->memo->date_max_signature}" /></span>
      </p>
    </div>
  </fieldset>
  
  <fieldset class="concernedList">
    {copixzone process=cahierdetextes|listeEleves cahierId=$ppo->cahierId elevesSelectionnes=$ppo->elevesSelectionnes}
  </fieldset>
  
  <div class="submit">
    {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
    <a href="{copixurl dest=cahierdetextes||voirMemos cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">
    {else}
    <a href="{copixurl dest=cahierdetextes||voirMemos cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
    {/if}
      <span class="button button-cancel" id="cancel">{i18n key="cahierdetextes.message.cancel"}</span>
    </a>
    <input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.save"}" />
  </div>
</form>