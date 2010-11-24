{literal} 
<script type="text/javascript">
jQuery(document).ready(function($){
				/* You might need to set the sSwfPath! Something like:
				 *   TableToolsInit.sSwfPath = "{/literal}{$ppo->pathClip}{literal}ZeroClipboard.swf";
				 */
	$('#quiz-adm-all').dataTable( {
		"sDom": 'T<"clear">lfrtip'
	} );
	ZeroClipboard.setMoviePath("{/literal}{$ppo->pathClip}{literal}ZeroClipboard.swf");
	
	$('.quiz-adm-responses-hide').hide();
} );

</script>
{/literal} 
<div id="quiz-adm">
<h2>{i18n key="quiz.results.title" noEscape=1}</h2>

{$ppo->quiz.name|utf8_decode}

<div class="content-panel">
<table id="quiz-adm-all">
	<thead>
		<tr>
			<th>{i18n key="quiz.results.date" noEscape=1}</th>
			<th>{i18n key="quiz.results.name" noEscape=1}</th>
			<th>{i18n key="quiz.results.surname" noEscape=1}</th>
			<th>{i18n key="quiz.results.class" noEscape=1}</th>
			<th>{i18n key="quiz.results.school" noEscape=1}</th>
			<th>{i18n key="quiz.results.results" noEscape=1}</th>
			<th>{i18n key="quiz.results.results" noEscape=1}</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$ppo->users item=user}
		<tr>
			<td>{$user.date}</td>
			<td>{$user.name}</td>
			<td>{$user.surname}</td>
			<td>{if !empty($user.classe)}{$user.classe}{/if}</td>
			<td>{if !empty($user.school)}{$user.school}{/if}</td>
			<td>
				{foreach from=$user.responses item=response key=ii}
				<div class="quiz-adm-{$response} quiz-adm-responses"></div>
				{/foreach}
			</td>
			<td>{$user.goodresp}/{$ppo->nbQuestions}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
</div>

</div>
