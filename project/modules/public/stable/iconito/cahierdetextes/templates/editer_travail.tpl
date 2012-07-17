{if $ppo->travail->a_faire}
  {copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee current="editerTravailAFaire"}
{else}
  {copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee current="editerTravailEnClasse"}
{/if}

{if $ppo->travail->a_faire}
  <h2>{i18n key="cahierdetextes.message.addTodoWork"}</h2>
{else}
  <h2>{i18n key="cahierdetextes.message.addClassroomWork"}</h2>
{/if}

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="cahierdetextes.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form name="travail_add" id="travail_add" action="{copixurl dest="cahierdetextes||editerTravail"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
    <input type="hidden" name="a_faire" id="a_faire" value="{$ppo->travail->a_faire}" />
    <input type="hidden" name="travailId" id="travailId" value="{$ppo->travail->id}" />
    <input type="hidden" name="jour" id="jour" value="{$ppo->jour}" />
    <input type="hidden" name="mois" id="mois" value="{$ppo->mois}" />
    <input type="hidden" name="annee" id="annee" value="{$ppo->annee}" />
    <input type="hidden" name="vue" id="vue" value="{$ppo->vue}">
    
    {if $ppo->travail->a_faire}  
      <div class="field">
        <label for="travail_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.dateGiven"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /> :</label>
        <p class="input"><input class="form datepicker" type="text" name="travail_date_creation" id="travail_date_creation" value="{if $ppo->travail->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->travail->date_creation}{/if}" required /></p>
      </div>
      
      <div class="field">
        <label for="travail_date_realisation" class="form_libelle">{i18n key="cahierdetextes.message.dateFor"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /> :</label>
        <p class="input"><input class="form datepicker" type="text" name="travail_date_realisation" id="travail_date_realisation" value="{$ppo->travail->date_realisation}" required /></p>
      </div>
    {else}
      <div class="field">
        <label for="travail_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.date"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /> :</label>
        <p class="input"><input class="form datepicker" type="text" name="travail_date_creation" id="travail_date_creation" value="{if $ppo->travail->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->travail->date_creation}{/if}" required /></p>
      </div>
    {/if}
    <div class="field">
      <label for="travail_domaine_id" class="form_libelle">{i18n key="cahierdetextes.message.domain"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /> :</label>
      <p class="input">
        {if $ppo->idsDomaine|@count eq 0}
          <a href="{copixurl dest="cahierdetextes||gererDomaines" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.noDomainCreateOne"}</a>
        {elseif $ppo->idsDomaine|@count le $ppo->nombreMaxVueRadio}
          {html_radios name='travail_domaine_id' values=$ppo->idsDomaine output=$ppo->nomsDomaine selected=$ppo->travail->domaine_id}
        {else}
          {html_options name='travail_domaine_id' values=$ppo->idsDomaine output=$ppo->nomsDomaine selected=$ppo->travail->domaine_id}
      {/if}</p>
    </div>
    {if $ppo->travail->a_faire}
      <div class="field">
        <label for="travail_a_rendre" class="form_libelle">{i18n key="cahierdetextes.message.return"} :</label>
        <p class="input"><input class="form" type="checkbox" name="travail_a_rendre" id="travail_a_rendre" value="1"{if $ppo->travail->a_rendre} checked="checked"{/if} /> <label for="travail_a_rendre">Travail à rendre dans le casier</label></p>
      </div>
    {/if}
    <div class="textarea">
      <label for="travail_description" class="form_libelle">{i18n key="cahierdetextes.message.description"} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /> :</label>
      {copixzone process=kernel|edition field='travail_description' format=$ppo->format content=$ppo->travail->description height=200 width=450}
    </div>
    <div class="field">
      <label for="travail_fichiers" class="form_libelle">{i18n key="cahierdetextes.message.relatedDocuments"} :</label>
      <div class="input">
       <span id="textButtonDelete">{i18n key="cahierdetextes.message.delete"}</span>
       <ul class="travail_fichiers" id="travail_fichiers">
        {foreach from=$ppo->fichiers item=fichier}
          <li><input type="hidden" name="travail_fichiers[]" value="{$fichier.type}-{$fichier.id}" /> <span>{$fichier.nom}</span> <a href="#" class="delete-node button button-delete">{i18n key="cahierdetextes.message.delete"}</a></li>
        {/foreach}
       </ul>
       {copixzone process=kernel|wikibuttons field='travail_fichiers' format='id' object=$ppo->nodeInfos withAlbum=false}
      </div>
    </div>
  </fieldset>
  
  <fieldset class="concernedList">
    {copixzone process=cahierdetextes|listeEleves cahierId=$ppo->cahierId elevesSelectionnes=$ppo->elevesSelectionnes}
  </fieldset>
  
  <div class="field redirectionField">
    <label for="travail_redirection" class="form_libelle">{i18n key="cahierdetextes.message.whatWouldYouDo"}</label>
    <p class="input"><label><input type="radio" name="travail_redirection" value="0" {if $ppo->travail_redirection eq 0}checked{/if} /> {i18n key="cahierdetextes.message.backWorks"}</label>
    <label><input type="radio" name="travail_redirection" value="1" {if $ppo->travail_redirection eq 1}checked{/if} /> {i18n key="cahierdetextes.message.addClassroomWork"}</label>
    <label><input type="radio" name="travail_redirection" value="2" {if $ppo->travail_redirection eq 2}checked{/if} /> {i18n key="cahierdetextes.message.addTodoWork"}</label></p>
  </div>
  
  <div class="submit">
    {if $ppo->vue eq "liste"}
      {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
        <a href="{copixurl dest=cahierdetextes||voirListeTravaux cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">
      {else}
        <a href="{copixurl dest=cahierdetextes||voirListeTravaux cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
      {/if}
    {elseif $ppo->vue eq "domaine"}
      {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
        <a href="{copixurl dest=cahierdetextes||voirTravauxParDomaine cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">
      {else}
        <a href="{copixurl dest=cahierdetextes||voirTravauxParDomaine cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
      {/if}
    {else}
      {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
        <a href="{copixurl dest=cahierdetextes||voirTravaux cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">
      {else}
        <a href="{copixurl dest=cahierdetextes||voirTravaux cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
      {/if}
    {/if}
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="cahierdetextes.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.save"}" />
  </div>
</form>