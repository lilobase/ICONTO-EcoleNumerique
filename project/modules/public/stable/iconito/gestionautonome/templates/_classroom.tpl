{foreach from=$ppo->classrooms key=key item=classroom}
  <li>
    <a href="#" id="classroom-{$classroom->id}" class="node after-expand"><span>{$classroom}</span></a>
  </li>
{/foreach}