{if empty($ppo->quizList)}
    <div class="noquiz-button">
		{i18n key="quiz.errors.noQuiz" noEscape=1}
    </div>
{else}
    <table id="quiz-table">
    <thead>
    <th></th>
    <th>{i18n key="quiz.table.published" noEscape=1}</th>
    <th>{i18n key="quiz.table.datestart" noEscape=1}</th>
    <th>{i18n key="quiz.table.dateend" noEscape=1}</th>
    <th>{i18n key="quiz.table.answers" noEscape=1}</th>
    <th></th>
    </thead>
    <tbody>
    {foreach from=$ppo->quizList item=quiz }
    <tr class="{cycle values="row1, row2"}">
        <td class="quiz-colstart">
            <a href="{copixurl dest="quiz|admin|modif" id=$quiz.id qaction="modif"}" class="button button-update">
            	<div class="quiz-title">{$quiz.name}</div>
            	<div class="quiz-description">{$quiz.description|truncate:100:'...'|strip_tags}</div>
            </a>
        </td>
        <td class="quiz-col80 center">
            {if $quiz.lock == 0}
            <a class="quiz-published"></a>
			{else}
            <a class="quiz-unpublished"></a>
			{/if}
        </td>
        <td class="quiz-col80 center">
        	{$quiz.date_start}
        </td>
        <td class="quiz-col80 center">
        	{$quiz.date_end}
        </td>
        <td class="quiz-col80 center">
        	<a href="{copixurl dest="quiz|admin|results" id=$quiz.id}" class="button button-results">
        	{$quiz.numResp}
        	</a>
        </td>
        <td class="quiz-col100 quiz-colend">
			<a href="{copixurl dest="quiz|admin|delQuiz" id=$quiz.id}" id="q-suppr" class="button button-delete">
			{i18n key="quiz.admin.delQuiz" noEscape=1}
			</a>
        </td>
    </tr>
    {/foreach}
    </tbody>

    </table>
{/if}