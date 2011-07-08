{if $ppo->dossiers neq null || $ppo->fichiers neq null}
  <form name="order-content" id="order-content" action="{copixurl dest="classeur||voirContenu"}" method="post">
    <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
    <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
    {i18n key="classeur.message.orderBy"}
    <select name="triColonne" id="order-column">
      <option value="nom" {if $ppo->tri.triDossiers eq "nom" && $ppo->tri.triFichiers eq "titre"}selected="selected"{/if}>{i18n key="classeur.message.title"}</option>
      <option value="type" {if $ppo->tri.triFichiers eq "type"}selected="selected"{/if}>{i18n key="classeur.message.type"}</option>
      <option value="date" {if $ppo->tri.triDossiers eq "date_creation" && $ppo->tri.triFichiers eq "date_upload"}selected="selected"{/if}>{i18n key="classeur.message.date"}</option>
      <option value="taille" {if $ppo->tri.triFichiers eq "taille" && $ppo->tri.triDossiers eq "taille"}selected="selected"{/if}>{i18n key="classeur.message.size"}</option>
    </select>
    <select name="triDirection" id="order-direction">
      <option value="ASC" {if $ppo->tri.triDirection eq "ASC"}selected="selected"{/if}>{i18n key="classeur.message.asc"}</option>
      <option value="DESC" {if $ppo->tri.triDirection eq "DESC"}selected="selected"{/if}>{i18n key="classeur.message.desc"}</option>
    </select>
  </form>
  <div class="overflow">
    <div id="folder-content">
    <ul class="thumbView">
    {assign var=index value=1}
    {foreach from=$ppo->dossiers item=dossier}
      <li class="folder">
        <div class="folder-datas">
          <input type="checkbox" class="check" name="dossiers[]" value="{$dossier->id}" />
          <span class="name">
            <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$dossier->id}" title="{i18n key="classeur.message.openFolder" nom=$dossier->nom noEscape=1}">{$dossier->nom|escape}</a><br />
            {$dossier->date_creation|datei18n:"date_short_time"|substr:0:10}
          </span>
        </div>
        <p class="folder-actions">
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
          <a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId dossierId=$dossier->id}" title="{i18n key="classeur.message.modify"}">
            <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="classeur.message.modify"}" />
          </a>
          {/if}
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
          <a href="{copixurl dest="classeur||deplacerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId dossierId=$dossier->id}" title="{i18n key="classeur.message.move"}">
            <img src="{copixurl}themes/default/images/action_move.png" alt="{i18n key="classeur.message.move"}" />
          </a>
          <a href="{copixurl dest="classeur||supprimerDossier" classeurId=$ppo->classeurId dossierId=$dossier->id}" onclick="return confirm('{i18n key="classeur.message.deleteFolderConfirm"}')" title="{i18n key="classeur.message.delete"}">
            <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
          </a>
          {/if}
          {if $dossier->nb_dossiers neq 0}
            {$dossier->nb_dossiers} {if $dossier->nb_dossiers eq 1}dossier{else}dossiers{/if}
          {/if}
          {if $dossier->nb_fichiers neq 0}
            {$dossier->nb_fichiers} {if $dossier->nb_fichiers eq 1}fichier{else}fichiers{/if}
          {/if}
          {$dossier->taille|human_file_size}
        </p>
      </li>
      {assign var=index value=$index+1}
    {/foreach}
  
    {foreach from=$ppo->fichiers item=fichier}
      <li class="file">
        <div class="file-datas">
          {if $fichier->estUneImage()}
            <img src="{$fichier->getLienMiniature(90)}" />
          {/if}
          <input type="checkbox" class="check" name="fichiers[]" value="{$fichier->id}" />
          <span class="name">
            {if $fichier->estUnFavori()}
              <a href="{$fichier->getLienFavori()}" title="{i18n key="classeur.message.openFile" titre=$fichier noEscape=1}" target="_blank">{$fichier->titre|escape}</a>
            {else}
              <a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$fichier->id}" title="{i18n key="classeur.message.openFile" titre=$fichier noEscape=1}" target="_blank">{$fichier}</a>
            {/if}
            <br />{$fichier->date_creation|datei18n:"date_short_time"|substr:0:10}
          </span>
        </div>
        <p class="file-actions">
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
            {if $fichier->estUnFavori()}
            <a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id favoriId=$fichier->id}" title="{i18n key="classeur.message.modify"}">
            {else}
            <a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" title="{i18n key="classeur.message.modify"}">
            {/if}
              <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="classeur.message.modify"}" />
            </a>
          {/if}
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
            <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" title="{i18n key="classeur.message.move"}">
              <img src="{copixurl}themes/default/images/action_move.png" alt="{i18n key="classeur.message.move"}" />
            </a>
            <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
              <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
            </a>
          {/if}
          {if $fichier->estUnFavori()}{i18n key="classeur.message.favorite"}{else}{$fichier->getExtension()}{/if}
          {$fichier->taille|human_file_size}
        </p>
      </li>
    {assign var=index value=$index+1}
    {/foreach}
    </ul>
    </div>
  </div>
  {copixzone process=classeur|actionsDeMasse classeurId=$ppo->classeurId dossierId=$ppo->dossierId}
{else}
  <p id="folder-content"><span>{i18n key="classeur.message.noFiles"}</span></p>
{/if}