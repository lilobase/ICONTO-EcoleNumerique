{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId current="editerAlbumPublic"}

<h2>{i18n key="classeur.message.createPublicAlbum"}</h2>

{if $ppo->confirmMessage}
  <p class="mesgSuccess">{$ppo->confirmMessage}</p>
{/if}

{i18n key="classeur.message.descPublicAlbum"}

<div class="folder-infos">
  {i18n key="classeur.message.thisFolderContains"}:
  <ul>
    <li>{$ppo->images|@count} {i18n key="classeur.message.images"}</li>
    <li>{$ppo->documents|@count} {i18n key="classeur.message.filesThatWillBeIgnored"}</li>
  </ul>
  
  {if $ppo->album->public eq 1}
    <ul class="actions">
      <li><a class="button button-image" href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.updateAlbum"}</a></li>
    </ul>
  {/if}
</div>

{if $ppo->album->public eq 0}
  <p>{i18n key="classeur.message.albumNotPublished"}</p>
  
  {if $ppo->images|@count gt 0}
  <ul class="actions">
    <li><a class="button button-image" href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.publishAnAlbum"}</a></li>
  </ul>
  {/if}
{else}
  <p>{i18n key="classeur.message.albumPublishedOn"} {$ppo->album->date_publication|datei18n:"date_short_time"|substr:0:10}</p>
  
  <ul class="actions">
    <li><a class="button button-imagevalid" href="{$ppo->albumUrl}" target="_blank">{i18n key="classeur.message.viewAlbum"}</a></li>
    <li><a class="button button-imagedelete" href="{copixurl dest="classeur||depublierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.removeAnAlbum"}</a></li>
  </ul>
{/if}

<a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">
  <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
</a>