<h3>{$ppo->name}</h3>
<p id="quiz-author">{i18n key="quiz.msg.author" noEscape=1} {$ppo->surname} {$ppo->nameAuthor}</p>
{if $ppo->description != null}
    <div id="quiz-desc">{$ppo->description}</div>
{/if}
{if $ppo->pic != null}
    <img id="quiz-pic" scr="{$ppo->pic}">
{/if}

{if $ppo->uResp}
    <p class="quiz-u-resp">
        {if $ppo->uEnd}
            {i18n key="quiz.msg.finish" noEscape=1}
        {else}
            {i18n key="quiz.msg.alreadyBegin" noEscape=1}
        {/if}
    </p>
{/if}
<ul id="quiz-list">
    {foreach from=$ppo->questions item=question}
        <li {if $question.userResp}class="quiz-user-resp"{/if}>
            <a href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$question.id'}">
                {i18n key="quiz.msg.question" noEscape=1} {$question.order} {if $question.userResp}<div class="quiz-comment"></div>{/if}
            </a>
        </li>
    {/foreach}
</ul>
<div class="quiz-clear"></div>
<p><div class="quiz-comment"></div> = {i18n key="quiz.msg.alreadyResp" noEscape=1}</p>
<a id="start-quiz" href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$ppo->next.id}" title="{i18n key="quiz.msg.start" noEscape=1}">{i18n key="quiz.msg.start" noEscape=1}</a>