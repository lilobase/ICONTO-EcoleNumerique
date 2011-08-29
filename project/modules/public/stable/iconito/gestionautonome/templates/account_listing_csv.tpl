Nom;Prénom;Identifiant;Mot de passe;Type;Localisation

{if $sessionDatas neq null}
{foreach from=$sessionDatas item=sessionData}
{$sessionData.lastname};{$sessionData.firstname};{$sessionData.login};{$sessionData.password};{$sessionData.type_nom};{$sessionData.node_nom}
{foreach from=$sessionData.person item=person}
{$person.lastname};{$person.firstname};{$person.login};{$person.password};{$person.nom_pa};{$person.node_nom}
{/foreach}
{/foreach}
{/if}