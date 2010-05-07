{foreach from=$ppo->classrooms key=key item=classroom}
  <li>
    <a href="#" id="classroom-{$classroom->id}" class="node classroom"><span>{$classroom}</span></a>
  </li>
{/foreach}