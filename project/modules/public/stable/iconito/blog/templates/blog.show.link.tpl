{if $kind=="2"}
	{if 1 OR $canManageLink}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Liens-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
		<DIV STYLE="float:right;">
<input style="" class="form_button" onclick="self.location='{copixurl dest="blog|admin|prepareEditLink" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.new"}" />
		</DIV><H1>{i18n key="blog.nav.links"}</H1>
		
		{if count($tabLinks)}
		<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		   <tr>
		      <th CLASS="liste_th">&nbsp;</th>
		      <th CLASS="liste_th">{i18n key="dao.bloglink.fields.name_blnk"}</th>
		      <th CLASS="liste_th">{i18n key="dao.bloglink.fields.url_blnk"}</th>
		      <th CLASS="liste_th">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptLink value=0}
		   
			   {foreach from=$tabLinks item=link}
			   <tr {cycle values=',class="alternate"' name="resultats"}>
			       <td></td>
			       <td>{$link->name_blnk}</td>
			       <td><a href="#" onClick="JavaScript:window.open('{$link->url_blnk}')">{$link->url_blnk}</a></td>
			       <td>
		            {if $cptLink>0}<a href="{copixurl dest=blog|admin|upLink id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
		            {if $cptLink<$tabLinks|@count-1}<a href="{copixurl dest=blog|admin|downLink id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
			       		<a href="{copixurl dest="blog|admin|prepareEditLink" id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a>
			       		<a href="{copixurl dest="blog|admin|deleteLink" id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>
			       </td>
			   </tr>
				 {assign var=cptLink value=$cptLink+1}
			   {/foreach}
		</table>
   {/if}   
		
		{i18n key="blog.link.list.nbPublies" pNb=$tabLinks|@count}
		
		
	{/if}
{/if}