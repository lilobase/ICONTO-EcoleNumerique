Nom;Prénom;Identifiant;Mot de passe;Type;Localisation

{if $accounts neq null}
{foreach from=$accounts item=account}
{$account.lastname};{$account.firstname};{$account.login};{$account.password};{$account.type_nom};{$account.node_nom}
{foreach from=$account.person item=person}
{$person.lastname};{$person.firstname};{$person.login};{$person.password};{$person.nom_pa};{$person.node_nom}
{/foreach}
{/foreach}
{/if}