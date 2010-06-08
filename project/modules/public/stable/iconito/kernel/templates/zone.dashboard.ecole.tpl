{if empty($pic)}
    <img src="{copixresource path="img/fichesecoles/no_photo.gif"}" alt="{i18n key="kernel|dashboard.imgAlt"}" title="{i18n key="kernel|dashboard.imgAlt"}" />
{else}
    <img src="{copixurl dest="fichesecoles||photo" photo=$pic|urlencode}" alt="{i18n key="kernel|dashboard.imgAlt"}" title="{i18n key="kernel|dashboard.imgAlt"}" />
{/if}

<ul class="dashboard-item">
    {foreach from=$ens item=en}
        <li>{$en.nom} {$en.prenom}</li>
    {/foreach}
</ul>