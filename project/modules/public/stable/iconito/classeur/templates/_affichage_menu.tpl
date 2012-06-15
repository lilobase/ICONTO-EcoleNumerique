<div id="submenu">
  <div class="menuitems">
    <ul>
      {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER && !$ppo->dossier->casier}
        <li><a class="addfolder{if $ppo->current eq "editerDossier"} current{/if}" href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.newFolder"}</span></a></li>
        <li><a class="addfile{if $ppo->current eq "editerFavori"} current{/if}" href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFavorite"}</span></a></li>
      {/if}
      {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER || $ppo->dossier->casier}
        <li><a class="addfile{if $ppo->current eq "editerFichiers"} current{/if}" href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{i18n key="classeur.message.addFiles"}</span></a></li>
      {/if}
      <li class="newGroupItems"><a class="viewList{if $ppo->current eq "liste"} current{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId vue=liste}" title="{i18n key="classeur.message.listView"}"></a></li>
      <li><a class="viewThumbs{if $ppo->current eq "vignette"} current{/if}" href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId vue=vignette}" title="{i18n key="classeur.message.thumbnailView"}"></a></li>
      {if $ppo->niveauUtilisateur >= PROFILE_CCV_MODERATE && !$ppo->dossier->casier}
        <li class="newGroupItems"><a class="image{if $ppo->current eq "editerAlbumPublic"} current{/if}" href="{copixurl dest="classeur||editerAlbumPublic" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}"><span class="valign"></span><span>{if $ppo->estPublic eq true}{i18n key="classeur.message.updatePublicAlbum"}{else}{i18n key="classeur.message.createPublicAlbum"}{/if}</span></a></li>
         {if $ppo->conf_ModClasseur_options}
            <li><a class="options{if $ppo->current eq "options"} current{/if}" href="{copixurl dest="classeur|options|" classeurId=$ppo->classeurId}"><span class="valign"></span><span>{i18n key="classeur.message.options"}</span></a></li>
         {/if}
      {/if}
    </ul>
  </div>
</div>
