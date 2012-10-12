{if $ppo->withEmpty}
  <option value="">&nbsp;</option>
{/if}
{foreach from=$ppo->classrooms item=classroom}
  <option value="{$classroom->id}">{$classroom|escape}</option>
{/foreach}
