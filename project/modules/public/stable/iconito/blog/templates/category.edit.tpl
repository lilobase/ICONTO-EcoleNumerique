<link rel="stylesheet" type="text/css" href="styles/module_blog_admin.css" />

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

{if $id_bacg==null}
	<h1>{i18n key="blog.get.create.category.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.category.title"}</h1>
{/if}
<form name="categoryEdit" action="{copixurl dest="blog|admin|validCategory" kind=$kind}" method="post" class="copixForm">
<input type="hidden" name="url_bacg" value="{$category->url_bacg|escape}">

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogarticlecategory.fields.name_bacg'}</td>
	  	<td CLASS="form_saisie"><input type="text" style="width:250px;" name="name_bacg" value="{$category->name_bacg|escape}" class="form"></td>
   </tr>
	 <tr><td colspan="2" CLASS="form_submit">

	 
<input type="hidden" name="id_bacg" value="{$category->id_bacg}">
<input type="hidden" name="id_blog" value="{$id_blog}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
<input type="button" class="form_button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
	</td></tr>
</table>

</form>