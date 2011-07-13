<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->

<h2>{i18n key="blog.get.list.comment.title"}</h2>


<div id="comments">


	 {if $resultats|@count >0}
	 	{assign var=cptCom value=1}
	   {foreach from=$resultats item=comment}
		 	{if $canManageComment OR $comment->is_online}
			
		 			<DIV class="commentBody"><div class="is_online{$comment->is_online}"><DIV CLASS="commentAuthor"><div class="commentCount"><A>{$cptCom}</A></div>

	       <b>{i18n key="blog.messages.comment2" 1=$comment->authorname_bacc|escape 2=$comment->date_bacc|datei18n 3=$comment->time_bacc|escape}
		
		{if $comment->authorweb_bacc!=null}<a rel="nofollow" href="{$comment->authorweb_bacc}" title="" target="_blank">{i18n key="blog.comment.web"}</a>{/if}

		{if $comment->authoremail_bacc!=null}
		<a href="MAILTO:{$comment->authoremail_bacc}" title="">{i18n key="blog.comment.email"}</a>
		{/if}
		
		 </b> ({i18n key="blog.messages.ip"} : {$comment->authorip_bacc})
		 	   {if $canManageComment}
				 <a class="button button-update" href="{copixurl dest="blog|admin|prepareEditComment" id_blog=$id_blog id_bact=$comment->id_bact id_bacc=$comment->id_bacc }" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a> 
				- <a class="button button-delete" href="{copixurl dest="blog|admin|deleteComment" id_blog=$id_blog id_bact=$comment->id_bact id_bacc=$comment->id_bacc }" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>

				- 
				{if $comment->is_online eq 0}
				<a class="button button-confirm" href="{copixurl dest="blog|admin|onlineComment" id_blog=$id_blog id_bact=$comment->id_bact id_bacc=$comment->id_bacc }" title="{i18n key="blog.action.comment.doOnline"}">{i18n key="blog.action.comment.doOnline"}</a>
				{else}
				<a class="button button-cancel" href="{copixurl dest="blog|admin|offlineComment" id_blog=$id_blog id_bact=$comment->id_bact id_bacc=$comment->id_bacc }" title="{i18n key="blog.action.comment.doOffline"}">{i18n key="blog.action.comment.doOffline"}</a>
				{/if}
				
				
	   {/if}

		 </DIV>
		 
		 <div class="commentMsg">{$comment->content_bacc|wiki}</div>

				 
		</div></DIV>
		 {assign var=cptCom value=$cptCom+1}
				 
			{/if}
	   {/foreach}
	 {else}
			{i18n key="blog.comment.list.nodata"}
		 
   {/if}   


<h2>{i18n key="blog.add.comment.title"}</h2>
{if $showErrors}
	<ul class="mesgErrors">
	  {foreach from=$errors item=message}
	    <li>{$message}</li>
	  {/foreach}
	</ul>
{/if}

<form name="commentEdit" action="{copixurl dest="blog|admin|validComment" id_blog=$id_blog id_bact=$id_bact}" method="post" class="copixForm">
<input type="hidden" name="id_bacc" value="{$toEdit->id_bacc}">
<input type="hidden" name="authorid_bacc" value="{$toEdit->authorid_bacc}">


<table class="editItems">
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authorname_bacc'}</td>
	  	<td><input type="text" name="authorname_bacc" value="{$toEdit->authorname_bacc|escape}" class="text"></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authoremail_bacc'}</td>
	  	<td><input type="text" name="authoremail_bacc" value="{$toEdit->authoremail_bacc|escape}" class="text"></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.authorweb_bacc'}</td>
	  	<td><input type="text" name="authorweb_bacc" value="{$toEdit->authorweb_bacc|escape}" class="text"></td>
   </tr>
   <tr>
      <td>{i18n key='dao.blogarticlecomment.fields.content_bacc'}</td>
	  	<td><textarea name="content_bacc" class="text">{$toEdit->content_bacc|escape}</textarea></td>
   </tr>
	 <tr>
	 		<td></td>
			<td><input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" /></td>
	 </tr>
</table>
</form>

</DIV>