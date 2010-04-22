<h3>{i18n key="quiz.msg.questionCount" noEscape=1} {$ppo->question->order}</h3>
<!--[if lte IE 7]><div class="ISIE67"><![endif]-->
<a href="{copixurl dest="quiz|default|quiz" id=$ppo->question->id_quiz}" id="quiz-go-home"></a>
{if $ppo->help != null}
        <a href="#help-data" id="quiz-help"></a>
        <div style="display:none"><div id="help-data">
        {if $ppo->help != null}{$ppo->help}{/if}
        </div></div>
{/if}
<div class="quiz-clear"></div>
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
    <li {if $choice.user}class="quiz-user"{/if}>
        <input type="{$ppo->select}" name="response[]" id="id{$choice.id}" value="{$choice.id}" {if $choice.user}checked="checked"{/if} />
        <label for="id{$choice.id}">
            {if $choice.txt != null}{$choice.txt}{/if}
            {if $choice.pic != null}<img src="{$choice.pic}" />{/if}
        </label>
    </li>
    {/foreach}
</ul>
{else}
    {i18n key="quiz.msg.response" noEscape=1} : <input type="text" name="response" id="response" />
{/if}
<div class="quiz-clear"></div>
<input type="submit" value="{i18n key="quiz.msg.next" noEscape=1}" class="quiz-next">
</form>
<a class="quiz-prev" href="{copixurl dest="quiz|default|question" id=$ppo->question->id_quiz qId=$ppo->prev.id}">{i18n key="quiz.msg.prev" noEscape=1}</a>
<!--[if lte IE 7]></div><![endif]-->
<div class="quiz-clear"></div>

