{if $titre}<div class="titre">{$titre}</div>{/if}

{if count($listArticle)}

    <ul>
        {foreach from=$articles item=article}
        <li>
            <article>

                {if $showtitle}
                    <div><a title="{$article->name_bact|escape}" href="{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}">{$article->name_bact|escape}</a></div>
                {/if}
                
                {if $showdate OR $showtime OR $showcategorie OR $showparent}
                    <div>
                        {if $showdate}
                            {if $dateformat}
                                {$article->date_bact|strftime:$dateformat}
                            {else}
                                {i18n key="blog|blog.article.date" 1=$article->date_bact|datei18n}
                            {/if}
                        {/if}
                        
                        {if $showtime}
                            {$article->time_bact|time}
                        {/if}
                        

                        {if $showcategorie}
                            &bull; 
                            {assign var=cptCat value=1}
                            {foreach from=$article->categories item=categorie }
                                {$categorie->name_bacg|escape}
                                {if $cptCat<$article->categories|@count} - {/if}
                                {assign var=cptCat value=$cptCat+1}
                            {/foreach}
                        {/if}
                        
                        {if $showparent}
                            &bull; {$article->parent.nom}
                            
                            {if $article->parent.type eq 'BU_CLASSE'}
                                ({$article->parent.parent.nom})
                            {/if}
                            
                        {/if}

                    </div>
                       
                {/if}
                
                {if $chapo}
                    <div class="">{$article->sumary_html_bact}</div>
                {/if}

                {if $hr}
                    <hr />
                {/if}

            </article>
        </li>

        {/foreach}
    </ul>
{else}
	{i18n key="blog|blog.message.noArticle"}
{/if}

