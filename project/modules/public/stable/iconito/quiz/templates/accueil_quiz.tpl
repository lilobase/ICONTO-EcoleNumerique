<div id="quiz-do">
	<div class="content-panel qd-header">
		<div class="qd-author">
		{i18n key="quiz.msg.author" noEscape=1}<br/>
		{$ppo->surname} {$ppo->nameAuthor}
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

	<ul class="qd-questions">
		<li class="header-questions"><span>{i18n key="quiz.msg.answered" noEscape=1}</span>{i18n key="quiz.msg.questionCountShort" noEscape=1} {i18n key="quiz.admin.enonce" noEscape=1} </li>
        {foreach from=$ppo->questions item=question key=curQuestNum}
		<li class="content-panel qd-question">
			<a class="{if $question.userResp}qd-question-done{else}qd-question-todo{/if}" href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$question.ct->id'}">
			<span class="badge">{$curQuestNum+1}</span> {$question.ct->name|utf8_decode}
			</a>
		</li>
		{/foreach}
	</ul>


	<div class="content-panel right qd-msg">
	{if $ppo->uResp}
		<div class="qd-alert">
		{if $ppo->uEnd}
		{i18n key="quiz.msg.finish" noEscape=1}
		{else}
		{i18n key="quiz.msg.alreadyBegin" noEscape=1}
		{/if}
		</div>
	{/if}
		{if $ppo->uResp}
			<a class="button button-continue" href="{copixurl dest="quiz|default|question" id=$ppo->quizId qId=$ppo->next}" title="{i18n key="quiz.msg.start" noEscape=1}">
			{if $ppo->uEnd}
				{i18n key="quiz.msg.restart" noEscape=1}
			{else}
				{i18n key="quiz.msg.continue" noEscape=1}
			{/if}
		{else}
			{i18n key="quiz.msg.start" noEscape=1}
		{/if}
		</a>
    </div>

</div>
