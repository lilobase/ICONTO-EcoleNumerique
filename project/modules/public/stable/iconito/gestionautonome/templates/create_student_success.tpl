<h2>Ajout d'un élève</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|AccountsInfo}
</div>

<p class="ui-state-highlight ui-corner-all notice-light" style="margin-top: 20px; padding: 0pt 0.7em;">
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
  <strong>Elève et responsables ajoutés !</strong>
</p>

<h3>Elève ajouté</h3>

<div class="field">
  <label for="student_name"> Nom :</label>
  <span id="student_name"><strong>{$ppo->student->nom}</strong></span>
</div>

<div class="field">
  <label for="student_firstname"> Prénom :</label>
  <span id="student_firstname"><strong>{$ppo->student->prenom1}</strong></span>
</div>

<div class="field">
  <label for="student_login"> Login :</label>
  <span id="student_login"><strong>{$ppo->login}</strong></span>
</div>

<div class="field">
  <label for="student_password"> Mot de passe :</label>
  <span id="student_password"><strong>{$ppo->password}</strong></span>
</div>