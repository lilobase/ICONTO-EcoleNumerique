nom;prenom;login;password;type_nom;node_nom

{if $sessionDatas neq null}
  {foreach from=$sessionDatas item=sessionData}
    {$sessionData.nom};{$sessionData.prenom};{$sessionData.login};{$sessionData.password};{$sessionData.type_nom};{$sessionData.node_nom}
  {/foreach}
{/if}