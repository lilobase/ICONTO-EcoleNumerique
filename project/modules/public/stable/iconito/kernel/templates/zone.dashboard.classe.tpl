<ul class="dashboard-item">
    {foreach from=$eleves item=eleve}
        <li>
            {$eleve.nom} {$eleve.prenom}
        </li>
    {/foreach}
</ul>