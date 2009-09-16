
{copixzone process=instruction|dossier_menu rDemande=$ppo->rDemande tab="responsables"}

<div  class="default-form">
<fieldset>
	<legend>Responsables</legend>
	
	
		
	<form class="default-form" method="post" onSubmit="submitonce(this);">
	
	<input type="hidden" name="id" value="{$ppo->rDemande->id}" />
	<input type="hidden" name="submit" value="1" />
	

	{copixzone process=instruction|enfant_responsables enfant=$ppo->rDemande->enfant auth_parentale=1}
		
		
	<p></p>
	<input type="submit" class="formSubmit" value="Enregistrer" />
	</form>

	
</fieldset>

</div>

