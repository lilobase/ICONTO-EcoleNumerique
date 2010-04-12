<div class="navigation" style="">

    <ul>
        {foreach from=$tree->classes key=classe_key item=classe_value}
            <li><a href="{copixurl dest="comptes||getNode" type="BU_CLASSE" id="$classe_key"}">{$classe_value->info.nom}</a></li>
        {/foreach}
    </ul>


</div>
