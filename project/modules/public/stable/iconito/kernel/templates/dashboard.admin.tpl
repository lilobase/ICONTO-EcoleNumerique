<h3>
    {i18n key="kernel|dashboard.admin.title" noEscape="true"}
</h3>
<h5>
    <a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}">Supprimer le contenu</a>
</h5>
<form action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <textarea id="content_txt" name="content_txt">{$ppo->content.content}</textarea>
    <input type="hidden" value="{$ppo->content.id_zone}" name="id_zone" />
    <input type="hidden" value="{$ppo->content.type_zone}" name="type_zone" />
    <input type="hidden" value="{$ppo->content.id}" name="id" />
    <input type="submit" />
</form><br />
<form action="{copixurl dest="kernel|dashboard|addPicture" id=$ppo->content.id}" enctype="multipart/form-data" method="post">

    {if !empty($ppo->content.picture)} <img src="{copixurl dest="kernel|dashboard|image" id=$ppo->content.id}" /> {/if}
    <label for="image">Ajouter une image : </label>
    <input type="file" name="image" accept="image/*" />
    <input type="submit" />
</form>
{if !empty($ppo->content.picture)}
<h5>
    <a href="{copixurl dest="kernel|dashboard|deletePic" id=$ppo->content.id}">Supprimer l'image</a>
</h5>
{/if}