<ul class="domains-list">
{foreach from=$ppo->domains item=domain}
  <li>{$domain} (Modifier - <a href="{copixurl dest="cahier||deleteDomain" nid=$ppo->nid domain_id=$domain->id}" onclick="return confirm('Etes-vous sur de vouloir supprimer ce domaine ?')">Supprimer</a>)</li>
{/foreach}
</ul>