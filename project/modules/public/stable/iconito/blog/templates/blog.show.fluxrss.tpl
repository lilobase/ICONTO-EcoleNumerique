{if $kind=="6"}
	<!--{if 1 OR $canManageRss}-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Liens-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		
		<DIV STYLE="float:right;">
<input style="" class="button button-add" onclick="self.location='{copixurl dest="blog|admin|prepareEditRss" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.new"}" />
		</DIV><H1>{i18n key="blog.nav.rss"}</H1>
		
		{if count($tabRss)}
		<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		   <tr>
		      <th CLASS="liste_th">&nbsp;</th>
		      <th CLASS="liste_th">{i18n key="dao.blogrss.fields.name_bfrs"}</th>
		      <th CLASS="liste_th">{i18n key="dao.blogrss.fields.url_bfrs"}</th>
		      <th CLASS="liste_th">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptRss value=0}
		   
			   {foreach from=$tabRss item=rss}
			   <tr {cycle values=',class="alternate"' name="resultats"}>
			       <td></td>
			       <td>{$rss->name_bfrs}</td>
			       <td><a href="#" onClick="JavaScript:window.open('{$rss->url_bfrs}')">{$rss->url_bfrs}</a></td>
			       <td>
		            {if $cptRss>0}<a href="{copixurl dest=blog|admin|upRss id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
		            {if $cptRss<$tabRss|@count-1}<a href="{copixurl dest=blog|admin|downRss id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.movedown}</a>{else}&nbsp;&nbsp;&nbsp;{/if}
			       		<a href="{copixurl dest="blog|admin|prepareEditRss" id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a>
			       		<a href="{copixurl dest="blog|admin|deleteRss" id_bfrs=$rss->id_bfrs id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a>
			       </td>
			   </tr>
				 {assign var=cptRss value=$cptRss+1}
			   {/foreach}
		</table>
   {/if}   
		
		{i18n key="blog.rss.list.nbPublies" pNb=$tabRss|@count}
		
		
	<!--{/if}-->
{/if}