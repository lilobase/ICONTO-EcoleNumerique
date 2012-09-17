<h4 class="dashboard-zone-title">{i18n key="kernel|dashboard.groupeDesc" noEscape="true"}</h4>

<p class="dashboard-club-desc">
    {$desc}
</p>
<p class="dashboard-club-nbusers">
    {i18n key="kernel|dashboard.nbUsers"} {$nbUsers}
</p>
{if !empty($tags)}
    <p>{$tags}</p>
{/if}