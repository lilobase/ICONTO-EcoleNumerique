<p id="select-choice">
   <input type="checkbox" id="selectAllThumbs" /><label for="selectAllThumbs">{i18n key="classeur.message.selectAll"}</label>
</p>
<form name="order-content" id="order-content" action="{copixurl dest="classeur||voirContenu"}" method="post">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  {i18n key="classeur.message.orderBy"}
  <select name="triColonne" id="order-column">
    <option value="nom" {if $ppo->tri.colonne eq "nom"}selected="selected"{/if}>{i18n key="classeur.message.title"}</option>
		<option value="origine" {if $ppo->tri.colonne eq "origine"}selected="selected"{/if}>{i18n key="classeur.message.origine"}</option>
    <option value="type" {if $ppo->tri.colonne eq "type"}selected="selected"{/if}>{i18n key="classeur.message.type"}</option>
    <option value="date" {if $ppo->tri.colonne eq "date"}selected="selected"{/if}>{i18n key="classeur.message.date"}</option>
    <option value="taille" {if $ppo->tri.colonne eq "taille"}selected="selected"{/if}>{i18n key="classeur.message.size"}</option>
  </select>
  <select name="triDirection" id="order-direction">
    <option value="ASC" {if $ppo->tri.direction eq "ASC"}selected="selected"{/if}>{i18n key="classeur.message.asc"}</option>
    <option value="DESC" {if $ppo->tri.direction eq "DESC"}selected="selected"{/if}>{i18n key="classeur.message.desc"}</option>
  </select>
</form>

