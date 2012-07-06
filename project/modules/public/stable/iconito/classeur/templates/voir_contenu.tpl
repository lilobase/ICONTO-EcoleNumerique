{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId current=$ppo->vue}

{if $ppo->confirmMessage}
  <p class="mesgSuccess">{$ppo->confirmMessage}</p>
{/if}
{if $ppo->errorMessage}
  <p class="mesgError">{$ppo->errorMessage}</p>
{/if}

<div id="sidebar">
  {copixzone process=classeur|arborescenceClasseurs classeurId=$ppo->classeurId dossierCourant=$ppo->dossierId withPersonal=true moduleType=$ppo->moduleType moduleId=$ppo->moduleId withMainLocker=true withSubLockers=$ppo->withSubLockers}
</div>

<div class="content-view">
  {if $ppo->vue eq 'liste'}
    {copixzone process=classeur|vueListe classeurId=$ppo->classeurId dossierId=$ppo->dossierId}
  {else}
    {copixzone process=classeur|vueVignette classeurId=$ppo->classeurId dossierId=$ppo->dossierId}
  {/if}
</div>

<script type="text/javascript">
  var classeurId = {$ppo->classeurId};
  var dossierId  = {$ppo->dossierId};
</script>