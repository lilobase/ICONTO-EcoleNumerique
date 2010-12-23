{if $kind=="0"}
	{if 1 OR $canManageArticle}
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<!--ARTICLES-->
	  <!-- ----------------------------------------------------------- -->
	  <!-- ----------------------------------------------------------- -->
		<h1>{i18n key="blog.nav.articles"}</h1>
		<div class="floatright">
<input class="button button-add" onclick="self.location='{copixurl dest="blog|admin|prepareEditArticle" id_blog=$id_blog kind=$kind}'" type="button" value="{i18n key="copix:common.buttons.new"}" />
		</div>
		
		<form name="findArticle" action="{copixurl dest="blog|admin|showBlog" id_blog=$id_blog p=$p}" method="post" class="copixForm">
		<input type="hidden" name="kind" value="{$kind}">
		<input type="hidden" name="id_bact" value="{$id_bact}">

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
			   {foreach from=$tabArticles item=article}
			   <DIV CLASS="adminArticleList"><div class="is_online{$article->is_online}">
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

			   				<a href="{copixurl dest="blog|admin|prepareEditArticle" id_bact=$article->id_bact id_blog=$id_blog kind=$kind}" title="{i18n key="blog.messages.update"}">[{i18n key="blog.messages.update"}]</a>
								{if $canDelete}
			   				<a href="{copixurl dest="blog|admin|deleteArticle" id_bact=$article->id_bact id_blog=$id_blog kind=$kind selectCategory=$selectCategory selectMonth=$selectMonth}" title="{i18n key="blog.messages.delete"}">[{i18n key="blog.messages.delete"}]</a>
								{/if}
		{if ($article->nbComment OR $article->nbComment_offline) OR $blog->has_comments_activated}
								
								{if $article->nbComment}
			   				<a href="{copixurl dest="blog|admin|listComment" id_bact=$article->id_bact id_blog=$id_blog}" title="{i18n key="blog.messages.article.comment" pNb=$article->nbComment}">[{i18n key="blog.messages.article.comment" pNb=$article->nbComment}]</a>
								{/if}
								
								{if $article->nbComment_offline AND $canAdminComments}
			   				<a href="{copixurl dest="blog|admin|listComment" id_bact=$article->id_bact id_blog=$id_blog}" title="{i18n key="blog.messages.article.commentOffline" pNb=$article->nbComment_offline}">[{i18n key="blog.messages.article.commentOffline" pNb=$article->nbComment_offline}]</a>
								{/if}

		{/if}
								<DIV ID="expand{$article->id_bact}" CLASS="displayNone">
								<DIV CLASS="expand">
			       		{if 1 || $article->expand}
			       			{if $article->sumary_html_bact}{$article->sumary_html_bact}<hr/>{/if}
			       			{$article->content_html_bact}
			       		{/if}
								</DIV>
								</DIV>
								
			     </div></DIV>

			   {/foreach}
			   {$pagerArticles} 
			 {else}

						{i18n key="blog.article.list.nodata"}
				 
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