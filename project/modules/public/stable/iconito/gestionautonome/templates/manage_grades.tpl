<h2>Gestion des années scolaires</h2>
<p class="right"><a href="{copixurl dest="gestionautonome||createGrade"}" class="button button-add">Créer une nouvelle année scolaire</a></p>
{if $ppo->grades neq null}
  <table>
    <tr>
      <th>Identifiant</th>
      <th>Année scolaire</th>
      <th>Date de début</th>
      <th>Date de fin</th>
      <th>Année scolaire actuelle ?</th>
      <th>Actions</th>
    </tr>
    {foreach from=$ppo->grades key=k item=grade}
      <tr class="{if $k%2 eq 0}even{else}odd{/if}">
        <td>{$grade->id_as}</td>
        <td>{$grade->annee_scolaire|escape}</td>
        <td>{$grade->dateDebut|datei18n}</td>
        <td>{$grade->dateFin|datei18n}</td>
        <td class="center">{if $grade->current eq '1'}<img src="{copixurl}themes/default/images/button-action/action_confirm.png" title="Oui" alt="Oui" />{/if}</td>
        <td class="actions">
          {if $grade->current neq 1}<a href="{copixurl dest="gestionautonome||setCurrentGrade" gradeId=$grade->id_as}"><img src="{copixurl}themes/default/images/button-action/action_read.png" title="Indiquer comme l'année courante" /></a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}
          <a href="{copixurl dest="gestionautonome||deleteGrade" gradeId=$grade->id_as}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette année scolaire ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cette année scolaire" /></a>
        </td>
      </tr>
    {/foreach}
  </table>
{/if}
