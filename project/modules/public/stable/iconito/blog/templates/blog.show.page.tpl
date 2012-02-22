{if $kind=="5"}
	{if 1 OR $canManagePage}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--Pages-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->

		<a class="floatright button button-add" href="{copixurl dest="blog|admin|prepareEditPage" id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.new"}</a>
		<h2>{i18n key="blog.nav.pages"}</h2>
<p>{i18n key="blog.get.edit.page.minihelp"}</p>

{i18n key="blog.pages.list.nbPublies" pNb=$tabPages|@count}
	
    {if count($tabPages)}

		<table class="viewItems">
		   <tr>
		      <th>{i18n key="dao.blogpage.fields.name_bpge"}</th>
		      {* <th>{i18n key="dao.blogpage.fields.url_bpge"}</th> *}
		      <th colspan="4">{i18n key="blog.list.actions"}</th>
		   </tr>

			 {assign var=cptPge value=0}
			   {foreach from=$tabPages item=page}
			   <tr class="list_line{$cptPge%2}" {cycle values=',class="alternate"' name="resultats"}>
			       <td><div class="is_online{$page->is_online}">{$page->name_bpge}</div></td>
			       {* <td>{$page->url_bpge}</td> *}
			       <td class="action"><div class="is_online{$page->is_online}"><a class="button button-update" href="{copixurl dest="blog|admin|prepareEditPage" id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.update"}">{i18n key="copix:common.buttons.update"}</a></div></td>
                   <td class="action"><div class="is_online{$page->is_online}"><a class="button button-delete" href="{copixurl dest="blog|admin|deletePage" id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}" title="{i18n key="copix:common.buttons.delete"}">{i18n key="copix:common.buttons.delete"}</a></div></td>
                   <td class="action"><div class="is_online{$page->is_online}">{if $cptPge>0}<a class="button button-sortup" href="{copixurl dest=blog|admin|upPage id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}">{i18n key=copix:common.buttons.moveup}</a>{/if}</div></td>
		           <td class="action"><div class="is_online{$page->is_online}">{if $cptPge<$tabPages|@count-1}<a class="button button-sortdown" href="{copixurl dest=blog|admin|downPage id_bpge=$page->id_bpge id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.movedown"}</a>{/if}</div>
                    </td>
			   </tr>
				 {assign var=cptPge value=$cptPge+1}
			   {/foreach}
		</table>
    {/if}   
		
		
	{/if}
{/if}