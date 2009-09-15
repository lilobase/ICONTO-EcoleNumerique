
<H2>{$classe.nom}</H2>

{if $classe.enseignants != 'NONE'}
<H3>{i18n key="annuaire.enseignant"}</H3>
<DIV ID="eleves">
{foreach from=$classe.enseignants item=enseignant}
<DIV><IMG SRC="img/annuaire/sexe{$enseignant.sexe}b.png" width="15" height="17" /> 

{user label=$enseignant.prenom|cat:" "|cat:$enseignant.nom userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=1}

</DIV>
{/foreach}
{if !$classe.enseignants}{i18n key="annuaire.noEnseignants"}{/if}
</DIV>
{/if}


{if $classe.eleves != 'NONE'}
<H3>{i18n key="annuaire.eleves"}</H3>
<DIV ID="eleves">
{foreach from=$classe.eleves item=eleve}
<DIV><IMG SRC="img/annuaire/sexe{$eleve.sexe}b.png" width="15" height="17" /> 

{user label=$eleve.prenom|cat:" "|cat:$eleve.nom userType=$eleve.type userId=$eleve.id login=$eleve.login dispMail=1}

</DIV>
{/foreach}
{if !$classe.eleves}{i18n key="annuaire.noEleves"}{/if}
</DIV>
{/if}

