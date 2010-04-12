<div class="navigation" style="">

        <ul>
        {foreach from=$tree->ecoles key=ecole_key item=ecole_value}
        <li><a href="{copixurl dest="comptes||getNode" type="BU_ECOLE" id="$ecole_key"}">{$ecole_value->info.nom}</a>
    
            <ul>
            {foreach from=$ecole_value->classes key=classe_key item=classe_value}
            <li><a href="{copixurl dest="comptes||getNode" type="BU_CLASSE" id="$classe_key"}">{$classe_value->info.nom}</a>
        
            </li>
            {/foreach}
            </ul>
    
        </li>
        {/foreach}
        </ul>


</div>
