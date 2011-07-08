<div id="submenu">
  <div class="menuitems">
    <ul>
      {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER}
      <li><a class="addfolder" href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.newFolder"}</span></a></li>
      <li><a class="addfile" href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFavorite"}</span></a></li>
      <li><a class="addfile" href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFiles"}</span></a></li>
      {/if}
      <li><a class="viewList" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId vue=liste}"><span class="valign"></span><span>{i18n key="classeur.message.listView"}</span></a></li>
      <li><a class="viewVign" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId vue=vignette}"><span class="valign"></span><span>{i18n key="classeur.message.thumbnailView"}</span></a></li>
      {if $ppo->niveauUtilisateur >= PROFILE_CCV_MODERATE}
        <li><a class="image" href="{copixurl dest="classeur||editerAlbumPublic" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.createPublicAlbum"}</span></a></li>
      {/if}
    </ul>
  </div>
</div>