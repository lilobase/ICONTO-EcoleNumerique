{if $kind=="1"}  
	{if 1 OR $canManageCategory}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--CATEGORIES-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
        <a class="floatright button button-add" href="{copixurl dest="blog|admin|prepareEditCategory" id_blog=$id_blog kind=$kind}" >{i18n key="copix:common.buttons.new"}</a>
		<h2>{i18n key="blog.nav.categories"}</h2>
        
		{i18n key="blog.category.list.nbPublies" pNb=$tabArticleCategory|@count}
		
        {if count($tabArticleCategory)}
		<table class="viewItems">
		   <tr>
		      <th>{i18n key="dao.blogarticlecategory.fields.name_bacg"}</th>
		      {* <th>{i18n key="dao.blogarticlecategory.fields.url_bacg"}</th> *}
		      <th colspan="4">{i18n key="blog.list.actions"}</th>
		   </tr>


			 {assign var=cptCat value=0}
		   
			   {foreach from=$tabArticleCategory item=cat}
			   <tr class="list_line{$cptCat%2}" {cycle values=',class="alternate"' name="resultats"}>
			       <td>{$cat->name_bacg}</td>
			       {* <td>{$cat->url_bacg}</td> *}
                    <td class="action"><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditCategory" id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a></td>
                    <td class="action">{if $cat->link_art!=null}
				       		<a class="button button-view" href="{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=0 selectCategory=$cat->id_bacg}" title="{i18n key="blog.list.category.nbarticles" pNb=$cat->nb_articles}">{i18n key="blog.list.category.nbarticles" pNb=$cat->nb_articles}</a>
				       	{else}
	 			       		<a class="button button-delete" href="{copixurl dest="blog|admin|deleteCategory" id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>
			       		{/if}
			       </td>
			       <td class="action">{if $cptCat>0}<a class="button button-sortup" href="{copixurl dest=blog|admin|upCategory id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{/if}</td>
                   <td class="action">{if $cptCat<$tabArticleCategory|@count-1}<a class="button button-sortdown" href="{copixurl dest=blog|admin|downCategory id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{/if}</td>
			   </tr>
				 {assign var=cptCat value=$cptCat+1}
			   {/foreach}
		</table>
    {/if}   
		
		
		
	{/if}
{/if}