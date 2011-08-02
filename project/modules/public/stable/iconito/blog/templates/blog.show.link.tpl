{if $kind=="2"}
	{if 1 OR $canManageLink}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Liens-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
		
<a class="floatright button button-add" href="{copixurl dest="blog|admin|prepareEditLink" id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.new"}</a>
		<h2>{i18n key="blog.nav.links"}</h2>
		{i18n key="blog.link.list.nbPublies" pNb=$tabLinks|@count}
		{if count($tabLinks)}
		<table class="viewItems">
		   <tr>
		      <th>{i18n key="dao.bloglink.fields.name_blnk"}</th>
		      <th>{i18n key="dao.bloglink.fields.url_blnk"}</th>
		      <th colspan="4">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptLink value=0}
		   
			   {foreach from=$tabLinks item=link}
			   <tr class="list_line{$cptLink%2}" {cycle values=',class="alternate"' name="resultats"}>
			       <td>{$link->name_blnk}</td>
			       <td><a href="#" onClick="JavaScript:window.open('{$link->url_blnk}')">{$link->url_blnk}</a></td>
			       <td class="action"><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditLink" id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a></td>
			       <td class="action"><a class="button button-delete" href="{copixurl dest="blog|admin|deleteLink" id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a></td>
                   <td class="action">{if $cptLink>0}<a class="button button-sortup" href="{copixurl dest=blog|admin|upLink id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{/if}</td>
		           <td class="action">{if $cptLink<$tabLinks|@count-1}<a class="button button-sortdown" href="{copixurl dest=blog|admin|downLink id_blnk=$link->id_blnk id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{/if}</td>
			   </tr>
				 {assign var=cptLink value=$cptLink+1}
			   {/foreach}
		</table>
   {/if}   
		
		
		
		
	{/if}
{/if}