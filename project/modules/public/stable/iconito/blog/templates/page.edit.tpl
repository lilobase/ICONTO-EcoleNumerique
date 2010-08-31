{literal}
<script type="text/javascript">
//<![CDATA[
function doUrl (pUrl) {
   var myForm = document.pageEdit;
   myForm.action = pUrl;
   if (typeof myForm.onsubmit == "function")// Form is submited only if a submit event handler is set.
      myForm.onsubmit();
   myForm.submit ();
}
//]]>
</script>
{/literal}

{if $kind eq "5"}

{if $showErrors}
<div class="errorMessage">
<h1>{i18n key=copix:common.messages.error}</h1>
 {ulli values=$errors}
</div>
{/if}

{if $preview}
<div class="forum_message_preview">
<h1>{i18n key="blog.button.previsu"}</h1>
<div class="postContent">
<h4>{$page->name_bpge}</h4>
<div>{$page->content_bpge|blog_format_article:$page->format_bpge}</div>
</div>
</div>
{/if}


{if $id_bpge==null}
	<h1>{i18n key="blog.get.create.page.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.page.title"}</h1>
{/if}


<form name="pageEdit" action="{copixurl dest="blog|admin|validPage" kind=$kind}" method="post" class="copixForm">
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="kind" value="{$kind}" />

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
   <tr>
		<td CLASS="form_libelle">{i18n key='dao.blogpage.fields.name_bpge'}</td>
		<td CLASS="form_saisie"><input type="text" style="width:250px;" name="name_bpge" value="{$page->name_bpge|escape}" class="form"></td>
   </tr>
	 {*
   <tr>
		<td CLASS="form_libelle">{i18n key='dao.blogpage.fields.content_bpge'}</td>
		<td CLASS="form_saisie"><textarea style="width:500px; height: 150px;" name="content_bpge" id="content_bpge" class="form">{$page->content_bpge|escape}</textarea>{$wikibuttons}</td>
   </tr>
		*}
   <tr>
		<td CLASS="form_libelle">{i18n key='dao.blogpage.fields.content_bpge'}</td>
		<td CLASS="form_saisie">{$edition_content}</td>
   </tr>
		
	<tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key="dao.blogpage.fields.is_online"}</td>
	      <td CLASS="form_saisie">{if $canWriteOnline}<input type="checkbox" name="is_online" value="1" {if $page->is_online}checked{/if} />{else}{i18n key="blog.page.offline.info"}<input type="hidden" name="is_online" value="0">{/if}</td>
	   </tr>

		<tr>
      <td CLASS="form_libelle">{i18n key='dao.blogpage.fields.format_bpge'}</td>
	  	<td CLASS="form_saisie">{html_radios name="format_bpge" values=$format_bpge.values output=$format_bpge.output checked=$page->format_bpge onClick="return change_format(this);"}</td>
   	</tr>		
		
	 <tr><td colspan="2" CLASS="form_submit">

<input type="hidden" name="id_bpge" value="{$page->id_bpge}">
<input type="hidden" name="id_blog" value="{$id_blog}">
<input type="submit" class="button button-view" value="{i18n key='blog.button.previsu'}" onClick="goBlog(this.form, 'preview');" />
<input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" onClick="goBlog(this.form, 'save');" />
<input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />

</td></tr>
</table>

</form>
{/if}

