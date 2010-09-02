<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />
<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_blog.js"></SCRIPT>

{if $kind=="4"} 
	{if 1 OR $canManageOption}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Options-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
				<DIV CLASS="" STYLE="float:right">
<input style="" class="button button-update" onclick="self.location='{copixurl dest="blog|admin|prepareEditBlog" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.update"}" />
		</DIV>		<H1>{i18n key="blog.nav.options"}</H1>

		
		
		<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		   <tr>
		      <th>{i18n key='dao.blog.fields.name_blog'}</th>
			  	<td>{$blog->name_blog}</td>
		   </tr>
	   <tr>
	      <th>{i18n key='dao.blog.fields.id_ctpt'}</th>
		  	<td><a href="{copixurl dest="blog|admin|prepareEditBlogStyle" id_blog=$blog->id_blog kind=$kind}">{i18n key="blog.buttons.modify.style"}</a></td>
	   </tr>
	   <tr>
	      <th>{i18n key='dao.blog.fields.logo_blog'}</th>
		  	<td>{if $blog->logo_blog!=''}<img src="{copixurl dest="blog||logo" id_blog=$blog->id_blog}" border="0"><br /><a href="{copixurl dest="blog|admin|deleteLogoBlog" id_blog=$blog->id_blog kind=$kind}" title="{i18n key="blog.buttons.delete.logo"}">{i18n key="blog.buttons.delete.logo"}</a>{else}{i18n key="blog.nofound.logo"}{/if}</td>		
	   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.is_public'}</th>
			  	<td>{if $blog->is_public}{i18n key="blog.oui"}{else}{i18n key="blog.non"}{/if}</td>
		   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.has_comments_activated'}</th>
			  	<td>{if $blog->has_comments_activated}{i18n key="blog.oui"}{else}{i18n key="blog.non"}{/if}</td>
		   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.type_moderation_comments'}</th>
			  	<td>{if $blog->type_moderation_comments eq 'POST'}{i18n key="blog.type_moderation_comments.post"}{else}{i18n key="blog.type_moderation_comments.pre"}{/if}</td>
		   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.default_format_articles'}</th>
			  	<td>
					{assign var="key" value="blog.default_format_articles."|cat:$blog->default_format_articles}
					{i18n key="$key"}</td>
		   </tr>
		   {if $magicmail_infos}
		   <tr>
		      <th>{i18n key='dao.blog.fields.magicmail'}</th>
			  	<td>
			  		{if $magicmail_infos->login}
			  			{i18n key='dao.blog.fields.magicmail_actif'}
						{$magicmail_infos->login}@{$magicmail_infos->domain}
					{else}
						{i18n key='dao.blog.fields.magicmail_inactif'}
					{/if}
					<a href="{copixurl dest='magicmail|default|go' id=$magicmail_infos->id}" class="button button-update">{i18n key='dao.blog.fields.magicmail_change'}</a>
				</td>
		   </tr>
		   {/if}
		</table>
	{/if}
{else}
	{$RESULT}
{/if}

