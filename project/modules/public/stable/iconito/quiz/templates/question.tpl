{if !empty($ppo->help)}
    <div id="help-data" title="{i18n key="quiz.msg.info" noEscape=1}">{$ppo->help}</div>
{/if}

{if $ppo->quiz.opt_show_results == 'each'}
    <form action="{copixurl dest="quiz|default|saveAndGetAnswer" id=$ppo->question.id_quiz qId=$ppo->question.id}" method="post">
{else}
    <form action="{copixurl dest="quiz|default|save" id=$ppo->question.id_quiz qId=$ppo->question.id}" method="post">
{/if}
    <div id="quiz-do">
	<div class="content-panel qd-header">
		<div class="qd-author">
		{i18n key="quiz.msg.author" noEscape=1}<br/>
		{$ppo->surname} {$ppo->nameAuthor}<br /><br />
		{if !empty($ppo->help)}
                    <div id="qd-help" class="button button-info">{i18n key="quiz.msg.info" noEscape=1}</div>
		{/if}
		</div>
		<div class="qd-title">
		{$ppo->name|utf8_decode}
		</div>
		{if $ppo->description != null}
		<div class="qd-description">
		{$ppo->description|utf8_decode}
		</div>
		{/if}
	</div>

	<table class="qd-table">
	<tr>
	<td class="qd-button-cell">
		<a class="qd-button qd-button-back" href="{copixurl dest="quiz|default|question" id=$ppo->question->id_quiz qId=$ppo->prev}"></a>
	</td>
	<td class="content-panel">
		<div class="qd-badges center">
                    {foreach from=$ppo->questionTpl item=curQuestId key=curQuestNum}
                        {if $curQuestId != 'current' }
                            <span class="badge badge-off"><a href="{copixurl dest="quiz|default|question" id=$ppo->question->id_quiz qId=$curQuestId}">{$curQuestNum}</a></span>
                        {else}
                            <span class="badge badge-current">{$curQuestNum}</span>
                        {/if}
                    {/foreach}
		</div>

        {if $ppo->error}
            <div class="qd-usererror">
                {$ppo->error}
            </div>
        {/if}

		{if $ppo->userResp}
		    <div class="qd-usermsg">
		        {i18n key="quiz.msg.alreadyResp" noEscape=1}
            </div>
		{/if}

		<div class="qd-question">
			<div class="qd-title">{$ppo->question.name}</div>
			{$ppo->question.content}

			{if $ppo->type == radio}
			<ul class="qd-propositions">
				{foreach from=$ppo->choices item=choice}
				<li>
					<input type="{$ppo->select}" name="response[]" id="id{$choice.id}" value="{$choice.id}" {if $choice.user}checked="checked"{/if} />
					<label for="id{$choice.id}">{$choice.ct}</label>
				</li>
				{/foreach}
			</ul>
			{else}
			{i18n key="quiz.msg.response" noEscape=1} : <input type="text" name="response" id="response" />
			{/if}
		</div>
	</td>
	<td class="qd-button-cell">
		<input class="qd-button qd-button-next" type="submit" value="">
	</td>
	</tr>
	</table>
	<div class="content-panel center">
		<a class="button button-cancel" href="{copixurl dest="quiz|default|default"}">{i18n key="quiz.msg.stop" noEscape=1}</a>
        {if $ppo->quiz.opt_show_results == 'each'}
            <input class="button button-continue" type="submit" value="{i18n key="quiz.msg.answer" noEscape=1}">
        {else}
            <input class="button button-continue" type="submit" value="{if $ppo->next === false}{i18n key="quiz.msg.endQuestion" noEscape=1}{else}{i18n key="quiz.msg.nextQuestion" noEscape=1}{/if}">
        {/if}
    </div>

</div>
</form>
