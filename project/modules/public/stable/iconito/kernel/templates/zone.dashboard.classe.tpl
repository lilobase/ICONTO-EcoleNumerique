<h4 class="dashboard-zone-title">{i18n key="kernel|dashboard.listeEleve" noEscape=true}</h4>
<ul class="dashboard-item">
    {foreach from=$eleves item=eleve}
        <li>
            {$eleve.nom} {$eleve.prenom}
        </li>
    {/foreach}
</ul>