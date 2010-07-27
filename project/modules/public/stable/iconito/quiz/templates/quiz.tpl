{if empty($ppo->quiz)}
    <div class="noquiz-button">
		{i18n key="quiz.errors.noQuiz" noEscape=1}
    </div>
{else}
    {foreach from=$ppo->quiz item=quiz }
    <div class="loading-button">
        <a href="{copixurl dest="quiz|default|quiz" id=$quiz.id}" class="button" >{$quiz.name}</a>
    </div>
    {/foreach}
{/if}