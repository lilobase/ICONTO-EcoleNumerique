{if $is_admin}
    <a href="{copixurl dest="kernel|dashboard|modif" node_id=$id node_type=$type}">{i18n key="kernel|dashboard.admin.link"}</a>
{/if}
<div id="">
    {if !empty($picture)}
    <img src="{$picture}" />
    {/if}
{$content}
</div>