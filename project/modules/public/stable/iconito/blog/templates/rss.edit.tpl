<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

{if $rss==null}
	<h1>{i18n key="blog.get.create.rss.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.rss.title"}</h1>
{/if}
<form name="rssEdit" action="{copixurl dest="blog|admin|validRss" id_blog=$id_blog kind=$kind}" method="post" class="copixForm">


<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogrss.fields.name_bfrs'}</th>
	  	<td CLASS="form_saisie"><input type="text" name="name_bfrs" value="{$rss->name_bfrs|escape}" class="form" style="width:250px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogrss.fields.url_bfrs'}</th>
	  	<td CLASS="form_saisie"><input type="text" name="url_bfrs" value="{$rss->url_bfrs|escape}" class="form" style="width:250px;"></td>
   </tr>
	 <tr><td colspan="2" CLASS="form_submit">
	 <input type="hidden" name="id_bfrs" value="{$rss->id_bfrs}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
<input type="button" class="form_button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
	</td></tr>
</table>

</form>