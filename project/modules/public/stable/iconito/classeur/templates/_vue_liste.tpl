<div class="overflow">
  <table id="folder-content" class="listView">
    <thead>
      <tr>
        <th class="left"><input type="checkbox" id="check_all" /></th>
        <th>
          {if $ppo->tri.colonne eq "titre" && $ppo->tri.direction eq "ASC"}
            {assign var=direction value='DESC'}
          {else}
            {assign var=direction value='ASC'}
          {/if}
          <a class="{if $ppo->tri.colonne eq "titre"}{$direction}{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId triColonne='nom' triDirection=$direction}">{i18n key="classeur.message.title"}</a>
        </th>
        <th>
          {if $ppo->tri.colonne eq "origine" && $ppo->tri.direction eq "ASC"}
            {assign var=direction value='DESC'}
          {else}
            {assign var=direction value='ASC'}
          {/if}
          <a class="{if $ppo->tri.colonne eq "origine"}{$direction}{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId triColonne='origine' triDirection=$direction}">{i18n key="classeur.message.origine"}</a>
        </th>
        <th>
          {if $ppo->tri.colonne eq "type" && $ppo->tri.direction eq "ASC"}
            {assign var=direction value='DESC'}
          {else}
            {assign var=direction value='ASC'}
          {/if}
          <a class="{if $ppo->tri.colonne eq "type"}{$direction}{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId triColonne='type' triDirection=$direction}">{i18n key="classeur.message.type"}</a>
        </th>
        <th>
          {if $ppo->tri.colonne eq "date" && $ppo->tri.direction eq "ASC"}
            {assign var=direction value='DESC'}
          {else}
            {assign var=direction value='ASC'}
          {/if}
          <a class="{if $ppo->tri.colonne eq "date"}{$direction}{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId triColonne='date' triDirection=$direction}">{i18n key="classeur.message.date"}</a>
        </th>
        <th>
          {if $ppo->tri.colonne eq "taille" && $ppo->tri.direction eq "ASC"}
            {assign var=direction value='DESC'}
          {else}
            {assign var=direction value='ASC'}
          {/if}
          <a class="{if $ppo->tri.colonne eq "taille"}{$direction}{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId triColonne='taille' triDirection=$direction}">{i18n key="classeur.message.size"}</a>
        </th>
        <th>{i18n key="classeur.message.actions"}</th>
      </tr>
    </thead>
    <tbody>
      {assign var=index value=1}
      {if $ppo->dossierParent}
        <tr class="folder even">
          <td>&nbsp;</td>
          <td><a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierParent->id}" title="{i18n key="classeur.message.openFolder" nom=$ppo->dossierParent->nom noEscape=1}" class="icon iconFolderUp">{i18n key="classeur.message.parentFolder"}</a></td>
          <td colspan="5">&nbsp;</td>
        </tr>
        {assign var=index value=2}
      {elseif $ppo->classeurParent}
        <tr class="folder even">
          <td>&nbsp;</td>
          <td>
            {if $ppo->classeurParent->isPersonnel}
              {i18n key="classeur.message.personnalFolder" assign=nom}
            {else}
              {assign var=nom value=$ppo->classeurParent->titre}
            {/if}
            <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId}" title="{i18n key="classeur.message.openFolder" nom=$nom noEscape=1}" class="icon iconFolderUp">{i18n key="classeur.message.parentFolder"}</a>
          </td>
          <td colspan="5">&nbsp;</td>
        </tr>
        {assign var=index value=2}
      {/if}
      
      
      {foreach from=$ppo->contenus item=contenu}
      
      <!-- Affichage des dossiers -->
      {if $contenu->content_type eq "dossier"}
        <tr class="folder {if $index%2 eq 0}odd{else}even{/if}">
          <td>
            {if ($ppo->dossierId eq 0 && !$contenu->casier) || $ppo->dossierId neq 0}
              <input type="checkbox" class="check" name="dossiers[]" value="{$contenu->id}"{if $contenu->casier} data-locker=1{/if} />
            {/if}
          </td>
          <td><a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.openFolder" nom=$contenu->titre noEscape=1}" class="icon iconFolder {if $contenu->casier}iconFolderLocked{/if}">{$contenu->titre|escape}</a></td>
          <td class="center">&nbsp;</td>
          <td class="center">{$contenu->type}</td>
          <td>{$contenu->date|datei18n:"date_short_time"}</td>
          <td class="right">
            {if $contenu->nb_dossiers neq 0}
              {$contenu->nb_dossiers} {if $contenu->nb_dossiers eq 1}{i18n key="classeur.message.folder"}{else}{i18n key="classeur.message.folders"}{/if}
            {/if}
            {if $contenu->nb_fichiers neq 0}
              {$contenu->nb_fichiers} {if $contenu->nb_fichiers eq 1}{i18n key="classeur.message.file"}{else}{i18n key="classeur.message.files"}{/if}
            {/if}
            {$contenu->taille|human_file_size}
          </td>
          <td class="center actions">
            {if ($ppo->dossierId eq 0 && !$contenu->casier) || $ppo->dossierId neq 0}
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
                <a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId dossierId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
                  <img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="classeur.message.modify"}" />
                </a>
              {/if}
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
                <a href="{copixurl dest="classeur||deplacerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId dossierId=$contenu->id}" title="{i18n key="classeur.message.move"}"{if $contenu->casier} onclick="return confirm('{i18n key="classeur.message.moveLockerConfirm"}')"{/if}>
                  <img src="{copixurl}themes/default/images/button-action/action_move.png" alt="{i18n key="classeur.message.move"}" />
                </a>
                <a href="{copixurl dest="classeur||supprimerDossier" classeurId=$ppo->classeurId dossierId=$contenu->id}" onclick="return confirm('{if $contenu->casier}{i18n key="classeur.message.deleteLockerConfirm"}{else}{i18n key="classeur.message.deleteFolderConfirm"}{/if}')" title="{i18n key="classeur.message.delete"}">
                  <img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
                </a>
              {/if}
            {/if}
          </td>
        </tr>
      {elseif $contenu->content_type eq "fichier"}
      
        <!-- Affichage des favoris -->
        {if $contenu->fichier|substr:-4 eq ".web"}
          <tr class="{$contenu->type} {if $index%2 eq 0}odd{else}even{/if}">
            <td><input type="checkbox" class="check" name="fichiers[]" value="{$contenu->id}" /></td>
            <td><a href="{$contenu->lien}" title="{i18n key="classeur.message.openFile" titre=$contenu->nom noEscape=1}" class="icon iconFavorite" target="_blank">{$contenu->titre|escape}</a></td>
            <td class="center">{$contenu->origine}</td>
            <td class="center">{$contenu->type}</td>
            <td>{$contenu->date|datei18n:"date_short_time"}</td>
            <td class="right">{$contenu->taille|human_file_size}</td>
            <td class="center actions">
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              <a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$contenu->parent_id favoriId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
                <img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="classeur.message.modify"}" />
              </a>
              {/if}
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$contenu->parent_id fichierId=$contenu->id}" title="{i18n key="classeur.message.move"}">
                <img src="{copixurl}themes/default/images/button-action/action_move.png" alt="{i18n key="classeur.message.move"}" />
              </a>
              <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$contenu->dossier_id fichierId=$contenu->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
                <img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
              </a>
              {/if}
            </td>
          </tr>
          
        <!-- Affichage des fichiers -->
        {else}
          {if $contenu->titre neq null}
            {assign var=titre value=$contenu->titre}
          {else}
            {assign var=titre value=$contenu->fichier}
          {/if}
          
          <tr class="{if $index%2 eq 0}odd{else}even{/if}">
            <td><input type="checkbox" class="check" name="fichiers[]" value="{$contenu->id}" /></td>
            <td><a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$contenu->id}" title="{i18n key="classeur.message.openFile" titre=$titre noEscape=1}" class="icon icon{$contenu->type|lower}" target="_blank">{$titre}</a></td>
            <td class="center">{$contenu->origine}</td>
            <td class="center">{$contenu->type}</td>
            <td>{$contenu->date|datei18n:"date_short_time"}</td>
            <td class="right">{$contenu->taille|human_file_size}</td>
            <td class="center actions">
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              <a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$contenu->parent_id fichierId=$contenu->id}" title="{i18n key="classeur.message.modify"}">
                <img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="classeur.message.modify"}" />
              </a>
              {/if}
              {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($contenu->user_id eq $ppo->idUtilisateur && $contenu->user_type eq $ppo->typeUtilisateur)}
              <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$contenu->parent_id fichierId=$contenu->id}" title="{i18n key="classeur.message.move"}">
                <img src="{copixurl}themes/default/images/button-action/action_move.png" alt="{i18n key="classeur.message.move"}" />
              </a>
              <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$contenu->parent_id fichierId=$contenu->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
                <img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
              </a>
              {/if}
            </td>
          </tr>
        {/if}
      {/if}
      {assign var=index value=$index+1}
      {/foreach}
    </tbody>
  </table>
</div>
{copixzone process=classeur|actionsDeMasse classeurId=$ppo->classeurId dossierId=$ppo->dossierId}