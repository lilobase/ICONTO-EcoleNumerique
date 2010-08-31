{literal}
<script type="text/javascript">
//<![CDATA[
function doUrl (pUrl) {
   var myForm = document.articleEdit;
   myForm.action = pUrl;
   if (typeof myForm.onsubmit == "function")// Form is submited only if a submit event handler is set.
      myForm.onsubmit();
   myForm.submit ();
}
//]]>
</script>
{/literal}

{if $kind eq "0"}

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
<h4>{$article->name_bact}</h4>
<div>{$article->sumary_bact|blog_format_article:$article->format_bact}</div>
<div>{$article->content_bact|blog_format_article:$article->format_bact}</div>
</div>
</div>
{/if}

	{if $id_bact==null}
		<h1>{i18n key="blog.get.create.article.title"}</h1>
	{else}
		<h1>{i18n key="blog.get.edit.article.title"}</h1>
	{/if}
	<form name="articleEdit" action="{copixurl dest="blog|admin|validArticle" kind=$kind}" method="post" class="copixForm">
<input type="hidden" name="go" value="preview" />
	
	<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	
	   <tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogarticle.fields.name_bact'}</td>
		  	<td CLASS="form_saisie"><input type="text" name="name_bact" value="{$article->name_bact|escape}" class="form" style="width:300px;"></td>
	   </tr>
	   <tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='blog.nav.categories'}</td>
		  	<td CLASS="form_saisie">
		  		{foreach from=$tabArticleCategory item=cat}
		  			 <input type="checkbox" id="tabSelectCat_{$cat->id_bacg}" name="tabSelectCat[]" value="{$cat->id_bacg}" {if $cat->selected}checked{/if}><label for="tabSelectCat_{$cat->id_bacg}"> {$cat->name_bacg}</label><br />
		  		{/foreach}
		  	</td>
	   </tr>
		 
		 <tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogarticle.fields.sumary_bact'}<br/><span class=helptext>{i18n key='blog.get.edit.article.chapo_help'}</span></td>
		  	<td CLASS="form_saisie">{$edition_sumary}</td>
	   </tr>
		 <tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key='dao.blogarticle.fields.content_bact'}</td>
		  	<td CLASS="form_saisie">{$edition_content}</td>
	   </tr>
		 
	   <tr>
	      <td CLASS="form_libelle" VALIGN="TOP">{i18n key="dao.blogarticle.fields.date_bact"}/{i18n key="dao.blogarticle.fields.time_bact"}</td>
	      <td CLASS="form_saisie">
        
        {inputtext class="datepicker" name="date_bact" value=$article->date_bact|datei18n}
        
        &nbsp;&nbsp;<input type="text" size=5 name="time_bact" value="{$article->time_bact|escape}" class="form"> {i18n key="blog.get.edit.article.heure_help"}
<br/>
{i18n key="blog.get.edit.article.date_help"}

</td>
	   </tr>
		 <tr>
	      <td CLASS="form_libelle" VALIGN="TOP"><label for="is_online">{i18n key="dao.blogarticle.fields.is_online"}</td>
	      <td CLASS="form_saisie">{if $canWriteOnline}<input type="checkbox" id="is_online" name="is_online" value="1" {if $article->is_online}checked{/if} />{else}{i18n key="blog.article.offline.info"}<input type="hidden" name="is_online" value="0">{/if}</td>
	   </tr>
		<tr>
      <td CLASS="form_libelle">{i18n key='dao.blogarticle.fields.format_bact'}</td>
	  	<td CLASS="form_saisie">{html_radios name="format_bact" values=$format_bact.values output=$format_bact.output checked=$article->format_bact onClick="return change_format(this);"}</td>
   	</tr>		 
		 
	
	
		 <tr><td colspan="2" CLASS="form_submit">
	<input type="hidden" name="id_bact" value="{$article->id_bact}">
	<input type="hidden" name="id_blog" value="{$id_blog}">
	<input type="submit" class="button button-view" value="{i18n key='blog.button.previsu'}" onClick="goBlog(this.form, 'preview');" />
	<input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}"  onClick="goBlog(this.form, 'save');" />
	<input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
		</td></tr>
	</table>
	</form>
{/if}

