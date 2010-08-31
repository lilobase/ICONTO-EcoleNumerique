{if $kind=="1"}  
	{if 1 OR $canManageCategory}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--CATEGORIES-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
				<DIV STYLE="float:right;">
<input style="" class="button button-add" onclick="self.location='{copixurl dest="blog|admin|prepareEditCategory" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.new"}" />
		</DIV><H1>{i18n key="blog.nav.categories"}</H1>
		
		{if count($tabArticleCategory)}
		<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		   <tr>
		      <th CLASS="liste_th">&nbsp;</th>
		      <th CLASS="liste_th">{i18n key="dao.blogarticlecategory.fields.name_bacg"}</th>
		      {* <th CLASS="liste_th">{i18n key="dao.blogarticlecategory.fields.url_bacg"}</th> *}
		      <th CLASS="liste_th">{i18n key="blog.list.actions"}</th>
		   </tr>


			 {assign var=cptCat value=0}
		   
			   {foreach from=$tabArticleCategory item=cat}
			   <tr {cycle values=',class="alternate"' name="resultats"}>
			       <td></td>
			       <td>{$cat->name_bacg}</td>
			       {* <td>{$cat->url_bacg}</td> *}
			       <td>
		            {if $cptCat>0}<a href="{copixurl dest=blog|admin|upCategory id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
		            {if $cptCat<$tabArticleCategory|@count-1}<a href="{copixurl dest=blog|admin|downCategory id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
			       		<a href="{copixurl dest="blog|admin|prepareEditCategory" id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a>
			       		{if $cat->link_art!=null}
				       		<a href="{copixurl dest="blog|admin|showBlog" id_blog=$id_blog kind=0 selectCategory=$cat->id_bacg}" title="{i18n key="blog.list.category.nbarticles" pNb=$cat->nb_articles}">{i18n key="blog.list.category.nbarticles" pNb=$cat->nb_articles}</a>
				       	{else}
	 			       		<a href="{copixurl dest="blog|admin|deleteCategory" id_bacg=$cat->id_bacg id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>
			       		{/if}
			       </td>
			   </tr>
				 {assign var=cptCat value=$cptCat+1}
			   {/foreach}
		</table>
    {/if}   
		
		{i18n key="blog.category.list.nbPublies" pNb=$tabArticleCategory|@count}
		
	{/if}
{/if}