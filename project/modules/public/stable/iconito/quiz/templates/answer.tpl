
{if !empty($ppo->help)}
    <div id="help-data" title="{i18n key="quiz.msg.info" noEscape=1}">{$ppo->help}</div>
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
                {else}
                    <div class="qd-usermsg">
                        Félicitations ! Tu as correctement répondu à la question.
                    </div>
                {/if}
                <div class="qd-question">
                    <div class="qd-title">{$ppo->question.name}</div>
                    {$ppo->question.content}
                    <p>
                        {if count($ppo->validChoices) > 1}
                            Les bonnes réponses sont :
                            <ul>
                                {foreach from=$ppo->validChoices item=choice}
                                    <li>{$choice}</li>
                                {/foreach}
                            </ul>
                        {else}
                            La bonne réponse est : {$ppo->validChoice}
                        {/if}
                    </p>
                    {$ppo->question.answer_detail}
                </div>
            </td>
            <td class="qd-button-cell">
              {if $ppo->nextQ === false}
                  <a class="qd-button qd-button-next" href="{copixurl dest="quiz|default|endQuestions" id=$ppo->question.id_quiz qId=$ppo->nextQ}">&nbsp;</a>
              {else}
                  <a class="qd-button qd-button-next" href="{copixurl dest="quiz|default|question" id=$ppo->question.id_quiz qId=$ppo->nextQ}">&nbsp;</a>
              {/if}
            </td>
        </tr>
    </table>
    <div class="content-panel center">
        <a class="button button-cancel" href="{copixurl dest="quiz|default|default"}">{i18n key="quiz.msg.stop" noEscape=1}</a>
        {if $ppo->nextQ === false}
            <a class="button button-continue" href="{copixurl dest="quiz|default|endQuestions" id=$ppo->question.id_quiz qId=$ppo->nextQ}">
                {i18n key="quiz.msg.endQuestion" noEscape=1}
            </a>
        {else}
            <a class="button button-continue" href="{copixurl dest="quiz|default|question" id=$ppo->question.id_quiz qId=$ppo->nextQ}">
                {i18n key="quiz.msg.nextQuestion" noEscape=1}
            </a>
        {/if}
    </div>
</div>
