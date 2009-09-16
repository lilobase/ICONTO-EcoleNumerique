<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />

<h1>{i18n key="blog.get.edit.comment.title"}</h1>

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

<form name="commentEdit" action="{copixurl dest="blog|admin|validModifyComment" id_blog=$id_blog id_bact=$id_bact}" method="post" class="copixForm">
<input type="hidden" name="id_bacc" value="{$id_bacc}">
<input type="hidden" name="authorid_bacc" value="{$comment->authorid_bacc}">

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle">{i18n key='blog.messages.article.name'}</td>
	  	<td CLASS="form_saisie">{$comment->name_bact|escape}</td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.authorname_bacc'}</td>
	  	<td CLASS="form_saisie"><input type="text" class="form" name="authorname_bacc" value="{$comment->authorname_bacc|escape}" style="width:300px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.authoremail_bacc'}</td>
	  	<td CLASS="form_saisie"><input type="text" class="form" name="authoremail_bacc" value="{$comment->authoremail_bacc|escape}" style="width:300px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.authorweb_bacc'}</td>
	  	<td CLASS="form_saisie"><input type="text" class="form" name="authorweb_bacc" value="{$comment->authorweb_bacc|escape}" style="width:300px;"></td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.authorip_bacc'}</td>
	  	<td CLASS="form_saisie">{$comment->authorip_bacc|escape}</td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.date_bacc'}</td>
	  	<td CLASS="form_saisie">{i18n key='blog.messages.le_a' 1=$comment->date_bacc|datei18n 2=$comment->time_bacc}</td>
   </tr>
   <tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticlecomment.fields.content_bacc'}</td>
	  	<td CLASS="form_saisie"><textarea class="form" style="width:400px; height: 200px;" name="content_bacc">{$comment->content_bacc|escape}</textarea></td>
   </tr>
	 <tr><td colspan="2" CLASS="form_submit">
	 <input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
<input type="button" class="form_button" value="{i18n key="copix:common.buttons.delete"}" onclick="javascript:window.location='{copixurl dest="blog|admin|deleteComment" id_blog=$id_blog id_bact=$id_bact id_bacc=$id_bacc}'" />
<input type="button" class="form_button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|listComment" id_blog=$id_blog id_bact=$id_bact}'" />

	 </td>
	 </tr>
</table>
</form>