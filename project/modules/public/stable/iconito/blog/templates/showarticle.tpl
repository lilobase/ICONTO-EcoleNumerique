{if !$article}
	{i18n key="blog.message.noarticle"}
{else}
<div id="article">

<h2>{$article->name_bact}</h2>
<div class="postInfo">
	{assign var=cptCat value=1}
	{assign var=listCat value=""}
	{foreach from=$article->categories item=categorie}
		{copixurl dest="blog||" blog=$article->url_blog cat=$categorie->url_bacg assign="url"}
		{assign var=thisA value='<a href="'|cat:$url|cat:'">'|cat:$categorie->name_bacg|cat:'</a> '"}
		{assign var=listCat value=$listCat|cat:$thisA}
		{if $cptCat<$article->categories|@count}{assign var=listCat value=$listCat|cat:' - '}{/if}
		{assign var=cptCat value=$cptCat+1}
	{/foreach}
	{if !$article->categories|@count}{i18n key="blog.article.nocategory" assign="listCat"}{/if}
	{i18n key="blog.message.theAtIn" day=$article->date_bact|datei18n:text time=$article->time_bact|hour_format:"%H:%i" categ=$listCat noEscape=1}

</div>
<div class="postSummary">{$article->sumary_html_bact}</div>
<div class="postContent">{$article->content_html_bact}</div>

</div>

<div id="comments">

{if $listComment|@count>0 OR $blog->has_comments_activated}  

<h2>{i18n key="blog.get.list.comment.title"}</h2>

{if $listComment|@count>0}
	{assign var=cptCom value=1}
	{foreach from=$listComment item=comment}

<DIV class="commentBody"><DIV CLASS="commentAuthor"><div class="commentCount"><A>{$cptCom}</A></div>

		<b>{i18n key="blog.messages.comment2" 1=$comment->authorname_bacc|escape 2=$comment->date_bacc|datei18n 3=$comment->time_bacc|escape}
		
		{if $comment->authorweb_bacc!=null}<a rel="nofollow" href="{$comment->authorweb_bacc}" TITLE="" target="_blank">{i18n key="blog.comment.web"}</a>{/if}

		{if $comment->authoremail_bacc!=null}
		<a href="MAILTO:{$comment->authoremail_bacc}" TITLE="">{i18n key="blog.comment.email"}</a>
		{/if}
		
		 </b> </DIV>
		
			{$comment->content_bacc|wiki}
		</DIV>

		 {assign var=cptCom value=$cptCom+1}
	   {/foreach}
	 {else}

				<div>{i18n key="blog.comment.list.nodata"}</div>
		 
{/if}   

{if !$blog->has_comments_activated}
  <div>{i18n key="blog.add.comment.closed"}</div>
{elseif $canComment}

<h2 id="commform">{i18n key="blog.add.comment.title"}</h2>

{if $showErrors}
<div class="errorMessage">
<h2>{i18n key=copix:common.messages.error}</h2>
{ulli values=$errors}
</div>	
{/if}


<form name="commentEdit" action="{copixurl dest="blog||validComment" blog=$blog->url_blog article=$article->url_bact}#commform" method="post" class="">
<!-- <input type="text" name="url1" maxlength="100" value="Abracadabra" /> -->

<input type="hidden" name="id_bacc" value="{$toEdit->id_bacc}">
<input type="hidden" name="id_bact" value="{$article->id_bact}">
<input type="hidden" name="url_bact" value="{$article->url_bact}">
<input type="hidden" name="authorid_bacc" value="{$toEdit->authorid_bacc}">
<table class="" style="border:0;">
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authorname_bacc'}</td>
	  <td><input type="text" name="authorname_bacc" value="{$toEdit->authorname_bacc|escape}" class="text"></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authoremail_bacc'}</td>
	  <td><input type="email" name="authoremail_bacc" value="{$toEdit->authoremail_bacc|escape}" class="text" ></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authorweb_bacc'}</td>
	  <td><input type="url" name="authorweb_bacc" value="{$toEdit->authorweb_bacc|escape}" class="text" placeholder="http://www.iconito.fr"></td>
   </tr>
   <tr class="hidden">
      <td>{i18n key='dao.blogarticlecomment.fields.authorweb_bacc'}</td>
	  <td><input type="text" name="url2" value="Abracadabra" class="text"></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.content_bacc'}</td>
	  <td><textarea name="content_bacc" class="text">{$toEdit->content_bacc|escape}</textarea></td>
   </tr>

	 <tr>
	 		<td></td>
			<td>
			{if $blog->type_moderation_comments neq "POST"}
			<div>{i18n key='blog.comments.offline.info'}</div>
			{/if}
			
			
			<input type="submit" class="submit" value="{i18n key="copix:common.buttons.ok"}" /></td>
	 </tr>
</table>

</form>
{/if}

{/if}
{/if}

</div>