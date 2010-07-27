<ul id="stepbar">
	<li class="sb-step-first">
		<a href="{copixurl dest="quiz|admin|list"}" class="sb-list"></a>
	</li>
	<li class="sb-step-active">{i18n key="quiz.form.edit" noEscape=1}</li>
	<li class="sb-message">
		{if !empty($ppo->success)}
			{$ppo->success}
		{else}
			{if empty($ppo->quiz.name)}
				{i18n key="quiz.form.newmsg" noEscape=1}
			{else}
				{i18n key="quiz.form.editmsg" noEscape=1}
			{/if}
		{/if}		
	</li>
</ul>
<div id="stepbar-restore"></div>

{if !empty($ppo->errors) }
<div id="dialog-message" title="{i18n key="quiz.errors" noEscape=1}">
	<ul>
	{* $ppo->error|@print_r *} 
	{if isset($ppo->errors.title)}<li>{$ppo->errors.title}</li>{/if}
	</ul>
</div>
{/if}

<form id="quiz-form" class="quiz" method="post" action="{$ppo->action}">

	<input type="hidden" name="qf-optshow" value="never" />
	<input type="hidden" name="check" value="1" />
	<input type="hidden" name="quizId" value="{$ppo->quiz.id}" />

	<div class="col-right">
		<div class="content-panel">
			<label>{i18n key="quiz.form.publishState" noEscape=1}</label>
			<select name="qf-lock" class="qf-publish">
				<option value="0">{i18n key="quiz.form.published" noEscape=1}</option>
				<option value="1">{i18n key="quiz.form.unpublished" noEscape=1}</option>
			</select>

			<label>{i18n key="quiz.form.datestart" noEscape=1}</label>
			<input type="text" class="qf-date" name="qf-datestart" value="{if $ppo->quiz.date_start != 0}{$ppo->quiz.date_start}{/if}" />
 
			<label>{i18n key="quiz.form.dateend" noEscape=1}</label>
			<input type="text" class="qf-date" name="qf-dateend" value="{if $ppo->quiz.date_start != 0}{$ppo->quiz.date_end}{/if}" />
 		</div>
		<div class="content-panel content-panel-button">
		<input type="submit" value="{i18n key="quiz.form.submit" noEscape=1}" class="button button-save" />
		</div>
	</div>
	
	<div class="col-main">
		<div class="content-panel content-panel-edit qf-head">
			<input type="text" class="qf-title" name="qf-title" value="{$ppo->quiz.name}" />
			<textarea id="qf-description" class="qf-description" name="qf-description">{$ppo->quiz.description}</textarea>
		</div>
		<div class="content-panel">
			<label><a href="" id="qf-opt-show" >{i18n key="quiz.form.help" noEscape=1}</a></label>
			<div id="qf-opt-hide"><textarea id="qf-help" name="qf-help">{$ppo->quiz.help}</textarea></div>
		</div>
<!--
		<div class="content-panel">
			<span class="qf-label">{i18n key="quiz.form.optshow" noEscape=1}</span>
			<input type="radio" name="qf-optshow" value="never"><label for="qf-optshow">{i18n key="quiz.form.optshowNever" noEscape=1}</label>
			<input type="radio" name="qf-optshow" value="each"><label for="qf-optshow">{i18n key="quiz.form.optshowEach" noEscape=1}</label>
			<input type="radio" name="qf-optshow" value="endquiz"><label for="qf-optshow">{i18n key="quiz.form.optshowEndquiz" noEscape=1}</label>
		</div>
-->
		
	</div>

	<div class="clearBoth"><br/></div>

	{if !empty($ppo->quiz.name) || !empty($ppo->questions)}
	<div class="content-panel">
		<span class="quiz-itemlist">{i18n key="quiz.form.questions" noEscape=1}</span>
			<table class="quiz-qtable">
			{foreach from=$ppo->questions item=question }
			<tr class="{cycle values="row1, row2"}">
				<td class="quiz-col48 quiz-qnum">
					Q{$question.id}
					</td>
					<td class="">
						<a href="{copixurl dest="quiz|admin|questions" id=$question.id qaction="modif"}" class="button button-update">
							<div class="quiz-title">{$question.name}</div>
							<div class="quiz-description">{$question.content|truncate:150:'...'|strip_tags}</div>
						</a>
					</td>
					<td class="quiz-col120">
						ToDo {i18n key="quiz.question.answersCount" noEscape=1}
					</td>
					<td class="quiz-col80">
						<a href="{copixurl dest="quiz|admin|delAnsw"}" id="a-suppr" class="button button-delete">
						{i18n key="quiz.question.delete" noEscape=1}&nbsp;&nbsp;&nbsp;
						</a>
					</td>
				</tr>
			{/foreach}
			</table>
			<div class="right">
				<a href="{copixurl dest='quiz|admin|questions'}" class="button button-add">
				{i18n key="quiz.question.add" noEscape=1}
				</a>
			</div>
	</div>
	{/if}
</form>


{literal}
<script type="text/javascript">
	jQuery('#qf-opt-hide').hide();
	jQuery('#qf-opt-show').click(function(){
		jQuery('#qf-opt-hide').toggle();
		return false;
		});
</script>
{/literal}