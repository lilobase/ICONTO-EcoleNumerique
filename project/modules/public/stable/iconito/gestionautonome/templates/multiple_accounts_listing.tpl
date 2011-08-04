<h2>Importer des élèves</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|MultipleAccountsInfo}
</div>

<p class="mesgSuccess">Elèves ajoutés !</p>

<h3>Liste des élèves ajoutés</h3>
<ul>
{foreach from=$ppo->students key=k item=student}
    <li>{if $student.gender eq 1}
            <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
          {else}                                                                 
            <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
          {/if} {$student.firstname} {$student.lastname}
    {foreach from=$student.person key=j item=person}
        <br />({$person.firstname} {$person.lastname})
    {/foreach}
    </li>
{/foreach}
</ul>

<div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree}" class="button button-back">Retour</a>
</div>