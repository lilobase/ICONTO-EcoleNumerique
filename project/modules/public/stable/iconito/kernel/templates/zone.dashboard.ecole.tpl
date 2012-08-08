<h4 class="dashboard-zone-title">{customi18n key="kernel|dashboard.liste%%indefinite__structure_element_staff_persons%%de%%definite__structure%%" noEscape="true" catalog=$catalog}</h4>

<img class="dashboard-zone-img" src="{copixresource path="img/fichesecoles/no_photo.gif"}" alt="{i18n key="kernel|dashboard.imgAlt" noEscape="true"}" title="{i18n key="kernel|dashboard.imgAlt" noEscape="true"}" />

<ul class="dashboard-item">
    {foreach from=$ens item=en}
        <li>{$en.nom} {$en.prenom}</li>
    {/foreach}
</ul>

