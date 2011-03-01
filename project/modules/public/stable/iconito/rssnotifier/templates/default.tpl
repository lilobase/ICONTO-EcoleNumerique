<h3>RSS Notifier Read Only Interface</h3>

<h4> Rss Name : {$ppo->title} </h4>
<h5> Description : {$ppo->summary}</h5>
<h5> Source : <a href="{$ppo->source}">{$ppo->source}</a> </h5>
<h4>Items : </h4>
<ul>



    {foreach from=$ppo->items item=item}

        <li><strong>{$item->title}</strong><p>{$item->content}</p><em>{$item->link}</em>

    {/foreach}

</ul>