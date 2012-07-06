{if ($ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || $ppo->classeurId eq $ppo->idClasseurPersonnel || !$ppo->dossier->casier)}
  <ul class="mass-actions">
    {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || $ppo->classeurId eq $ppo->idClasseurPersonnel}
      <li><a href="{copixurl dest="classeur||supprimerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-delete">{i18n key="classeur.message.delete"}</a></li>
      <li><a href="{copixurl dest="classeur||deplacerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-move">{i18n key="classeur.message.move"}</a></li>
    {/if}
    {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || !$ppo->dossier->casier}
      <li><a href="{copixurl dest="classeur||copierContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-copy">{i18n key="classeur.message.copy"}</a></li> 
      <li><a href="{copixurl dest="classeur||telechargerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-download">{i18n key="classeur.message.download"}</a></li>
    {/if}
  </ul>
{/if}