{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h2>{i18n key="cahierdetextes.message.addMemo"}</h2>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<div class="message_errors">
	  <ul>
	    {foreach from=$ppo->erreurs item=erreur}
		    <li>{$erreur}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="memo_add" id="memo_add" action="{copixurl dest="cahierdetextes||editerMemo"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
    <input type="hidden" name="memoId" id="memoId" value="{$ppo->memo->id}" />
    <input type="hidden" name="jour" id="jour" value="{$ppo->jour}" />
    <input type="hidden" name="mois" id="mois" value="{$ppo->mois}" />
    <input type="hidden" name="annee" id="annee" value="{$ppo->annee}" />
        
    <div class="field">
      <label for="memo_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.date"} :</label>
      <input class="form datepicker" type="text" name="memo_date_creation" id="memo_date_creation" value="{if $ppo->memo->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->memo->date_creation}{/if}" />
    </div>
      
    <div class="field">
      <label for="memo_date_validite" class="form_libelle">{i18n key="cahierdetextes.message.validityDate"} :</label>
      <input class="form datepicker" type="text" name="memo_date_validite" id="memo_date_validite" value="{$ppo->memo->date_validite}" />
    </div>

    <div class="field">
      <label for="memo_message" class="form_libelle">{i18n key="cahierdetextes.message.memo"} :</label>
      {copixzone process=kernel|edition field='memo_message' format=$ppo->format content=$ppo->memo->message height=200}
    </div>
    
    <div class="field">
      <label for="memo_fichiers" class="form_libelle">{i18n key="cahierdetextes.message.relatedDocuments"} :</label>
    </div>
    
    <div class="field">
      <input type="checkbox" id="memo_avec_signature" name="memo_avec_signature" value="1" {if $ppo->memo->avec_signature}checked="checked"{/if}/> {i18n key="cahierdetextes.message.askParentsSignature"}
      <div class="field" {if !$ppo->memo->avec_signature} style="display: none"{/if} id="field-signature">
        {i18n key="cahierdetextes.message.toSignOn"} <input class="form datepicker" type="text" name="memo_date_max_signature" id="memo_date_max_signature" value="{$ppo->memo->date_max_signature}" />
      </div>
    </div>
  </fieldset>
  
  <fieldset>
    {copixzone process=cahierdetextes|listeEleves nid=$ppo->nid elevesSelectionnes=$ppo->elevesSelectionnes}
  </fieldset>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.save"}" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	  
 	  $('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/cahierdetextes/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
    $('#memo_avec_signature').change(function() {
      $('#memo_date_max_signature').val('');
      $('#field-signature').toggle();
    });
    
  });
//]]> 
</script>
{/literal}