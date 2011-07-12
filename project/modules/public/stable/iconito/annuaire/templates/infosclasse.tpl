
<h2>{$classe.nom}</h2>

{if $classe.enseignants != 'NONE'}
<h3>{i18n key="annuaire.enseignant"}</h3>
<div id="eleves">
{foreach from=$classe.enseignants item=enseignant}
<div>{if $enseignant.sexe == 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" />{elseif $enseignant.sexe == 2}<img src="{copixurl}themes/default/images/icon-16/user-female.png" />{/if}
{user label=$enseignant.prenom|cat:" "|cat:$enseignant.nom userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=$canWriteUSER_ENS}

</div>
{/foreach}
{if !$classe.enseignants}{i18n key="annuaire.noEnseignants"}{/if}
</div>
{/if}

{if $classe.eleves != 'NONE'}
<h3>{i18n key="annuaire.eleves"}</h3>
<div id="eleves">
{foreach from=$classe.eleves item=eleve}
<div>{if $eleve.sexe == 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" />{elseif $eleve.sexe == 2}<img src="{copixurl}themes/default/images/icon-16/user-female.png" />{/if}

{user label=$eleve.prenom|cat:" "|cat:$eleve.nom userType=$eleve.type userId=$eleve.id login=$eleve.login dispMail=$canWriteUSER_ELE}

</div>
{/foreach}
{if !$classe.eleves}{i18n key="annuaire.noEleves"}{/if}
</div>
{/if}

