{if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH}
<div id="submenu">
  <div class="menuitems">
    <ul>
      <li><a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.newFolder"}</span></a></li>
      <li><a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFavorite"}</span></a></li>
      <li><a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFiles"}</span></a></li>
      {if $ppo->typeUtilisateur == 'USER_ENS'}
        <li><a href="{copixurl dest="classeur||editerAlbumPublic" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.createPublicAlbum"}</span></a></li>
      {/if}
    </ul>
  </div>
</div>
{/if}