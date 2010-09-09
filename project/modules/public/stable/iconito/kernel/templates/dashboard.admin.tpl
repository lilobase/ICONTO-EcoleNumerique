<h3>
    {i18n key="kernel|dashboard.admin.title" noEscape="true"}
</h3>
    <hr />    
<fieldset id="dash-ct">
<legend>{i18n key="kernel|dashboard.admin.content" noEscape="true"}</legend>
<form action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <textarea id="content_txt" name="content_txt">{$ppo->content.content}</textarea>
    <input type="hidden" value="{$ppo->content.id_zone}" name="id_zone" />
    <input type="hidden" value="{$ppo->content.type_zone}" name="type_zone" />
    <input type="hidden" value="{$ppo->content.id}" name="id" />
    <input type="submit" class="button button-save" />
</form>
<a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}" class="button button-delete" >Supprimer le contenu</a>
</fieldset>
<br />
<fieldset id="dash-pic">
    <legend>{i18n key="kernel|dashboard.admin.picture" noEscape="true"}</legend>
    <form action="{copixurl dest="kernel|dashboard|addPicture" id=$ppo->content.id}" enctype="multipart/form-data" method="post">
        {if !empty($ppo->content.picture)} <img src="{copixurl dest="kernel|dashboard|image" id=$ppo->content.id pic=$ppo->content.picture}" /> {/if}
        <label for="image">Ajouter une image : </label><br />
        <input type="file" name="image" accept="image/*" /><br />
        <input type="submit" class="button button-save" />
    </form>
    {if !empty($ppo->content.picture)}
        <a href="{copixurl dest="kernel|dashboard|deletePic" id=$ppo->content.id}" class="button button-delete">Supprimer l'image</a>
    {/if}
 </fieldset>