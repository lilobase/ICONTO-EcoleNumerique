<!--<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_blog_admin.css"}" />-->

{if $rss==null}
	<h1>{i18n key="blog.get.create.rss.title"}</h1>
{else}
	<h1>{i18n key="blog.get.edit.rss.title"}</h1>
{/if}

{if $showErrors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<form id="rssEdit" action="{copixurl dest="blog|admin|validRss" id_blog=$id_blog kind=$kind}" method="post" class="copixForm">
<table class="editItems">

   <tr>
      <th><label for="name_bfrs">{i18n key='dao.blogfluxrss.fields.name_bfrs'}</label></th>
	  <td><input type="text" id="name_bfrs" name="name_bfrs" value="{$rss->name_bfrs|escape}" /></td>
   </tr>
   <tr>
      <th><label for="url_bfrs">{i18n key='dao.blogfluxrss.fields.url_bfrs'}</label></th>
	  <td><input type="text" id="url_bfrs" name="url_bfrs" value="{$rss->url_bfrs|escape}" /></td>
   </tr>
	 <tr>
     	<td></td>
        <td>
            <input type="hidden" name="id_bfrs" value="{$rss->id_bfrs}" />
            <input type="button" class="button button-cancel" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=$kind}'" />
            <input type="submit" class="button button-confirm" value="{i18n key="copix:common.buttons.ok"}" />
            
		</td>
    </tr>
</table>

</form>