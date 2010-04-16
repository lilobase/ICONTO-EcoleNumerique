<h2>Création d'une personne</h2>

<h3>Personne</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="personnel_creation" id="personnel_creation" action="{copixurl dest="|validatePersonnelCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    <input type="hidden" name="role" id="type-role" value="{$ppo->role}" />
    
    <label for="civilite"> Civilité :</label>
    <select class="form" name="civilite" id="civilite">
      {html_options values=$ppo->civilities output=$ppo->civilities selected=$ppo->personnel->civilite}
  	</select>
    
    <div class="field">
      <label for="nom"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->personnel->nom}" />
    </div>
    
    <div class="field" id="field_nomjf" {if $ppo->personnel->civilite neq 'Madame'}style="display: none"{/if}>
      <label for="nanom_jfe"> Nom de jeune fille :</label>
      <input class="form" type="text" name="nom_jf" id="nom_jf" value="{$ppo->personnel->nom_jf}" />
    </div>
    
    <div class="field">
      <label for="prenom1"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->personnel->prenom1}" />
    </div>
    
    <div class="field">
      <label for="date_nais"> Date de naissance :</label>
      <input class="form" type="text" name="date_nais" id="date_nais" value="{$ppo->personnel->date_nais}" />
    </div>
    
    <div class="field">
      <label for="cle_privee"> Clé privée :</label>
      <input class="form" type="text" name="cle_privee" id="cle_privee" value="{$ppo->personnel->cle_privee}" />
    </div>

    <div class="field">
      <label for="profession"> Profession :</label>
      <input class="form" type="text" name="profession" id="profession" value="{$ppo->personnel->profession}" />
    </div>
    
    <div class="field">
      <label for="tel_dom"> Téléphone fixe :</label>
      <input class="form" type="text" name="tel_dom" id="tel_dom" value="{$ppo->personnel->tel_dom}" />
    </div>
    
    <div class="field">
      <label for="tel_gsm"> Téléphone portable :</label>
      <input class="form" type="text" name="tel_gsm" id="tel_gsm" value="{$ppo->personnel->tel_gsm}" />
    </div>
    
    <div class="field">
      <label for="tel_pro"> Téléphone professionnel :</label>
      <input class="form" type="text" name="tel_pro" id="tel_pro" value="{$ppo->personnel->tel_pro}" />
    </div>
    
    <div class="field">
      <label for="tel_pro"> Numéro de poste :</label>
      <input class="form" type="text" name="num_poste" id="num_poste" value="{$ppo->personnel->num_poste}" />
    </div>
    
    <div class="field">
      <label for="mel"> Mail :</label>
      <input class="form" type="text" name="mel" id="mel" value="{$ppo->personnel->mel}" />
    </div>
    
    <div class="field">
      <label for="mel"> Mail pro :</label>
      <input class="form" type="text" name="mel_pro" id="mel_pro" value="{$ppo->personnel->mel_pro}" />
    </div>
    
    <div class="field">
      <label for="num_rue"> Numéro de rue :</label>
      <input class="form" type="text" name="num_rue" id="num_rue" value="{$ppo->personnel->num_rue}" />
    </div>
    
    <div class="field">
      <label for="adresse1"> Adresse 1 :</label>
      <input class="form" type="text" name="adresse1" id="adresse1" value="{$ppo->personnel->adresse1}" />
    </div>
    
    <div class="field">
      <label for="adresse2"> Adresse 2 :</label>
      <input class="form" type="text" name="adresse2" id="adresse2" value="{$ppo->personnel->adresse2}" />
    </div>
    
    <div class="field">
      <label for="code_postal"> Code postal :</label>
      <input class="form" type="text" name="code_postal" id="code_postal" value="{$ppo->personnel->code_postal}" />
    </div>
    
    <div class="field">
      <label for="commune"> Commune :</label>
      <input class="form" type="text" name="commune" id="commune" value="{$ppo->personnel->commune}" />
    </div>

    <div class="field">
      <label for="ville"> Ville :</label>
      <select class="form" name="ville" id="ville">
        {html_options values=$ppo->cityIds output=$ppo->cityNames selected=$ppo->personnel->id_ville}
  	  </select>
    </div>
    
    <div class="field">
      <label for="pays"> Pays :</label>
      <select class="form" name="pays" id="pays">
        {html_options values=$ppo->countryIds output=$ppo->countryNames selected=$ppo->personnel->pays}
  	  </select>
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="form_button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="form_button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
  
  $('#civilite').change(function() {
    
    if ($("option:selected", this).val() == 'Madame') {
      
      $('#field_nomjf').show();
    }
    else {
      
      $('#field_nomjf').hide();
    }
  });
//]]> 
</script>
{/literal}