<script type="text/javascript">
{literal} 

jQuery.noConflict();

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

{/literal} 
</script>
<div id="quiz-adm">
<h3>{i18n key="quiz.msg.hresults" noEscape=1} {$ppo->quiz->name}</h3>
<table id="quiz-adm-all">
	<thead>
		<tr>
			<th>
				{i18n key="quiz.msg.date" noEscape=1}
			</th>
			<th>
				{i18n key="quiz.msg.name" noEscape=1}
			</th>
			<th>
				{i18n key="quiz.msg.surname" noEscape=1}
			</th>
			<th>
				{i18n key="quiz.msg.class" noEscape=1}
			</th>
			<th>
				{i18n key="quiz.msg.school" noEscape=1}
			</th>
			<th>
				{i18n key="quiz.msg.results" noEscape=1}
			</th>
		</tr>
	</thead>
	<tbody>
		
		{foreach from=$ppo->users item=user}
		<tr>
			<td>
				&nbsp;{$user.date}
			</td>
			<td>
				{$user.name}
			</td>
			<td>
				{$user.surname}
			</td>
			<td>
			{if $user.classe != null}
				{$user.classe}
			{/if}
			</td>
			<td>
			{if $user.school != null}
				{$user.school}
			{/if}
			</td>
			<td>
			{foreach from=$user.responses item=response key=ii}
				<div class="quiz-adm-{$response} quiz-adm-responses"></div>
				<div class="quiz-adm-responses-hide">
				{i18n key="quiz.msg.response" noEscape=1} {$ii} : 
				{if $response == 'correct'}
					{i18n key="quiz.msg.true" noEscape=1}
				{elseif $response == 'resp'}
					{i18n key="quiz.msg.false" noEscape=1}
				{elseif $response == 'no-resp'}
					i18n key="quiz.msg.empty" noEscape=1}
				{/if}
				</div>
			{/foreach}
			</td>
		</tr>
		{/foreach}
		
	</tbody>
</table>	
</div>