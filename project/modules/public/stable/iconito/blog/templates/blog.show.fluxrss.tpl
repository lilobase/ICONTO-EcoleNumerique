{if $kind=="6"}
	<!--{if 1 OR $canManageRss}-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Liens-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
		<a class="floatright button button-add" href="{copixurl dest="blog|admin|prepareEditRss" id_blog=$id_blog kind=$kind}" >{i18n key="copix:common.buttons.new"}</a>
		<h2>{i18n key="blog.nav.rss"}</h2>
		
		{i18n key="blog.rss.list.nbPublies" pNb=$tabRss|@count}
		{if count($tabRss)}
		<table class="viewItems">
		   <tr>
		      <th>{i18n key="dao.blogfluxrss.fields.name_bfrs"}</th>
		      <th>{i18n key="dao.blogfluxrss.fields.url_bfrs"}</th>
		      <th colspan="4">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptRss value=0}
		   
			   {foreach from=$tabRss item=rss}
			   <tr class="list_line{$cptRss%2}" {cycle values=',class="alternate"' name="resultats"}>
			       <td>{$rss->name_bfrs}</td>
			       <td><a href="#" onClick="JavaScript:window.open('{$rss->url_bfrs}')">{$rss->url_bfrs}</a></td>
			       <td class="action"><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditRss" id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a></td>
                   <td class="action"><a class="button button-delete" href="{copixurl dest="blog|admin|deleteRss" id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a></td>
                   <td class="action">{if $cptRss>0}<a class="button button-sortup" href="{copixurl dest=blog|admin|upRss id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{/if}</td>
		           <td class="action">{if $cptRss<$tabRss|@count-1}<a class="button button-sortdown" href="{copixurl dest=blog|admin|downRss id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{/if}</td>
			   </tr>
				 {assign var=cptRss value=$cptRss+1}
			   {/foreach}
		</table>
   {/if}   
		
		
		
	<!--{/if}-->
{/if}