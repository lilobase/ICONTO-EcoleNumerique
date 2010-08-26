{if $ppo->ok}
  <p>{i18n key='contact|contact.oks.send' noEscape=1}</p>
{else}

  {if $ppo->errors}
    <div id="dialog-message" title="{$ppo->TITLE_PAGE}">
    	<ul>
    	{foreach from=$ppo->errors item=error}
    		<li>{$error}</li><br/>
    	{/foreach}
    	</ul>
    </div>
  {/if}
  
  <form id="formMessage" method="post">
  <input type="hidden" name="submit" value="1" />
  
  <h2>{i18n key="contact|contact.form.message.group.identite" noEscape=1}</h2>
  
    <p>
      <label class="default" for="from_nom">{i18n key="contact|dao.contacts_messages.fields.from_nom" noEscape=1} <span class="asterisque">*</span></label>
      {inputtext name="from_nom" value=$ppo->rForm->from_nom maxlength="150"}
    </p>
  
    <p>
      <label class="default" for="from_email">{i18n key="contact|dao.contacts_messages.fields.from_email" noEscape=1} <span class="asterisque">*</span></label>
      {inputtext name="from_email" value=$ppo->rForm->from_email maxlength="150"}
    </p>
  
  <h2>{i18n key="contact|contact.form.message.group.message" noEscape=1} <span class="asterisque">*</span></h2>
  
  <table>
    <tr>
      <td class="types">
        {foreach from=$ppo->types item=type}
          <input type="radio" name="type" id="type{$type->id}" value="{$type->id}"{if $ppo->rForm->type eq $type->id} checked="checked"{/if} /><label for="type{$type->id}">{$type->nom|escape}</label>
          <br/>
        {/foreach}
        
      </td>
      <td>
        {textarea name="message" value=$ppo->rForm->message}
      </td>
    
    </tr>
  
  </table>
  
  <p class="asterique">
    {i18n key="contact|contact.form.message.asterisque" noEscape=1}
  </p>
  
  <p>
    <input type="submit" value="{i18n key="contact|contact.form.message.submit" noEscape=1}" class="button button-save" />
  
  </p>
  </form>


{/if}
