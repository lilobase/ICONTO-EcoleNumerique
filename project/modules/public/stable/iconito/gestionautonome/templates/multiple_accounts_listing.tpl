<h2>Ajout d'une liste d'élèves</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|MultipleAccountsInfo}
</div>

<p class="notice-light ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
  <strong>Elèves ajoutés !</strong>
</p>

<h4>Liste des élèves ajoutés</h4>

{foreach from=$ppo->students key=k item=student}

  {$student.firstname} {$student.lastname}
  {foreach from=$student.person key=j item=person}
    
    ({$person.firstname} {$person.lastname})
  {/foreach}
  ,
{/foreach}

<ul class="actions">
  <li><input class="button" type="button" value="Retour" id="back" /></li>
</ul>        

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  //jQuery('.button').button();
 	  
 	  jQuery('#back').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
  });
//]]> 
</script>
{/literal}