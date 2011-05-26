{if $ppo->fichiers neq null}
  {foreach from=$ppo->fichiers item=fichier}
    <a href="{$fichier->getDownloadUrl()}">{$fichier->nom}</a>
  {/foreach}
{/if}