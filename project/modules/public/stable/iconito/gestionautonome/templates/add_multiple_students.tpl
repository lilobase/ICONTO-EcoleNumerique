<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Ajout d'une liste d'élèves</h2>

<p>Vous disposez de deux méthodes pour ajouter une série d’élèves :</p>
<ul>
  <li>
    ajouter uniquement des <strong>élèves</strong>. Tapez une ligne par élève à ajouter, avec les informations suivantes séparées par une virgule, un point-virgule ou une tabulation: Nom, Prénom, Sexe (masculin: M; féminin: F), date de naissance optionnelle (JJ/ MM/AAAA)
  </li>
  <li>
  ajouter des <strong>élèves et leurs parents</strong>. Tapez, sur la même ligne, les informations de l’élève suivies de celles des parents: Nom élève, Prénom élève, Sexe élève (M ou F), date de naissance optionnelle (JJ/MM/AAAA), nom parent 1, prénom parent 1, sexe parent 1, relation parent 1 (PERE, MERE, AUTRE), nom parent 2, prénom parent 2, sexe parent 2, relation parent 2 (PERE, MERE, AUTRE). Tous les champs «parent 2» sont optionnels. Un champ optionnel peut être vide mais n’oubliez pas le séparateur (virgule, point virgule ou tabulation). 
  </li>
</ul>

<form name="student_creation_data" id="student_creation_data" action="{copixurl dest="|validateMultipleStudentsAdd"}" method="POST" enctype="multipart/form-data">
  <fieldset class="leftfield">
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    
    <div class="field">
      <label for="liste" style="vertical-align: top"> Liste</label>
      <textarea name="liste" id="liste" class="form" style="width:500px; height: 200px;"></textarea>
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Retour" id="cancel" /></li>
  	<li><input class="button" type="submit" name="save" id="save" value="Suite" /></li>
  </ul>
</form>

<div id="">
  Nous pouvons remplir le champ avec des exemples ci-dessous:
  <ul>
    <li><a href="#" id="students-data">Quelques élèves</a></li>
    <li><a href="#" id="students-and-persons-data">Quelques élèves et leurs parents</a></li>
  </ul>
  Si vous disposez de la liste des élèves dans un format tableur, vous pouvez réaliser un copier/coller des champs équivalents.
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	  
 	  jQuery('.button').button();
 	  
 	  jQuery("#liste").resizable({
    	
    	minHeight: 200,
      minWidth: 500,
      maxWidth: 650
    });
  
    jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
  
    jQuery('#students-data').click(function(){
      
      jQuery('#liste').val('BERANGER,Kevin,M\nGEORGES,Arthur,M\nGAGERE,Emma,F,15/12/2000\nZEBULON,Jean-Luc,F');
  
      return false;
    });
  
    jQuery('#students-and-persons-data').click(function(){
    
      jQuery('#liste').val('BERANGER,Kevin,M,,BERANGER,Richard,M,PERE\nGEORGES,Arthur,M,,GEORGES,Sylvette,F,MERE,GEORGES,Antoine,M,PERE\nGAGERE,Emma,F,15/12/2000,GAGERE,Rene,M,PERE,GAGERE,Sylvie,F,MERE\nZEBULON,Jean-Luc,F,,ZEBULON,William,M,PERE,ZEBULON,Natacha,F,MERE');
    
      return false;
    });
  });
  
//]]> 
</script>
{/literal}
