<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />



{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

{if $id_blog==null}
	<h1>{i18n key="blog.get.create.blog.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.blog.title"}</h1>
{/if}
<form name="blogEdit" action="{copixurl dest="blog|admin|validBlog" kind=$kind}" method="post" enctype="multipart/form-data" class="copixForm">

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.name_blog'}</td>
	  	<td CLASS="form_saisie"><input type="text" name="name_blog" value="{$blog->name_blog|escape}" class="form" style="width:250px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.logo_blog'}</td>
	  	<td CLASS="form_saisie">
 						{if $blog->logo_blog!=''}<img src="{copixurl dest="blog|admin|logo" id_blog=$blog->id_blog}" border="0" /><br />{/if}
            <input size="35" type="file" name="logoFile" class="form" style="width:250px;"><br/>{i18n key='blog.logo.messages.instructions' nb=$logo_max_width}
			</td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.is_public'}</td>
	  	<td CLASS="form_saisie">{html_radios name="is_public" values=$is_public.values output=$is_public.output checked=$blog->is_public}</td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.has_comments_activated'}</td>
	  	<td CLASS="form_saisie">{html_radios name="has_comments_activated" values=$has_comments_activated.values output=$has_comments_activated.output checked=$blog->has_comments_activated}</td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.type_moderation_comments'}</td>
	  	<td CLASS="form_saisie">{html_radios name="type_moderation_comments" values=$type_moderation_comments.values output=$type_moderation_comments.output checked=$blog->type_moderation_comments}</td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blog.fields.default_format_articles'}</td>
	  	<td CLASS="form_saisie">{html_radios name="default_format_articles" values=$default_format_articles.values output=$default_format_articles.output checked=$blog->default_format_articles}</td>
   </tr>

	 <tr><td colspan="2" CLASS="form_submit">
<input type="hidden" name="id_blog" value="{$blog->id_blog}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
{if ($kind==null) or ($id_blog==null)}
	<input class="form_button" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|listBlog"}'" />
{else}
	<input class="form_button" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
{/if}
	 
	</td>	 
	 </tr>

	 
	 </table>
</form>