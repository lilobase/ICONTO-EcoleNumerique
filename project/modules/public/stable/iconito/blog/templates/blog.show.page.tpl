{if $kind=="5"}
	{if 1 OR $canManagePage}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Pages-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->

		<DIV STYLE="float:right">
<input style="" class="button button-add" onclick="self.location='{copixurl dest="blog|admin|prepareEditPage" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.new"}" />
		</DIV>
		<H1>{i18n key="blog.nav.pages"}</H1>

	<p>{i18n key="blog.get.edit.page.minihelp"}</p>

    {if count($tabPages)}

		<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		   <tr>
		      <th CLASS="liste_th">&nbsp;</th>
		      <th CLASS="liste_th">{i18n key="dao.blogpage.fields.name_bpge"}</th>
		      {* <th CLASS="liste_th">{i18n key="dao.blogpage.fields.url_bpge"}</th> *}
		      <th CLASS="liste_th">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptPge value=0}
			   {foreach from=$tabPages item=page}
			   <tr {cycle values=',class="alternate"' name="resultats"}>
			       <td></td>
			       <td><div class="is_online{$page->is_online}">{$page->name_bpge}</div></td>
			       {* <td>{$page->url_bpge}</td> *}
			       <td><div class="is_online{$page->is_online}">
		            {if $cptPge>0}<a href="{copixurl dest=blog|admin|upPage id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
		            {if $cptPge<$tabPages|@count-1}<a href="{copixurl dest=blog|admin|downPage id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.movedown"}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
			       		<a href="{copixurl dest="blog|admin|prepareEditPage" id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a>
			       		<a href="{copixurl dest="blog|admin|deletePage" id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>
</div>
			       </td>
			   </tr>
				 {assign var=cptPge value=$cptPge+1}
			   {/foreach}
		</table>
    {/if}   
		
		{i18n key="blog.pages.list.nbPublies" pNb=$tabPages|@count}
	{/if}
{/if}