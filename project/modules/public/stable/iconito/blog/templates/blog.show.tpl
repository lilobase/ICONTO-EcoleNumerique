<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->
<script type="text/javascript" src="{copixurl}js/iconito/module_blog.js"></script>

{if $kind=="4"} 
	{if 1 OR $canManageOption}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Options-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<h2>{i18n key="blog.nav.options"}</h2>
		<table class="editItems">
		   <tr>
		      <th>{i18n key='dao.blog.fields.name_blog'}</th>
			  <td>{$blog->name_blog}</td>
		   </tr>
	   <tr>
	      <th>{i18n key='dao.blog.fields.logo_blog'}</th>
		  	<td>{if $blog->logo_blog!=''}<img src="{copixurl dest="blog||logo" id_blog=$blog->id_blog}" alt=""><br /><a class="button button-delete" href="{copixurl dest="blog|admin|deleteLogoBlog" id_blog=$blog->id_blog kind=$kind}" title="{i18n key="blog.buttons.delete.logo"}">{i18n key="blog.buttons.delete.logo"}</a>{else}{i18n key="blog.nofound.logo"}{/if}</td>		
	   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.is_public'}</th>
			  	<td>{if $blog->is_public}{i18n key="blog.oui"}{else}{i18n key="blog.non"}{/if}</td>
		   </tr>
           <tr>
               <th>{i18n key='dao.blog.fields.privacy'}</th>
               <td>{i18n key="blog.privacy."|cat:$blog->privacy}</td>
           </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.has_comments_activated'}</th>
			  	<td>{if $blog->has_comments_activated}{i18n key="blog.oui"}{else}{i18n key="blog.non"}{/if}</td>
		   </tr>
		   <tr>
		      <th>{i18n key='dao.blog.fields.type_moderation_comments'}</th>
			  	<td>{if $blog->type_moderation_comments eq 'POST'}{i18n key="blog.type_moderation_comments.post"}{else}{i18n key="blog.type_moderation_comments.pre"}{/if}</td>
		   </tr>
        {if $can_format_articles}
		   <tr>
		      <th>{i18n key='dao.blog.fields.default_format_articles'}</th>
			  	<td>
					{assign var="key" value="blog.default_format_articles."|cat:$blog->default_format_articles}
					{i18n key="$key"}</td>
		   </tr>
        {/if}
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
           <tr>
           		<td></td>
                <td><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditBlog" id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.update"}</a></td>
           </tr>
			<tr class="editCss">
            	<th>{i18n key='dao.blog.fields.id_ctpt'}</th>
		  		<td><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditBlogStyle" id_blog=$blog->id_blog kind=$kind}">{i18n key="blog.buttons.modify.style"}</a></td>
            </tr>
        </table>
	{/if}
{else}
	{$RESULT}
{/if}

