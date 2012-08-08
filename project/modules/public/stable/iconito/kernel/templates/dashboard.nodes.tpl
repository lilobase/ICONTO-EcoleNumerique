{if $is_admin && $type == 'BU_ECOLE'}
    <a class="dashboard-list" title="{i18n key="kernel|dashboard.ficheEcole" noEscape="true"}" href="{copixurl dest="fichesecoles||fiche" id=$idZone}">{customi18n key="kernel|dashboard.fiche%%definite__structure%%" catalog=$catalog noEscape="true"}</a>
{/if}

<div class="admindash">
    {if !empty($picture)}
        <img  src="{copixurl dest="fichesecoles||photo" photo=$pic|urlencode}" alt="{i18n key="kernel|dashboard.imgAlt" noEscape="true"}" title="{i18n key="kernel|dashboard.imgAlt" noEscape="true"}" />

    {/if}
{$content}
</div>
{if $is_admin && $type != 'ROOT'}

{if $is_admin && $type == 'BU_CLASSE'}
<div id="admindash_lower">
{i18n key="kernel|dashboard.admin.classe.alert"}
</div>
{/if}

    <a href="{copixurl dest="kernel|dashboard|modif" node_id=$id node_type=$type}"
    class="modif_dash button button-update">{i18n key="kernel|dashboard.admin.link"}</a>
    

{/if}