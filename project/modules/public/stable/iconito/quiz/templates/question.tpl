<h3>{i18n key="quiz.msg.questionCount" noEscape=1} {$ppo->question->order}</h3>

{if $ppo->error}
    <p class="quiz-error">
    {$ppo->error}
    </p>
{/if}
{if $ppo->userResp}
    <p class="quiz-u-resp">
        {i18n key="quiz.msg.alreadyQResp" noEscape=1}
    </p>
{/if}
<p class="quiz-response">
    {if $ppo->question->content_txt != null}{$ppo->question->content_txt}{/if}
    {if $ppo->question->content_pic != null}<img src="{$ppo->question->content_pic}" />{/if}
</p>
<form action="{copixurl dest="quiz|default|save" id=$ppo->question->quiz_id qId=$ppo->prev.id}" method="get">
{if $ppo->type == radio}
<ul id="quiz-response-list">
    {foreach from=$ppo->choices item=choice}
    <li {if $choice.user}class="quiz-user"{/if}><label for="response">
        {if $choice.txt != null}{$choice.txt}{/if}
        {if $choice.pic != null}<img src="{$choice.pic}" />{/if}</label>
        <input type="{$ppo->select}" name="response[]" id="response[]" value="{$choice.id}" {if $choice.user}checked="checked"{/if} />
    </li>
    {/foreach}
</ul>
{else}
    {i18n key="quiz.msg.response" noEscape=1} : <input type="text" name="response" id="response" />
{/if}
<div class="quiz-clear"></div>
<a href="{copixurl dest="quiz|default|question" id=$ppo->question->id_quiz qId=$ppo->prev.id}"><button class="quiz-prev">{i18n key="quiz.msg.prev" noEscape=1}</button></a>
<input type="submit" value="{i18n key="quiz.msg.next" noEscape=1}" class="quiz-next">
</form>