<h3>{$ppo->name|utf8_decode}</h3>
<p id="quiz-author">{i18n key="quiz.msg.author" noEscape=1} {$ppo->surname} {$ppo->nameAuthor}</p>
{if $ppo->description != null}
    <div id="quiz-desc">{$ppo->description|utf8_decode}</div>
{/if}

{if $ppo->uResp}
   <p class="ui-state-highlight"><strong>
        {if $ppo->uEnd}
            {i18n key="quiz.msg.finish" noEscape=1}
        {else}
            {i18n key="quiz.msg.alreadyBegin" noEscape=1}
        {/if}
    </strong></p>
{/if}
<ul id="quiz-list">
    {foreach from=$ppo->questions item=question}
        <li {if $question.ct->userResp}class="quiz-user-resp"{/if}>
            <a class="button" href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$question.ct->id'}">
                {$question.ct->name|utf8_decode} {if $question.userResp}<div class="quiz-comment"></div>{/if}
            </a>
        </li>
    {/foreach}
</ul>
<div class="quiz-clear"></div>
<p><div class="quiz-comment"></div> = {i18n key="quiz.msg.alreadyResp" noEscape=1}</p>
<a id="start-quiz" class="button" href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$ppo->next}" title="{i18n key="quiz.msg.start" noEscape=1}">{i18n key="quiz.msg.start" noEscape=1}</a>