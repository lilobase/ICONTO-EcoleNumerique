{if $ppo->dossiers neq null || $ppo->fichiers neq null}
<table id="folder-content">
  <thead>
    <tr>
      <th class="left"><input type="checkbox" id="check_all" /></th>
      <th>
        {if $ppo->tri.triDossiers eq "nom" && $ppo->tri.triFichiers eq "titre" && $ppo->tri.triDirection eq "ASC"}
          {assign var=triDirection value='DESC'}
        {else}
          {assign var=triDirection value='ASC'}
        {/if}
        <a class="{if $ppo->tri.triDossiers eq "nom" && $ppo->tri.triFichiers eq "titre"}{$triDirection}{/if}" href="{copixurl dest="classeur||voirContenu" vue='liste' classeurId=$ppo->classeurId dossierId=$ppo->dossierId triDossiers='nom' triFichiers='titre' triDirection=$triDirection}">{i18n key="classeur.message.title"}</a>
      </th>
      <th>
        {if $ppo->tri.triFichiers eq "type" && $ppo->tri.triDirection eq "ASC"}
          {assign var=triDirection value='DESC'}
        {else}
          {assign var=triDirection value='ASC'}
        {/if}
        <a class="{if $ppo->tri.triFichiers eq "type"}{$triDirection}{/if}" href="{copixurl dest="classeur||voirContenu" vue='liste' classeurId=$ppo->classeurId dossierId=$ppo->dossierId triFichiers='type' triDirection=$triDirection}">{i18n key="classeur.message.type"}</a>
      </th>
      <th>
        {if $ppo->tri.triDossiers eq "date_creation" && $ppo->tri.triFichiers eq "date_upload" && $ppo->tri.triDirection eq "ASC"}
          {assign var=triDirection value='DESC'}
        {else}
          {assign var=triDirection value='ASC'}
        {/if}
        <a class="{if $ppo->tri.triDossiers eq "date_creation" && $ppo->tri.triFichiers eq "date_upload"}{$triDirection}{/if}" href="{copixurl dest="classeur||voirContenu" vue='liste' classeurId=$ppo->classeurId dossierId=$ppo->dossierId triDossiers='date_creation' triFichiers='date_upload' triDirection=$triDirection}">{i18n key="classeur.message.date"}</a>
      </th>
      <th>
        {if $ppo->tri.triFichiers eq "taille" && $ppo->tri.triDirection eq "ASC"}
          {assign var=triDirection value='DESC'}
        {else}
          {assign var=triDirection value='ASC'}
        {/if}
        <a class="{if $ppo->tri.triFichiers eq "taille"}{$triDirection}{/if}" href="{copixurl dest="classeur||voirContenu" vue='liste' classeurId=$ppo->classeurId dossierId=$ppo->dossierId triDossiers='taille' triFichiers='taille' triDirection=$triDirection}">{i18n key="classeur.message.size"}</a>
      </th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    {assign var=index value=1}
    {foreach from=$ppo->dossiers item=dossier}
    <tr class="folder {if $index%2 eq 0}odd{else}even{/if}">
      <td><input type="checkbox" class="check" name="dossiers[]" value="{$dossier->id}" /></td>
      <td><a href="{copixurl dest="classeur||voirContenu" vue='liste' classeurId=$ppo->classeurId dossierId=$dossier->id}" title="{i18n key="classeur.message.openFolder" nom=$dossier->nom}">{$dossier->nom|escape}</a></td>
      <td>---</td>
      <td>{$dossier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
      <td>
        {if $dossier->nb_dossiers neq 0}
          {$dossier->nb_dossiers} {if $dossier->nb_dossiers eq 1}dossier{else}dossiers{/if}
        {/if}
        {if $dossier->nb_fichiers neq 0}
          {$dossier->nb_fichiers} {if $dossier->nb_fichiers eq 1}fichier{else}fichiers{/if}
        {/if}
        {$dossier->taille|human_file_size}
      </td>
      <td>
        {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
        <a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId dossierId=$dossier->id}" title="{i18n key="classeur.message.modify"}">
          <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="classeur.message.modify"}" />
        </a>
        <a href="{copixurl dest="classeur||deplacerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId dossierId=$dossier->id}" title="{i18n key="classeur.message.move"}">
          Move
        </a>
        <a href="{copixurl dest="classeur||supprimerDossier" classeurId=$ppo->classeurId dossierId=$dossier->id}" onclick="return confirm('{i18n key="classeur.message.deleteFolderConfirm"}')" title="{i18n key="classeur.message.delete"}">
          <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
        </a>
        {/if}
      </td>
    </tr>
    {assign var=index value=$index+1}
    {/foreach}
    {foreach from=$ppo->fichiers item=fichier}
    {if $fichier->estUnFavori()}
      <tr class="{$fichier->type} {if $index%2 eq 0}odd{else}even{/if}">
        <td><input type="checkbox" class="check" name="fichiers[]" value="{$fichier->id}" /></td>
        <td><a href="{$fichier->getLienFavori()}" title="{i18n key="classeur.message.openFile" titre=$fichier->titre}">{$fichier->titre|escape}</a></td>
        <td>Favori</td>
        <td>{$fichier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
        <td>{$fichier->taille|human_file_size}</td>
        <td>
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
          <a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id favoriId=$fichier->id}" title="{i18n key="classeur.message.modify"}">
            <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="classeur.message.modify"}" />
          </a>
          <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" title="{i18n key="classeur.message.move"}">
            Move
          </a>
          <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
            <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
          </a>
          {/if}
        </td>
      </tr>
    {else}
      <tr class="{$fichier->type} {if $index%2 eq 0}odd{else}even{/if}">
        <td><input type="checkbox" class="check" name="fichiers[]" value="{$fichier->id}" /></td>
        <td><a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeurId fichierId=$fichier->id}" title="{i18n key="classeur.message.openFile" titre=$fichier->titre}">{$fichier->titre|escape}</a></td>
        <td>{$fichier->type_text}</td>
        <td>{$fichier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
        <td>{$fichier->taille|human_file_size}</td>
        <td>
          {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || ($dossier->user_id eq $ppo->idUtilisateur && $dossier->user_type eq $ppo->typeUtilisateur)}
          <a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" title="{i18n key="classeur.message.modify"}">
            <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="classeur.message.modify"}" />
          </a>
          <a href="{copixurl dest="classeur||deplacerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" title="{i18n key="classeur.message.move"}">
            Move
          </a>
          <a href="{copixurl dest="classeur||supprimerFichier" classeurId=$ppo->classeurId dossierId=$fichier->dossier_id fichierId=$fichier->id}" onclick="return confirm('{i18n key="classeur.message.deleteFileConfirm"}')" title="{i18n key="classeur.message.delete"}">
            <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="classeur.message.delete"}" />
          </a>
          {/if}
        </td>
      </tr>
    {/if}
    {assign var=index value=$index+1}
    {/foreach}
  </tbody>
</table>
{else}
  <p>{i18n key="classeur.message.noFiles"}</p>
{/if}