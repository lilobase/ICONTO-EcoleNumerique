<h3>
    {i18n key="kernel|dashboard.admin.title" noEscape="true"}
</h3>
    <hr />    
<div id="dash-ct" class="content-panel">
<form id="dash-form-ct" action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <textarea id="content_txt" name="content_txt">{$ppo->content.content}</textarea>
    <input type="hidden" value="{$ppo->content.id_zone}" name="id_zone" />
    <input type="hidden" value="{$ppo->content.type_zone}" name="type_zone" />
    <input type="hidden" value="{$ppo->content.id}" name="id" />
</form>
</div>
<br />
<div id="dash-pic" class="content-panel">
{if !empty($ppo->content.picture)} <img src="{copixurl dest="kernel|dashboard|image" id=$ppo->content.id pic=$ppo->content.picture}" /> {/if}
      {if empty($ppo->content.picture)}
    <form action="{copixurl dest="kernel|dashboard|addPicture" id=$ppo->content.id}" enctype="multipart/form-data" method="post">
        
        <label for="image">Ajouter une image : </label><br />
        <input type="file" name="image" accept="image/*" />
        <input type="submit" class="button button-add" value=" {i18n key="kernel|dashboard.admin.add" noEscape="true"}"/>
    </form>
    {/if}
    {if !empty($ppo->content.picture)}
        <br /><a href="{copixurl dest="kernel|dashboard|deletePic" id=$ppo->content.id}" class="button button-delete">Supprimer l'image</a>
    {/if}
        <div style="clear:both"></div>
 </div>
<br />
<div id="dash-ereg" class="content-panel">
    <a id="dash-submit" href="#" class="button button-save" >Enregistrer</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}" class="button button-delete" >Supprimer le contenu</a>
    <div style="clear:both"></div>
</div>

{literal}
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#dash-submit").click(function(){
            $("#dash-form-ct").submit();
        });
    });
</script>
{/literal}
