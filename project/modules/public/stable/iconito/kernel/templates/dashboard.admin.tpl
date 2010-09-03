<h3>
    {i18n key="kernel|dashboard.admin.title" noEscape="true"}
</h3>

<form action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <textarea id="content_txt" name="content_txt">{$ppo->content.content}</textarea>
    <input type="hidden" value="{$ppo->content.id_zone}" name="id_zone" />
    <input type="hidden" value="{$ppo->content.type_zone}" name="type_zone" />
    <input type="hidden" value="{$ppo->content.id}" name="id" />
    <input type="submit" />
</form>