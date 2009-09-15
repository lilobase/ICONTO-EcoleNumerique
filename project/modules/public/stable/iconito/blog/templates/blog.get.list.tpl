<h2>{i18n key="blog.get.list.blog.title"}</h2>
<table class="CopixTable">
   <tr>
      <th>&nbsp;</th>
      <th>{i18n key="dao.blog.fields.name_blog"}</th>
      <th align="right">{i18n key="blog.list.actions"}</th>
   </tr>
   <tr>
      <th colspan="2">
      	{i18n key="blog.list.nbPublies" 1=$resultats|@count}
      </th>
      <th>
      	{if $canCreate}
	      	<a href="{copixurl dest="blog|admin|prepareEditBlog"}" title="{i18n key="copix:common.buttons.new"}"><img src="{copixurl}img/tools/new.png" alt="{i18n key="copix:common.buttons.new"}" /></a>
      	{/if}
      </th>
   </tr>
   {if count($resultats)}
	   {foreach from=$resultats item=blog}
	   <tr {cycle values=',class="alternate"' name="resultats"}>
	       <td>{popupinformation text=$blog->name_blog}
	            {i18n key="copix:common.messages.desc"} : {$blog->name_blog} {/popupinformation}</td>
	       <td>{$blog->name_blog}</td>
	       <td>
	       	{if $blog->canWrite}
	       		<a href="{copixurl dest="blog|admin|prepareEditBlog" id_blog=$blog->id_blog}" title="{i18n key="copix:common.buttons.update"}"><img src="{copixurl}img/tools/update.png" alt="{i18n key="copix:common.buttons.update"}" /></a>
	       		<a href="{copixurl dest="blog|admin|deleteBlog" id_blog=$blog->id_blog}" title="{i18n key="copix:common.buttons.delete"}"><img src="{copixurl}img/tools/delete.png" alt="{i18n key="copix:common.buttons.delete"}" /></a>
	       	{/if}
	       	{if $blog->canRead}
	       		<a href="{copixurl dest="blog|admin|showBlog" id_blog=$blog->id_blog}" title="{i18n key="copix:common.buttons.show"}"><img src="{copixurl}img/tools/show.png" alt="{i18n key="copix:common.buttons.show"}" /></a>
	       	{/if}
	       </td>
	   </tr>
	   {/foreach}
	 {else}
	   <tr>
	      <td colspan="3">{i18n key="blog.list.nodata"}</td>
	   </tr>
   {/if}   
</table>