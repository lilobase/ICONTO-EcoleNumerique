<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->

{if $id_bacg==null}
	<h1>{i18n key="blog.get.create.category.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.category.title"}</h1>
{/if}

{if $showErrors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<form name="categoryEdit" action="{copixurl dest="blog|admin|validCategory" kind=$kind}" method="post" class="copixForm">
<input type="hidden" name="url_bacg" value="{$category->url_bacg|escape}">

<table class="editItems">

   <tr>
      <th>{i18n key='dao.blogarticlecategory.fields.name_bacg'}</th>
	  <td><input type="text" name="name_bacg" value="{$category->name_bacg|escape}" /></td>
   </tr>
	 <tr>
     	<td></td>
        <td>
<input type="hidden" name="id_bacg" value="{$category->id_bacg}" />
<input type="hidden" name="id_blog" value="{$id_blog}" />
<input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
<input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" />
	</td></tr>
</table>

</form>