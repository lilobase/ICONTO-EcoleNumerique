
{assign var=item value=$ppo->file}
{assign var=can value=$ppo->can}

<td class="malle-table-icon">
  <img src="{copixresource path="img/malle/www.png"}" alt="{i18n key=malle.web.type}" title="{i18n key=malle.web.type}" />
</td>

{assign var=name value=$item->url}

{if $item->nom neq $item->fichier}
  {assign var=name value=$item->nom}
{/if}


<td class="malle-table-name">
<a class="item-link" href="{$item->fullUrl}" target="_blank">{$name}</a>

<div class="item-field" style="display: none;">
  {i18n key="malle.web.form.nom" noEscape=1 assign=title}
  {inputtext title="$title" name="newFilesWeb[`$item->id`]" value=$item->nom maxlength="200" style="width:400px;"}
  <br/>
  {i18n key="malle.web.form.url" noEscape=1 assign=title}
  {inputtext title="$title" name="newFilesWebUrl[`$item->id`]" value=$item->fullUrl maxlength="255" style="width:400px;"}
  <input type="submit" class="button button-confirm" value="" />
</div>
      
</td>

<td class="malle-table-edit">

{if $can.item_rename}<a class="item-rename"><img src="{copixresource path='images/action_update.png'}" alt="{i18n key='malle.btn.rename'}" title="{i18n key='malle.btn.rename'}" /></a>{/if}

</td>

<td class="malle-table-content">
  {i18n key=malle.web.type}
</td>
<td class="malle-table-size">
  
</td>








