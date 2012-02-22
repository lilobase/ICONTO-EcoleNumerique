<h2>{i18n key="kne.title" noEscape=1}</h2>

{if empty($ppo->ressources)}
<div class="content-info">
    {i18n key="kne.emptyRessources" noEscape=1}
</div>
{elseif $ppo->ressources == 'confError'}
<div class="content-info">
    {i18n key="kne.badConfigSchool" noEscape=1}
</div>
{else}
    {foreach from=$ppo->ressources item=ressource}
    <div class="content-panel">
        <a href="{$ressource->URLRessource}" target="_blank">
            {$ressource->DisciplineRessource} : 
            <strong>{$ressource->TitreRessource}</strong>
            <em>{$ressource->EditeurRessource}</em>
        </a>
    </div>
    {/foreach}
{/if}