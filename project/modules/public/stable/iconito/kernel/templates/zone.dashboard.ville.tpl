<h4 class="dashboard-zone-title">{i18n key="kernel|dashboard.listeEcoles" noEscape="true"}</h4>

<ul class="dashboard-big-item">
{foreach from=$ecoles item=ecole}
    <li>
        <a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}" class="dashboard-list">
            {$ecole.nom}
        </a>
    </li>
{/foreach}
</ul>