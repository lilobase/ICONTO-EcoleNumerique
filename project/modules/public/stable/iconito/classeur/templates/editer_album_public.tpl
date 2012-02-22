{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId current="editerAlbumPublic"}

<h2>
  {if $ppo->album->public eq 0}
    {i18n key="classeur.message.createPublicAlbum"}
  {else}
    {i18n key="classeur.message.updatePublicAlbum"}
  {/if}
</h2>

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
</div>

{if $ppo->album->public eq 0}
  <p>
      <strong>{i18n key="classeur.message.albumNotPublished"}</strong> 
      {if $ppo->images|@count gt 0}
      <a class="button button-image" href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.publishAnAlbum"}</a>
      {/if}
  </p>
{else}
  <p><strong>{i18n key="classeur.message.albumPublishedOn"} {$ppo->album->date_publication|datei18n:"date_short_time"|substr:0:10}</strong> <a class="button button-imagevalid" href="{$ppo->albumUrl}" target="_blank">{i18n key="classeur.message.viewAlbum"}</a></p>
  
  <ul class="actions">
    <li><a class="button button-imagedelete" href="{copixurl dest="classeur||depublierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.removeAnAlbum"}</a></li>
    <li><a class="button button-image" href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.updateAlbum"}</a></li>
  </ul>
{/if}

<div class="center clear">
<a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">
  <span class="button button-cancel cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
</a>
</div>