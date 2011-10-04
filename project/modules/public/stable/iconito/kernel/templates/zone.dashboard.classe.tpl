{if $ceriseprim}
<div>
<a target="_blank" href="{$ceriseprim}"><img src="https://www.cerise-prim.fr/support/aproposde/docs/logo930x200_300dpi.png" width="200" alt="Cerise Prim"/></a>
</div><br clear="all" />
{/if}

<h4 class="dashboard-zone-title">{i18n key="kernel|dashboard.listeEleve" noEscape=true}</h4>
<ul class="dashboard-item">
    {foreach from=$eleves item=eleve}
        <li>
            {$eleve.nom} {$eleve.prenom}
        </li>
    {/foreach}
</ul>