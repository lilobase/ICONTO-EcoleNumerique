<div id="submenu">
  {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH}
    <a href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId}">{i18n key="classeur.message.newFolder"}</a> - 
    <a href="{copixurl dest="classeur||editerFavori" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">{i18n key="classeur.message.addFavorite"}</a> - 
    <a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">{i18n key="classeur.message.addFiles"}</a>
  {/if}
</div>