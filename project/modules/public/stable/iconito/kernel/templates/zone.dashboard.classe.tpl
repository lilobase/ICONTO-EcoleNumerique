{if $ceriseprim}
<div>
<a target="_blank" href="{$ceriseprim}"><img src="https://www.cerise-prim.fr/support/aproposde/docs/logo930x200_300dpi.png" width="200" alt="Cerise Prim"/></a>
</div><br class="clear" />
{/if}

<h4 class="dashboard-zone-title">{i18n key="kernel|dashboard.listeEleve" noEscape=true}</h4>

    <img title="Photo de la classe" alt="Photo de la classe" src="{copixresource path="img/fichesecoles/no_photo.gif"}" class="dashboard-zone-img"/>
<ul class="dashboard-item">
    {foreach from=$eleves item=eleve}
        <li>
            {$eleve.nom} {$eleve.prenom}
        </li>
    {/foreach}
</ul>