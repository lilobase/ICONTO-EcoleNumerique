<div class="content-panel" style="width: 500px; min-height: 90px;">
<h1>{i18n key="malle.web.add"}</h1>
<div class="explanation">{i18n key="malle.addWebTxt"}</div>

<form action="{copixurl dest="|doAddWeb"}" method="post" id="formAddWeb" onSubmit="return false;">
<input type="hidden" name="id" value="{$ppo->id}" />
<input type="hidden" name="folder" value="{$ppo->folder}" />

<p>
  <label class="web" for="nom">{i18n key="malle.web.form.nom" noEscape=1} <span class="asterisque">*</span></label>
  {inputtext name="nom" value=$ppo->rForm->nom maxlength="200" class="malle-web-form-nom"}
</p>

<p>
  <label class="web" for="url">{i18n key="malle.web.form.url" noEscape=1} <span class="asterisque">*</span></label>
  {inputtext name="url" value=$ppo->rForm->url maxlength="255" class="malle-web-form-url"}
</p>    

<input class="button button-confirm" id="buttonAddWeb" type="submit" value="{i18n key="malle.btn.submitAddFile"}" />
</form>
</div>