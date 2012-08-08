{if $kind=="0"}
	{if 1 OR $canManageArticle}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--ARTICLES-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
<a class="floatright button button-add" href="{copixurl dest="blog|admin|prepareEditArticle" id_blog=$id_blog kind=$kind}">{i18n key="copix:common.buttons.new"}</a>
		<h2>{i18n key="blog.nav.articles"}</h2>
	
		<form name="findArticle" action="{copixurl dest="blog|admin|showBlog" id_blog=$id_blog p=$p}" method="post" class="copixForm">
		<input type="hidden" name="kind" value="{$kind}" />
		<input type="hidden" name="id_bact" value="{$id_bact}" />

									<DIV CLASS="adminArticleForm">
									{i18n key=blog.messages.categories} :
									
										<select name="selectCategory">
											<option value=""></option>
											{foreach from=$tabArticleCategory item=cat}
												<option value="{$cat->id_bacg}">{$cat->name_bacg}</option>
											{/foreach}
										</select>
									
									{i18n key="blog.messages.month"} :
										<select name="selectMonth">
											<option value=""></option>
											{foreach from=$tabArticleMonth item=month}
											<option value="{$month.value}">{$month.text}</option>
											{/foreach}
										</select>

									<input type="submit" value="{i18n key="blog.buttons.ok"}" class="button button-confirm" />
									
					</DIV>


		   {if count($tabArticles)}
			<table class="viewItems">
            	<tr>
                	<th>{i18n key='dao.blogarticle.fields.name_bact'}</th>
                    <th>{i18n key="dao.blogarticle.fields.is_online"}</th>
                    <th colspan="3">{i18n key="blog.list.actions"}</th>
                 </tr>   
                       {counter start=1 assign="cpt"}
                       {foreach from=$tabArticles item=article}
                  <tr class="list_line{$cpt%2}">
                  	<td>
			   			<div class="adminArticleList">
			       		{if 0 && $article->expand}
			       			<a href="#" onClick="JavaScript:submitForm(document.findArticle.selectCategory.options[document.findArticle.selectCategory.selectedIndex].value, 'document.findArticle.selectMonth.options[document.findArticle.selectMonth.selectedIndex].value', '')" title="{$article->name_bact}">[-] {$article->name_bact}</a>
			       		{else}
			       			<a href="javascript:swapArticle('{$article->id_bact}');" title="">[+] <b>{$article->name_bact}</b></a>
			       		{/if}
			       		<br />
								
								
							 {assign var=cptCat value=1}
							 {assign var=listCat value=""}
                             {foreach from=$article->categories item=categorie}
                                      {assign var=thisA value="<a href=\"#\" onClick=\"JavaScript:submitForm(`$categorie->id_bacg`, '', '');\">`$categorie->name_bacg`</a>"}
                                        {assign var=listCat value=$listCat|cat:$thisA}
                                        {if $cptCat<$article->categories|@count}{assign var=listCat value=$listCat|cat:' - '}{/if}
                                        {assign var=cptCat value=$cptCat+1}
                             {/foreach}
							 {if !$article->categories|@count}{i18n key="blog.article.nocategory" assign="listCat"}{/if}
							 {i18n key="blog.message.theAtIn" day=$article->date_bact|datei18n:text time=$article->time_bact|hour_format:"%H:%i" categ=$listCat noEscape=1}
                        </div>
                             <DIV ID="expand{$article->id_bact}" CLASS="displayNone">
								<DIV CLASS="expand">
			       		{if 1 || $article->expand}
			       			{if $article->sumary_html_bact}{$article->sumary_html_bact}<hr/>{/if}
			       			{$article->content_html_bact}
			       		{/if}
								</DIV>
								</DIV>
						</td>
                        <td class="center">{if $article->is_online}<img src="{copixurl}themes/default/images/button-action/action_confirm.png" alt="{i18n key="blog.oui"}" />{else}<img src="{copixurl}themes/default/images/button-action/action_cancel.png" alt="{i18n key="blog.non"}" />{/if}</td>
                        <td class="action">{if $canDelete || ! $article->is_online}<a class="button button-update" href="{copixurl dest="blog|admin|prepareEditArticle" id_bact=$article->id_bact id_blog=$id_blog kind=$kind}" title="{i18n key="blog.messages.update"}">{i18n key="blog.messages.update"}</a>{/if}</td>
                        {if $canDelete}<td class="action">
			   				<a class="button button-delete" href="{copixurl dest="blog|admin|deleteArticle" id_bact=$article->id_bact id_blog=$id_blog kind=$kind selectCategory=$selectCategory selectMonth=$selectMonth}" title="{i18n key="blog.messages.delete"}">{i18n key="blog.messages.delete"}</a>
						 </td>
                         {else}<td></td>
                         {/if}
                         {if ($article->nbComment OR $article->nbComment_offline) OR $blog->has_comments_activated}
                         <td class="comment">
								
								{if $article->nbComment}
			   				<a class="button button-confirm" href="{copixurl dest="blog|admin|listComment" id_bact=$article->id_bact id_blog=$id_blog}" title="{i18n key="blog.messages.article.comment" pNb=$article->nbComment}">{i18n key="blog.messages.article.comment" pNb=$article->nbComment}</a>
								{/if}
								
								{if $article->nbComment_offline AND $canAdminComments}
			   				<a class="button button-cancel" href="{copixurl dest="blog|admin|listComment" id_bact=$article->id_bact id_blog=$id_blog}" title="{i18n key="blog.messages.article.commentOffline" pNb=$article->nbComment_offline}">{i18n key="blog.messages.article.commentOffline" pNb=$article->nbComment_offline}</a>
								{/if}
                        {else}
                        <td>
                        {/if}
						</td>
					</tr>
                    {counter}
			   {/foreach}
               </table>
			   {$pagerArticles} 
			 {else}
				<p>{i18n key="blog.article.list.nodata"}</p>
		   {/if}   
           

		{literal}
		<script type="text/javascript">
		//<![CDATA[
			function selectCategory(id_bacg) {
				cat = document.findArticle.selectCategory;
				i=0;
				while(i<cat.length) {
					if(cat.options[i].value==id_bacg) {break;}
					i++;
				}
				if(i<cat.length) {
					cat.selectedIndex = i;
				} else {
					cat.selectedIndex = 0;
				}
			}
	
			function selectMonth(numMonth) {
				month = document.findArticle.selectMonth;
				i=0;
				while(i<month.length) {
					if(month.options[i].value==numMonth) {break;}
					i++;
				}
				if(i<month.length) {
					month.selectedIndex = i;
				} else {
					month.selectedIndex = 0;
				}
			}
			
			function submitForm (id_bacg, month, id_bact) {
				selectCategory(id_bacg);
				selectMonth(month);
				document.findArticle.id_bact.value = id_bact;
				document.findArticle.submit();				
				return false;
			}
			
			{/literal}
			selectCategory({$selectCategory});
			selectMonth({$selectMonth});
			{literal}
		//]]>
		</script>
		{/literal}
		</form>
	{/if}
{/if}
