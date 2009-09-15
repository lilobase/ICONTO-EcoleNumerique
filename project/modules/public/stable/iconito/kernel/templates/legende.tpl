
<p class="legende">
L&eacute;gende :<br/>

{foreach from=$ppo->tabActions item=item key=k}

{icon action=$item} {icon action=$item title=$ppo->tabTitles[$k] legende=1}<br/>

{/foreach}

</p>

