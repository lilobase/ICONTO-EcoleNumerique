<div id="welcome_actualites">
{if $titre}<div class="titre">{$titre}</div>{/if}
<script type="text/javascript" src="{copixurl dest="blog||js" blog=$blog nb=$nb colonnes=$colonnes chapo=$chapo hr=$hr id=$id}"></script>
<script type="text/javascript">document.writeln(blogJs);</script>
{if $hreflib}<div class="hreflib"><a href="{copixurl dest="blog||" blog=$blog}">{$hreflib}</a></div>{/if}
</div>
