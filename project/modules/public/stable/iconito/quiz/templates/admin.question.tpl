{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
                // SORTABLE ANSWERS
        $("#qf-answers").sortable();
        $("#qf-answers").disableSelection();

        $(".button-drag").hover(function(){
                $(this).parent(".qf-answer").addClass('drag-on');
        }, function(){
                $(this).parent(".qf-answer").removeClass('drag-on');
        });

                // ADD NEW ANSWER ITEM
        $("#qf-addanswer").click(function(){
                        $("#qf-answers .qf-answer:last").after($("#qf-answer-tpl").html());
                        $("#qf-answers .qf-answer:last .qf-content").focus();
                        return false;
        });

                // DELETE ANSWER ITEM
        $(".qf-delanswer").live('click', function(){
           $(this).parent(".qf-answer").remove();
           return false;
        });
         
                // SUBMIT FORM
        $("#qf-form-answers").submit(function(){
            $(this).hide();
            $("#qf-answers li").each(function(){
                //get the values :
                var mainct = $(this).children(".qf-content").val();
                var order = $(this).index("#qf-answers li");
                var correct = ($(this).children(".qf-correct").is(":checked")) ? 1 : 0;
                var finalValue = mainct+'###'+correct+'###'+order;
                $(this).children(".qf-content").val(finalValue);
                return true;
            });
        });
    });
</script>
{/literal}

<ul id="stepbar">
    <li class="sb-step-first">
        <a href="{copixurl dest="quiz|admin|list"}" class="sb-list"></a>
    </li>
    <li class="sb-step"><a href="{copixurl dest="quiz|admin|modif" id=$ppo->quiz.id qaction="modif"}">{i18n key="quiz.form.edit" noEscape=1}</a></li>
    <li class="sb-step-active">{i18n key="quiz.question.edit" noEscape=1}</li>
    <li class="sb-message">
		{if !empty($ppo->success)}
			{$ppo->success}
		{else}
			{if empty($ppo->question.name)}
				{i18n key="quiz.question.newmsg" noEscape=1}
			{else}
				{i18n key="quiz.question.editmsg" noEscape=1}
			{/if}
		{/if}		
    </li>
</ul>
<div id="stepbar-restore"></div>

{if !empty($ppo->error) }
<div id="dialog-message" title="{i18n key="quiz.errors" noEscape=1}">
    <ul>
	{* $ppo->error|@print_r *} 
	{if isset($ppo->error.name)}<li>{$ppo->error.name}</li>{/if}
	{if isset($ppo->error.answer_detail)}<li>{$ppo->error.answer_detail}</li>{/if}
	{if isset($ppo->error.resp.content)}<li>{$ppo->error.resp.content}</li>{/if}
	{if isset($ppo->error.resp.correct)}<li>{$ppo->error.resp.correct}</li>{/if}
    </ul>
</div>
{/if}
<div class="content-panel content-info">
    <a href="{copixurl dest="quiz|admin|modif" id=$ppo->quiz.id qaction="modif"}" class="button-reload">
        <strong>{i18n key="quiz.admin.goBackToQuiz" noEscape=1} </strong>
    </a>({i18n key="quiz.form.editmsg" noEscape=1})
</div>
<form id="qf-form-question" class="quiz" action="{$ppo->actionAnsw}" method="post" >
    <div class="content-panel content-panel-edit">
        <span class="quiz-itemlist">{i18n key="quiz.question.question" noEscape=1}</span><br/>
        <label for="aw-name">{i18n key="quiz.question.title" noEscape=1}</label>
        <input type="text" class="qf-title" id="aw-name" name="aw-name" value="{$ppo->question.name}"/><br />
        <label for="aw-content">{i18n key="quiz.question.detail" noEscape=1}</label>
        <textarea class="qf-description" id="aw-content" name="aw-content">{$ppo->question.content}</textarea>
        {$ppo->addPicPopup}
        {if $ppo->quiz.opt_show_results == 'each'}
            <label for="aw-content">{i18n key="quiz.question.answerDetail" noEscape=1}</label>
            <textarea class="qf-description" id="answer-detail" name="answer-detail">{$ppo->question.answer_detail}</textarea>
        {/if}
        <!-- process data's, integrity check by server side sessions storage -->
        <input type="hidden" name="aw-id" value="{$ppo->id}" />
    </div>
    <div class="content-panel right">
        <input type="submit" class="button button-save" value="{i18n key="quiz.question.save" noEscape=1}"/>
    </div>
</form>

{if !empty($ppo->question.name)}

<div id="qf-answer-tpl">
    <li class="qf-answer">
        <a class="button-drag"></a>
        <input type="checkbox" class="qf-correct" name="qf-correct" />
        <input type="text" class="qf-content" name="qf-content[]" value=""/>
        <button class="qf-delanswer button button-delete">{i18n key="quiz.question.answersDelete" noEscape=1}</button>
    </li>
</div>

<form id="qf-form-answers" class="quiz" action="{$ppo->actionResp}" method="post" >
    <!-- process data's, integrity check by server side sessions storage -->
    <input type="hidden" name="aw-id" value="{$ppo->id}" />
    <div class="content-panel content-panel-edit">
        <span class="quiz-itemlist">{i18n key="quiz.question.answers" noEscape=1}</span>

        <div>
		{i18n key="quiz.question.answersSort" noEscape=1}<br/>
		{i18n key="quiz.question.answersChoose" noEscape=1}<br/><br/>
        </div>

        <ul id="qf-answers">

		{* Answers defined *}
		{if !empty($ppo->resp)}
            <!-- RESPONSES ARRAY -->
			{foreach from=$ppo->resp item=resp}
            <li class="qf-answer">
                <a class="button-drag"></a>
                <input type="checkbox" class="qf-correct" name="qf-correct" {if $resp.correct == 1} checked="checked" {/if} />
                <input type="text" class="qf-content" name="qf-content[]" value="{$resp.content}"/>
                <button class="qf-delanswer button button-delete">{i18n key="quiz.question.answersDelete" noEscape=1}</button>
            </li>
			{/foreach}
		{else}
		{* No Answer defined *}	
            <li class="qf-answer">
                <a class="button-drag"></a>
                <input type="checkbox" class="qf-correct" name="qf-correct" />
                <input type="text" class="qf-content" name="qf-content[]" value=""/>
                <button class="qf-delanswer button button-delete">{i18n key="quiz.question.answersDelete" noEscape=1}</button>
            </li>
		{/if}

        </ul>
        <div class="left">
            <button id="qf-addanswer" class="button button-add">{i18n key="quiz.question.answersAdd" noEscape=1}</button>
        </div>
    </div>
    <div class="content-panel right">
        <input type="submit" class="qf-submit button button-save" value="{i18n key="quiz.question.answersSave" noEscape=1}"/>
    </div>
</form>
{/if}