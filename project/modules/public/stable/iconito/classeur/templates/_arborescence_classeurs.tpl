<ul>
    {foreach from=$ppo->classeurs item=classeur}
        {assign var=classeurId value=$classeur->id}
        <li class="classeur {if !isset($ppo->classeursOuverts[$classeurId])}collapsed{else}open{/if}">
            <p class="{if ($ppo->classeurId eq $classeur->id) && ($ppo->dossierCourant eq 0 || $ppo->dossierCourant eq null)}current{/if}">
            {if $classeur->hasDossiers()}
                <a href="#" class="expand-classeur {$classeur->id} switchFolder">
                {if !isset($ppo->classeursOuverts[$classeurId])}
                    <img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" />
                {else}
                    <img src="{copixurl}themes/default/images/sort_down_off.png" alt="-" />
                {/if}
            </a>
            {else}
                <img src="{copixurl}themes/default/images/sort_right_inactive.png" alt=">" class="switchFolder" />
            {/if}
            {if $ppo->field neq null && $ppo->format neq null}
                <a class="labelFolder" href="{copixurl dest="classeur||getClasseurPopup" classeurId=$classeur->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}">
            {else}
                <a class="labelFolder" href="{copixurl dest="classeur||voirContenu" classeurId=$classeur->id}">
            {/if}
            {if $ppo->withPersonal && $classeur->id eq $ppo->classeurPersonnel}
                {i18n key="classeur.message.personnalFolder"}
            {else}
                {$classeur->titre|escape}
            {/if}
            </a></p>
            <ul class="child {if !isset($ppo->classeursOuverts[$classeurId])}closed{/if}">
                {copixzone process=classeur|arborescenceDossiers classeurId=$classeur->id dossierCourant=$ppo->dossierCourant field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}
            </ul>
        </li>
    {/foreach}
</ul>
