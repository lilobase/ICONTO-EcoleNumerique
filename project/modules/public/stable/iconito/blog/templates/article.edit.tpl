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

	
	
  
{if $preview}
<div class="forum_message_preview">
<h2>{i18n key="blog.button.previsu"}</h2>
<div class="postContent">
<h4>{$article->name_bact}</h4>
<div>{$article->sumary_bact|blog_format_article:$article->format_bact}</div>
<div>{$article->content_bact|blog_format_article:$article->format_bact}</div>
</div>
</div>
{/if}

	{if $id_bact==null}
		<h2>{i18n key="blog.get.create.article.title"}</h2>
	{else}
		<h2>{i18n key="blog.get.edit.article.title"}</h2>
	{/if}
    
    {if $showErrors}
	<div class="mesgErrors">
	{ulli values=$errors}
	</div>
	{/if}
	<form name="articleEdit" action="{copixurl dest="blog|admin|validArticle" kind=$kind}" method="post" class="copixForm">
<input type="hidden" name="go" value="preview" />
	<p class="center">{i18n key='kernel|kernel.fields.oblig' noEscape=1}</p>
	<table class="editItems">
	
	   <tr>
	      <td>{i18n key='dao.blogarticle.fields.name_bact'} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></td>
		  	<td><input type="text" name="name_bact" value="{$article->name_bact|escape}" class="form" style="width:300px;" required /></td>
	   </tr>
	   <tr>
	      <td>{i18n key='blog.nav.categories'} <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></td>
		  	<td>
		  		{foreach from=$tabArticleCategory item=cat}
		  			 <input type="checkbox" id="tabSelectCat_{$cat->id_bacg}" name="tabSelectCat[]" value="{$cat->id_bacg}" {if $cat->selected}checked{/if} /><label for="tabSelectCat_{$cat->id_bacg}"> {$cat->name_bacg}</label><br />
		  		{/foreach}
		  	</td>
	   </tr>
		 
		 <tr>
	      <td>{i18n key='dao.blogarticle.fields.sumary_bact'}<br/><span class=helptext>{i18n key='blog.get.edit.article.chapo_help'}</span></td>
		  	<td>{$edition_sumary}</td>
	   </tr>
		 <tr>
	      <td>{i18n key='dao.blogarticle.fields.content_bact'}</td>
		  	<td>{$edition_content}</td>
	   </tr>
		 
	   <tr>
	      <td>{i18n key="dao.blogarticle.fields.date_bact"}/{i18n key="dao.blogarticle.fields.time_bact"}</td>
	      <td>
        
        {inputtext class="datepicker" name="date_bact" value=$article->date_bact|datei18n}
        
        &nbsp;&nbsp;<input type="text" size="5" id="time_bact" name="time_bact" value="{$article->time_bact|escape}" /> {i18n key="blog.get.edit.article.heure_help"}
<br/>
{i18n key="blog.get.edit.article.date_help"}

</td>
	   </tr>
		 <tr>
	      <td><label for="is_online">{i18n key="dao.blogarticle.fields.is_online"}</label></td>
	      <td>{if $canWriteOnline}<input type="checkbox" id="is_online" name="is_online" value="1" {if $article->is_online}checked{/if} />{else}{i18n key="blog.article.offline.info"}<input type="hidden" name="is_online" value="0" />{/if}</td>
	   </tr>
    {if $can_format_articles}
        <tr>
            <td>{i18n key='dao.blogarticle.fields.format_bact'}</td>
            <td>{html_radios name="format_bact" values=$format_bact.values output=$format_bact.output checked=$article->format_bact onClick="return change_format(this);"}</td>
        </tr>
    {else}
        <input type="hidden" name="format_bact" value="{$default_format_articles}" />
    {/if}
		 
	
	
		 <tr>
         	<td></td>
            <td>
	<input type="hidden" name="id_bact" value="{$article->id_bact}" />
	<input type="hidden" name="id_blog" value="{$id_blog}" />
	<input type="submit" class="button button-view" value="{i18n key='blog.button.previsu'}" onClick="goBlog(this.form, 'preview');" />
	<input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
	<input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}"  onClick="goBlog(this.form, 'save');" />
		</td></tr>
	</table>
	</form>
{/if}

