
<div class="admindash">
    {if !empty($picture)}
    <img src="{$picture}" />
    {/if}
{$content}
</div>
{if $is_admin}
    <a href="{copixurl dest="kernel|dashboard|modif" node_id=$id node_type=$type}" class="modif_dash button button-update">{i18n key="kernel|dashboard.admin.link"}</a>
{/if}