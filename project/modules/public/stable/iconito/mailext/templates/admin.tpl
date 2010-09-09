{literal}
<script type="text/javascript">
(function($){
    $(document).ready(function(){

        $("#me-form-new").hide();
        $("#show-me-form-new").click(function(){
            $("#me-form-new").show();
            $(this).parent(".content-panel").hide();
            return false;
        });

        $(".me-advanced").hide();
        $(".show-me-advanced").click(function(){
            $(this).next(".me-advanced").toggle();
            return false;
        });

        $("#comeback").click(function(){
                $("#me-form-new").hide();
                 $("#show-me-form-new").show();
            });

    });
})(jQuery)

</script>
{/literal}
<h2>{i18n key="mailext.title" noEscape=1}</h2>
<p class="content-info">{i18n key="mailext.description" noEscape=1}</p>
{foreach from=$ppo->mailConf item=mail}
{if isset($mail.error)}
<div id="dialog-message" title="{i18n key="mailext.error" noEscape=1}">
        {$mail.error}
</div>
{elseif isset($mail.valid)}
<div id="dialog-message" title="Connection">
        {if $mail.valid}
            {i18n key="mailext.successConnect" noEscape=1}
        {else}
            {i18n key="mailext.errorConnect" noEscape=1}
        {/if}
</div>
{/if}
<form action="{copixurl dest="mailext|mailext|update"}" method="post" id="me-form">
    <div class="content-panel">
        <label for="name">Nom du compte mail</label>
        <input type="text" name="name" class="me-name" value="{$mail.name}" /><br />
        <label for="login">Login</label>
        <input type="text" name="login" class="me-login" value="{$mail.login}" /><br />
        <label for="pass">Mot de passe</label>
        <input type="password" name="pass" class="me-pass" value="{$mail.pass}" /><br />
        <label for="webmail_url">Webmail (adresse web)</label>
        <input type="text" name="webmail_url" class="me-webmail_url" value="{$mail.webmail_url}" /><br />
    </div>
    <div class="content-panel">
        <label for="server">Protocol</label>
        <select name="protocol" class="me-protocol">
            <option {if $mail.protocol == 'pop3'}selected="selected" {/if}value="pop3">pop 3</option>
            <option {if $mail.protocol == 'imap'}selected="selected" {/if}value="imap">imap</option>
        </select><br />
        <label for="server">adresse du serveur</label>
        <input type="text" name="server" class="me-server" value="{$mail.server}" /><br />
        <label for="ssl">SSL</label>
        <input type="radio" name="ssl" value="1" {if $mail.ssl == '1'}checked="checked" {/if}/>oui
        <input type="radio" name="ssl" value="0" {if $mail.ssl == '0'}checked="checked" {/if}/>non
        <br />
        <br />
       
    </div>
    <div class="content-panel">
        <a href="" class="show-me-advanced button button-add">Configuration avanc&eacute;e</a>
        <div class="me-advanced">
        <label for="port">port</label>
            <input type="text" name="port" class="me-port" value="{$mail.port}"/><br />
            <label for="imap_path">Chemin de la boite de réception</label>
            <input type="text" name="imap_path" value="{$mail.imap_path}"/><br />
            <label for="tls">Activer le TLS</label>
            <input type="radio" name="tls" value="1" {if $mail.tls == '1'}checked="checked" {/if}/>oui
            <input type="radio" name="tls" value="0" {if $mail.tls == '0'}checked="checked" {/if}/>non<br />
        </div>
    </div>

    
    <input type="hidden" name="id" value="{$mail.id}" />

    <div class="content-panel">
        <input type="submit" value="enregistrer" class="button button-save m-right"/>
</form>
<a href="{copixurl dest="mailext|mailext|deleteMailConf" id=$mail.id}" class="button button-delete m-right">
       {i18n key="mailext.delete" noEscape=1}
</a>

<a href="{copixurl dest="||" id=$mail.id}" class="button button-cancel m-right">
       {i18n key="mailext.back" noEscape=1}
</a>
<div style="clear: both;"></div>
</div><br /><br />
{/foreach}
<div class="content-panel">
    <a id="show-me-form-new" href="" class="button button-add">{i18n key="mailext.newForm" noEscape=1}</a>
</div>

<form action="{copixurl dest="mailext|mailext|update"}" method="post" id="me-form-new">
    <div class="content-panel">
        <label for="name">Nom du compte mail</label>
        <input type="text" name="name" class="me-name" /><br />
        <label for="login">Login</label>
        <input type="text" name="login" class="me-login" /><br />
        <label for="pass">Mot de passe</label>
        <input type="password" name="pass" class="me-pass" /><br />
        <label for="webmail_url">Webmail (adresse web)</label>
        <input type="text" name="webmail_url" class="me-webmail_url" /><br />
    </div>
    <div class="content-panel">
        <label for="server">Protocol</label>
        <select name="protocol" class="me-protocol">
            <option>pop 3</option>
            <option>imap</option>
        </select><br />
        <label for="server">adresse du serveur</label>
        <input type="text" name="server" class="me-server" /><br />
        <label for="ssl">SSL</label>
        <input type="radio" name="ssl" value="1" />oui
        <input type="radio" name="ssl" value="0" />non
        <br />
        <br />

    </div>
    <div class="content-panel">
        <a href="" class="show-me-advanced button button-add">Configuration avanc&eacute;e</a>
        <div class="me-advanced">
        <label for="port">port</label>
            <input type="text" name="port" class="me-port"/><br />
            <label for="imap_path">Chemin de la boite de réception</label>
            <input type="text" name="imap_path" /><br />
            <label for="tls">Activer le TLS</label>
            <input type="radio" name="tls" value="1" />oui
            <input type="radio" name="tls" value="0" />non<br />
        </div>
    </div>
    <input type="hidden" name="id" value="new" />
       <div class="content-panel">
        <input type="submit" value="enregistrer" class="button button-save m-right"/>
</form>

<a href="" class="button button-cancel m-right" id="comeback">
       {i18n key="mailext.back" noEscape=1}
</a>
<div style="clear: both;"></div>
</div>