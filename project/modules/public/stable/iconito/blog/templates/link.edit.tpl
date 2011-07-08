<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->

{if $link==null}
	<h1>{i18n key="blog.get.create.link.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.link.title"}</h1>
{/if}

{if $showErrors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<form name="linkEdit" action="{copixurl dest="blog|admin|validLink" id_blog=$id_blog kind=$kind}" method="post" class="copixForm">

<table class="editItems">

   <tr>
      <th>{i18n key='dao.bloglink.fields.name_blnk'}</th>
	  <td><input type="text" name="name_blnk" value="{$link->name_blnk|escape}" /></td>
   </tr>
   <tr>
      <th>{i18n key='dao.bloglink.fields.url_blnk'}</th>
	  <td><input type="text" name="url_blnk" value="{$link->url_blnk|escape}" /></td>
   </tr>
	<tr>
      <td></td>
      <td><input type="hidden" name="id_blnk" value="{$link->id_blnk}" />
		<input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
        <input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" />
		</td>
    </tr>
</table>

</form>