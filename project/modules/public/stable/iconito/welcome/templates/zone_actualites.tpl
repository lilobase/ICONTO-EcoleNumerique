<div id="welcome_actualites">
{if $titre}<div class="titre">{$titre}</div>{/if}
<script language="javascript" src="{copixurl dest="blog||js" blog=$blog nb=$nb colonnes=$colonnes chapo=$chapo hr=$hr}"></script>
<script language="javascript">document.writeln(blogJs);</script>
{if $hreflib}<div class="hreflib"><a href="{copixurl dest="blog||" blog=$blog}">{$hreflib}</a></div>{/if}
</div>
