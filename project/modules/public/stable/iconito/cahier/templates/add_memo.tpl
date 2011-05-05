<h2>{i18n key="cahier.message.add_memo"}</h2>

{if $ppo->save neq null}
  <p class="success">Mémo créé</p>
{elseif not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="memo_add" id="memo_add" action="{copixurl dest="cahier||validateAddMemo"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
        
    <div class="field">
      <label for="memo_date_from" class="form_libelle">Date :</label>
      <input class="form datepicker" type="text" name="memo_date_from" id="memo_date_from" value="{if $ppo->memo->date_creation eq null}{$ppo->selectedDate|date_format:"%d/%m/%Y"}{else}{$ppo->memo->date_creation}{/if}" />
    </div>
      
    <div class="field">
      <label for="memo_date_to" class="form_libelle">Valable jusqu'au :</label>
      <input class="form datepicker" type="text" name="memo_date_to" id="memo_date_to" value="{$ppo->memo->date_validite}" />
    </div>

    <div class="field">
      <label for="memo_content" class="form_libelle">Memo :</label>
      {copixzone process=kernel|edition field='memo_content' format=$ppo->format content=$ppo->memo->message height=200}
    </div>
    
    <div class="field">
      <label for="memo_files" class="form_libelle">Documents associés :</label>
    </div>
    
    <div class="field">
      <input type="checkbox" id="with_signature" name="memo_with_signature" value="1" {if $ppo->memo->avec_signature}checked="checked"{/if}/> demander la signature des parents
      <div class="field" {if $ppo->memo->avec_signature eq null} style="display: none"{/if} id="field-signature">
        à signer pour le <input class="form datepicker" type="text" name="memo_date_signature" id="memo_date_signature" value="{$ppo->memo->date_max_signature}" />
      </div>
    </div>
  </fieldset>
  
  <fieldset>
    {copixzone process=cahier|showStudents selectedStudentIds=$ppo->selectedStudentIds}
  </fieldset>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	  
 	  jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/cahier/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
    jQuery('#with_signature').change(function() {
      jQuery('#memo_date_signature').val('');
      jQuery('#field-signature').toggle();
    });
    
  });
//]]> 
</script>
{/literal}