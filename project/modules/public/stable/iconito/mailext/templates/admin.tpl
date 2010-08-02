{literal}
<script type="text/javascript">
(function($){
    $(document).ready(function(){

        $("#me-form-new").hide();
        $("#show-me-form-new").click(function(){
            $("#me-form-new").show();
            $(this).hide();
            return false;
        });

        $(".me-advanced").hide();
        $(".show-me-advanced").click(function(){
            $(this).next(".me-advanced").toggle();
            return false;
        });

    });
})(jQuery)

</script>
{/literal}
<h3>{i18n key="mailext.title" noEscape=1}</h3>
<h4>{i18n key="mailext.description" noEscape=1}</h4>
<hr />
<a id="show-me-form-new" href="">{i18n key="mailext.newForm" noEscape=1}</a>

<form action="" id="me-form-new">
	<fieldset>
		<legend>Compte</legend>
		<label for="name">Nom du compte mail</label>
		<input type="text" name="name" class="me-name" />
		<label for="login">Login</label>
		<input type="text" name="login" class="me-login" />
		<label for="pass">Mot de passe</label>
		<input type="text" name="pass" class="me-pass" />
		<label for="webmail_url">Webmail (adresse web)</label>
		<input type="text" name="webmail_url" class="me-webmail_url" />
	</fieldset>
	<fieldset>
		<legend>Serveur</legend>
		<select name="protocol" class="me-protocol">
			<option value="pop3">pop 3</option>
			<option value="imap">imap</option>
		</select>
		<label for="server">adresse du serveur</label>
		<input type="text" name="server" class="me-server" />
		<label for="port">port</label>
		<input type="text" name="port" class="me-port" />
		<label for="ssl">SSL</label>
		<input type="radio" name="ssl" value="1"/>oui
		<input type="radio" name="ssl" value="0"/>non
		<hr />
                <a href="" class="show-me-advanced">Configuration avanc&eacute;e</a>
		<div class="me-advanced">
			<label for="imap_path">Chemin de la boite de réception</label>
			<input type="text" name="imap_path" />
		</div>
		
	</fieldset>
    <input type="submit" value="enregistrer" />
</form>

