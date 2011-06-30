{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}

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
      <li><a href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.updateAlbum"}</a></li>
    </ul>
  {/if}
</div>

{if $ppo->album->public eq 0}
  <p>{i18n key="classeur.message.albumNotPublished"}</p>
  
  <ul class="actions">
    <li><a href="{copixurl dest="classeur||publierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.publishAnAlbum"}</a></li>
  </ul>
{else}
  <p>Votre album public a été publié le {$ppo->album->date_publication|datei18n:"date_short_time"|substr:0:10}</p>
  
  <ul class="actions">
    <li><a href="{$ppo->albumUrl}">{i18n key="classeur.message.viewAlbum"}</a></li>
    <li><a href="{copixurl dest="classeur||depublierAlbum" classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}">{i18n key="classeur.message.removeAnAlbum"}</a></li>
  </ul>
{/if}