<div class="overflow">
  <div id="folder-content" class="thumbView">
  <ul>
    <!-- Affichage du dossier parent -->
    {if $ppo->dossierParent}
      <li class="folder">
        <div class="datas">
          <a class="icon iconFolderUp" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierParent->id}" title="{i18n key="classeur.message.openFolder" nom=$ppo->dossierParent->nom noEscape=1}"><img src="{copixurl}themes/default/images/icon-128/icon-folder-up.png" /></a>
          <p class="footerData footerFolderUp">
            <span class="name">
              <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.openFolder" nom=$nom noEscape=1}">{i18n key="classeur.message.parentFolder"}</a>
            </span>
          </p>
        </div>
      </li>
    {elseif $ppo->classeurParent}
      <li class="folder">
        <div class="datas">
          {if $ppo->classeurParent->isPersonnel}
            {i18n key="classeur.message.personnalFolder" assign=nom}
          {else}
            {assign var=nom value=$ppo->classeurParent->titre}
          {/if}
          <a class="icon iconFolderUp" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId}" title="{i18n key="classeur.message.openFolder" nom=$nom noEscape=1}"><img src="{copixurl}themes/default/images/icon-128/icon-folder-up.png" /></a>
          <p class="footerData footerFolderUp">
            <span class="name">
              <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.openFolder" nom=$nom noEscape=1}">{i18n key="classeur.message.parentFolder"}</a>
            </span>
          </p>
        </div>
      </li>
    {/if}
    
    {assign var=index value=1}
    {foreach from=$ppo->contenus item=contenu}
      <!-- Affichage des dossiers -->
      {if $contenu->content_type eq "dossier"}
        <li class="folder">
          <div class="datas">
            <a class="icon" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.openFolder" nom=$contenu->titre noEscape=1}"><img src="{copixurl}themes/default/images/icon-128/icon-folder{if $contenu->casier}-locked{/if}.png" /></a>
            <p class="footerData">
                {if ($ppo->dossierId eq 0 && !$contenu->casier) || $ppo->dossierId neq 0}
                  <input type="checkbox" class="check" name="dossiers[]" value="{$contenu->id}"{if $contenu->casier} data-locker=1{/if} />
                {/if}
                <span class="name">
                  <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.openFolder" nom=$contenu->titre noEscape=1}">{$contenu->titre|escape}</a><br />
                  <span class="date">{$contenu->date|datei18n:"date_short_time"}</span>
                </span>
            </p>
          </div>
          {if ($ppo->dossierId eq 0 && !$contenu->casier) || $ppo->dossierId neq 0}
            <p class="actions">
              <span class="size"></span>
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
                <a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
                  <img src="{copixurl}themes/default/images/icon-16/action-update.png" alt="{i18n key="classeur.message.modify"}" />
                </a>
              {/if}
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
                <a href="{copixurl dest="classeur||deplacerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId dossierId=$contenu->id}" title="{i18n key="classeur.message.move"}"{if $contenu->casier} onclick="return confirm('{i18n key="classeur.message.moveLockerConfirm"}')"{/if}>
                  <img src="{copixurl}themes/default/images/icon-16/action-move.png" alt="{i18n key="classeur.message.move"}" />
                </a>
                <a href="{copixurl dest="classeur||supprimerDossier" classeurId=$ppo->classeurId dossierId=$contenu->id}" onclick="return confirm('{if $contenu->casier}{i18n key="classeur.message.deleteLockerConfirm"}{else}{i18n key="classeur.message.deleteFolderConfirm"}{/if}')" title="{i18n key="classeur.message.delete"}">
                  <img src="{copixurl}themes/default/images/icon-16/action-delete.png" alt="{i18n key="classeur.message.delete"}" />
                </a>
              {/if}
            </p>
          {/if}
        </li>
  
      {elseif $contenu->content_type eq "fichier"}
  
        {if $contenu->titre neq null}
          {assign var=titre value=$contenu->titre}
        {else}
          {assign var=titre value=$contenu->fichier}
        {/if}
  
        <!-- Affichage des fichiers -->
        <li class="file">
          <div class="datas">
            {if $contenu->lienMiniature neq null}
              <a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$contenu->id}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" target="_blank"><img src="{$contenu->lienMiniature}" /></a>
            {elseif $contenu->fichier|substr:-4 eq ".web"}
              <a class="icon" href="{$contenu->lien}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" target="_blank"><img src="{copixurl}themes/default/images/icon-128/icon-favorite.png" /></a>
            {else}
              <a class="icon" href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$contenu->id}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" target="_blank"><img src="{copixurl}themes/default/images/icon-128/{if in_array($contenu->type|lower,$ppo->fileExtensionAllowed)}icon-{$contenu->type|lower}.png{else}icon-file.png{/if}" alt="{$contenu->titre}" /></a>
            {/if}
            <p class="footerData">
                <input type="checkbox" class="check" name="fichiers[]" value="{$contenu->id}" />
                <span class="name">
                  {if $contenu->fichier|substr:-4 eq ".web"}
                    <a href="{$contenu->lien}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" target="_blank">{$titre|escape}</a>
                  {else}
                    <a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$contenu->id}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" target="_blank">{$titre|substr:0:50}</a>
                  {/if}
                  <br />{$contenu->origine}
                  <br /><span class="date">{$contenu->type} - {$contenu->date|datei18n:"date_short_time"}</span>
                </span>
            </p>
          </div>
          <p class="actions">
            <span class="size">{$contenu->taille|human_file_size}</span>
            {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              {if $contenu->fichier|substr:-4 eq ".web"}
              	<a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$contenu->dossier_id favoriId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
              {else}
              	<a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$contenu->dossier_id fichierId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
              {/if}
                <img src="{copixurl}themes/default/images/icon-16/action-update.png" alt="{i18n key="classeur.message.modify"}" />
              </a>
            {/if}
            {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$contenu->dossier_id fichierId=$contenu->id}" title="{i18n key="classeur.message.move"}">
                <img src="{copixurl}themes/default/images/icon-16/action-move.png" alt="{i18n key="classeur.message.move"}" />
              </a>
              <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$contenu->dossier_id fichierId=$contenu->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
                <img src="{copixurl}themes/default/images/icon-16/action-delete.png" alt="{i18n key="classeur.message.delete"}" />
              </a>
            {/if}
          </p>
        </li>
      {assign var=index value=$index+1}
      {/if}
    {/foreach}
  </ul>
  </div>
</div>
{copixzone process=classeur|actionsDeMasse classeurId=$ppo->classeurId dossierId=$ppo->dossierId}