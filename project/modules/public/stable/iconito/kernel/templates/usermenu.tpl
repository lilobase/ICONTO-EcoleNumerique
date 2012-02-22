{if $ppo->uLogged }
<ul class="usermenu">
    {foreach from=$ppo->menuitems key=k item=i}
    <li class="{ $i.class }">
        {if $i.before neq ""}{ $i.before }{/if}
        <a class="item { $i.class }" href="{ $i.url }" title="{ $i.title }"><span class="hidden">{ $i.title }</span></a>
    </li>
    {/foreach}
    
    <li class="{ $ppo->logout.class }">
        <a class="item { $ppo->logout.class }" href="{ $ppo->logout.url }" title="{ $ppo->logout.title }"><span class="hidden">{ $ppo->logout.title }</span></a>
    </li>
</ul>
{/if}