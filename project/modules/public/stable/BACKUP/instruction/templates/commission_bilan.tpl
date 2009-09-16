

<form class="default-form" method="get" onSubmit="submitonce(this);">

<input type="hidden" name="id" value="{$ppo->rCommission->id}" />

<fieldset>
	<legend>Infos commission</legend>

	
	<div id="comm_infos">
	{copixzone process=instruction|commission_infos rCommission=$ppo->rCommission page=bilan}
	</div>
	
	
	
</fieldset>

</form>


<div class="default-form">
<fieldset>
	
	
	<p>La commission est termin&eacute;e, vous pouvez acc&eacute;der aux documents suivants.</p>
	
	
</fieldset>
</div>

















