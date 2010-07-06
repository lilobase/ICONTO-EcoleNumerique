<h2>{i18n key="quiz.msg.listQuiz" noEscape=1}</h2> 
{if empty($ppo->quiz)}

        <h4 class="quiz-index-title">{i18n key="quiz.errors.noQuiz" noEscape=1}</h4>
{else}
    {foreach from=$ppo->quiz item=quiz }
    <div class="quiz-quiz">
        <a href="{copixurl dest="quiz|default|quiz" id=$quiz.id}" class="button" >{$quiz.name}</a>
    </div>
    {/foreach}
{/if}