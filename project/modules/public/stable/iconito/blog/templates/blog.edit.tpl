<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->


{if $id_blog==null}
	<h2>{i18n key="blog.get.create.blog.title"}</h2>
{else}
	<h2>{i18n key="blog.get.edit.blog.title"}</h2>
{/if}

{if $showErrors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<form name="blogEdit" action="{copixurl dest="blog|admin|validBlog" kind=$kind}" method="post" enctype="multipart/form-data" class="copixForm">

<table class="editItems">

   <tr>
      <th>{i18n key='dao.blog.fields.name_blog'}</th>
	  	<td><input type="text" name="name_blog" value="{$blog->name_blog|escape}" /></td>
   </tr>
   <tr>
      <th>{i18n key='dao.blog.fields.logo_blog'}</th>
	  	<td>
 			{if $blog->logo_blog!=''}<img alt="{$blog->logo_blog}" src="{copixurl dest="blog||logo" id_blog=$blog->id_blog}" /><br />{/if}
            <input size="35" type="file" name="logoFile" /><br/><em>{i18n key='blog.logo.messages.instructions' nb=$logo_max_width}</em>
			</td>
   </tr>

   <tr>
      <th>{i18n key='dao.blog.fields.is_public'}</th>
	  	<td>{html_radios name="is_public" values=$is_public.values output=$is_public.output checked=$blog->is_public}</td>
   </tr>
   <tr>
       <th>{i18n key='dao.blog.fields.privacy'}</th>
       <td>
          <select name="privacy" id="privacy">
               <option value="0" {if $blog->privacy eq 0}selected{/if}>{i18n key="blog.privacy.0"}</option>
               <option value="10" {if $blog->privacy eq 10}selected{/if}>{i18n key="blog.privacy.10"}</option>
               <option value="20" {if $blog->privacy eq 20}selected{/if}>{i18n key="blog.privacy.20"}</option>
           </select>
       </td>
   </tr>
   <tr>
      <th>{i18n key='dao.blog.fields.has_comments_activated'}</th>
	  	<td>{html_radios name="has_comments_activated" values=$has_comments_activated.values output=$has_comments_activated.output checked=$blog->has_comments_activated}</td>
   </tr>

   <tr>
      <th>{i18n key='dao.blog.fields.type_moderation_comments'}</th>
	  	<td>{html_radios name="type_moderation_comments" values=$type_moderation_comments.values output=$type_moderation_comments.output checked=$blog->type_moderation_comments}</td>
   </tr>
{if $can_format_articles}
    <tr>
        <th>{i18n key='dao.blog.fields.default_format_articles'}</th>
        <td>{html_radios name="default_format_articles" values=$default_format_articles.values output=$default_format_articles.output checked=$blog->default_format_articles}</td>
    </tr>
{else}
    <input type="hidden" name="default_format_articles" value="{$default_format_articles}" />
{/if}
	 <tr><td></td>
     <td>
<input type="hidden" name="id_blog" value="{$blog->id_blog}" />
{if ($kind==null) or ($id_blog==null)}
	<input class="button button-cancel" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|listBlog"}'" />
{else}
	<input class="button button-cancel" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
{/if}
<input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" />
	 
	</td>	 
	 </tr>

	 
	 </table>
</form>
