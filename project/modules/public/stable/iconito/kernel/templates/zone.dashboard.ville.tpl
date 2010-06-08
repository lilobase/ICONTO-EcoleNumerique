<ul class="dashboard-item">
{foreach from=$ecoles item=ecole}
    <li>
        <a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">
            {$ecole.nom}
        </a>
    </li>
{/foreach}
</ul>