<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Importer {customi18n key="gestionautonome|gestionautonome.message.%%indefinite__structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</h2>

<div class="help">
    <p>Vous disposez de deux méthodes pour ajouter {customi18n key="gestionautonome|gestionautonome.message.%%indefinite__structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc} :</p>
    <ul>
      <li>ajouter uniquement des <strong>{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</strong>. <em>Saisissez {customi18n key="gestionautonome|gestionautonome.message.%%indefinite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc} par ligne.</em></li>
      <li>ajouter des <strong>{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc} et leurs {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_responsables%%" catalog=$ppo->vocabularyCatalog->id_vc}</strong>. <em>Saisissez, sur la même ligne, les informations de {customi18n key="gestionautonome|gestionautonome.message.%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc} suivies de celles {customi18n key="gestionautonome|gestionautonome.message.%%indefinite__structure_element_responsables%%" catalog=$ppo->vocabularyCatalog->id_vc}.</em></li>
      <li>importer un <strong>fichier</strong> (<em>au format CSV</em>) contenant les informations.</li>
    </ul>
    
    <p>Dans tous les cas, les informations doivent être séparées par une virgule ou un point-virgule et être formatées selon le modèle suivant : </p>
    
    <pre><code>Nom, Prénom, Sexe (M ou F), <span class="optionnal">[ </span>date de naissance (JJ/MM/AAAA)<span class="optionnal"> ]</span>,
<span class="optionnal">[ </span>Nom parent1, Prénom parent1, Sexe (M ou F), relation (PERE, MERE, AUTRE),<span class="optionnal"> ]</span>
<span class="optionnal">[ </span>Nom parent2, Prénom parent2, Sexe (M ou F), relation (PERE, MERE, AUTRE)<span class="optionnal"> ]</span>
</code></pre>
    
    <p>Les informations entre <span class="optionnal">[ ]</span> sont optionnelles. Un champ optionnel peut être vide mais n'oubliez pas le séparateur (virgule ou point virgule).</p>
    
    <p>Voir des exemples de saisies : <a href="#" id="students-data">Quelques {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</a> - <a href="#" id="students-and-persons-data">Quelques {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc} et leurs {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_responsables%%" catalog=$ppo->vocabularyCatalog->id_vc}</a> 
    - <a href="{copixurl}import_exemple.csv">Télécharger un fichier d'exemple</a></p>
</div>

<div id="import-dialog">
    <h3>Sélectionnez un document</h3>
    {if not $ppo->errors eq null}
    <div class="mesgErrors">
      <ul>
        {foreach from=$ppo->errors item=error}
            <li>{$error}</li>
        {/foreach}
      </ul>
    </div>
    {/if}
    
    <form action="{copixurl dest="|addMultipleStudents" parentId=$ppo->nodeId parentType=$ppo->nodeType}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="node_id" value="{$ppo->nodeId}" />
    <input type="file" name="filename" />
    <div class="submit">
        <input type="submit" class="button button-upload" value="Envoyer" />
    </div>
    </form>
</div>

<form name="student_creation_data" id="student_creation_data" action="{copixurl dest="|validateMultipleStudentsAdd"}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    
    <div class="field">
        <label for="liste">{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc} à importer dans la classe {$ppo->nodeInfos.nom}</label>
        <textarea name="liste" id="liste" placeholder="Nom, Prénom, M, 01/01/2000, Nom parent1, Prénom parent1, M, PERE, Nom parent2, Prénom parent2, F, MERE">{$ppo->import}</textarea>
    </div>
  
    <div class="submit">
        <input class="button button-cancel" type="button" value="Annuler" id="cancel" />
        <input class="button button-next" type="submit" name="save" id="save" value="Continuer" />
    </div>
</form>



{literal}
<script type="text/javascript">
//<![CDATA[
  
$(document).ready(function(){

    jQuery("#import-dialog").dialog({modal: true, autoOpen: {/literal}{if ($ppo->errors)}true{else}false{/if}{literal}, title: "Sélectionnez un document", width: 400});
	jQuery('#import-dialog .submit input').before('<a href="#" class="button button-cancel">Annuler</a>');
	jQuery('#import-dialog .submit .button-cancel').click(function(){jQuery('#import-dialog').dialog('close'); return false;});
    
    jQuery("#liste").parent().parent().before("<a href=\"{/literal}{copixurl dest="|importStudentsList" nodeId=$ppo->nodeId}{literal}\" id=\"import-csv\" class=\"button button-upload\">Importer un fichier</a>");
    
    jQuery("#import-csv").click(function(event){
        event.stopPropagation();
        jQuery("#import-dialog").dialog("open");
        return false;
    });
    
    jQuery('#cancel').click(function() {
        document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
  
    jQuery('#students-data').click(function(){
        jQuery('#liste').val('BERANGER, Kevin, M\nGEORGES, Arthur, M\nGAGERE, Emma, F, 15/12/2000\nZEBULON, Jean-Luc, M');
        return false;
    });
  
    jQuery('#students-and-persons-data').click(function(){
        jQuery('#liste').val('BERANGER, Kevin, M, , BERANGER, Richard, M, PERE\nGEORGES, Arthur, M, , GEORGES, Sylvette, F, MERE, GEORGES, Antoine, M, PERE\nGAGERE, Emma, F, 15/12/2000, GAGERE, Rene, M, PERE, GAGERE, Sylvie, F, MERE\nZEBULON, Jean-Luc, M, , ZEBULON, William, M, PERE, ZEBULON, Natacha, F, MERE');
        return false;
    });
});
  
//]]> 
</script>
{/literal}
