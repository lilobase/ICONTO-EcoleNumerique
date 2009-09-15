<link rel="stylesheet" type="text/css" href="styles/module_blog_admin.css" />

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

{if $link==null}
	<h1>{i18n key="blog.get.create.link.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.link.title"}</h1>
{/if}
<form name="linkEdit" action="{copixurl dest="blog|admin|validLink" id_blog=$id_blog kind=$kind}" method="post" class="copixForm">


<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.bloglink.fields.name_blnk'}</th>
	  	<td CLASS="form_saisie"><input type="text" name="name_blnk" value="{$link->name_blnk|escape}" class="form" style="width:250px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.bloglink.fields.url_blnk'}</th>
	  	<td CLASS="form_saisie"><input type="text" name="url_blnk" value="{$link->url_blnk|escape}" class="form" style="width:250px;"></td>
   </tr>
	 <tr><td colspan="2" CLASS="form_submit">
	 <input type="hidden" name="id_blnk" value="{$link->id_blnk}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
<input type="button" class="form_button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
	</td></tr>
</table>

</form>