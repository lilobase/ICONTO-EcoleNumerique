{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierParent->id}

<h2>{i18n key="classeur.message.options"}</h2>

{if $ppo->conf_ModClasseur_upload}
<h3>{i18n key="classeur.message.editUpload"}</h3>

{if ! $ppo->classeur->upload_db|is_null}
<aside class="help">
    <h3>{i18n key="classeur.message.option.tbi.configwebdav_title"}</h3>
    <p>{i18n key="classeur.message.option.tbi.configwebdav_mesg"}</p>
    <p>{i18n key="classeur.message.option.tbi.configwebdav_params"}&nbsp;:</p>
       
       <p>
       <label for="server_name">{i18n key="classeur.message.option.tbi.configwebdav_server"}</label>
       <input name="server_name" id="server_name" readonly="readonly" value="{$ppo->classeur->upload_url|escape}"/>
       </p>
       <p>
            <label for="server_user">{i18n key="classeur.message.option.tbi.configwebdav_login"}</label>
            <input id="server_user" name="server_user" readonly="readonly" value="{$ppo->classeur->upload_fs|escape}"/>
       </p>
       <p>
            <label for="server_password">{i18n key="classeur.message.option.tbi.configwebdav_passwd"}</label>
            <input id="server_password" id="server_password" readonly="readonly" value="{$ppo->classeur->upload_pw|escape}"/>
       </p>
       
</aside>
{/if}

<div>
<p>{i18n key="classeur.message.option.tbi.configclasseur_mesg"}</p>

    <form id="form_upload_config" action="{copixurl dest="classeur|options|" classeurId=$ppo->classeur->id}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
        <div class="row">
            <p class="label">{i18n key="classeur.message.option.tbi.configclasseur_status"}</p>
            <div class="field">
                <input id="save-mode-disabled" type="radio" name="save-mode" value="upload-delete" {if $ppo->classeur->upload_db|is_null}checked="checked" {/if}/><label for="save-mode-disabled">{i18n key="classeur.message.upload-disabled"}</label>
                <input id="save-mode-enabled" type="radio" name="save-mode" value="upload" {if ! $ppo->classeur->upload_db|is_null}checked="checked" {/if}/><label for="save-mode-enabled">{i18n key="classeur.message.upload-enabled"}</label>
            </div>
        </div>
    <!--<p>Les documents seront déposés dans le dossier {if $ppo->classeur->folder_infos}"{$ppo->classeur->folder_infos->nom|escape}"{else}principal{/if} de ce classeur.</p>-->
    
	<div class="row" id="selectDestFolder">
        <p class="label">{i18n key="classeur.message.option.tbi.configclasseur_where"}&nbsp;:</p>
        <div class="field">
            <div class="selectFolder">
                <ul class="child">
                    <li class="folder"><p class="{if ! $ppo->classeur->upload_db}current{/if}"><input id="dossier-0" type="radio" value="dossier-0" name="destination"{if ! $ppo->classeur->upload_db} checked="checked"{/if}><label for="dossier-0">{i18n key="classeur.message.option.tbi.configclasseur_homefolder"}</label></p></li>
                    {copixzone process=classeur|selectionDossiers classeurId=$ppo->classeur->id targetType="dossier" targetId=$ppo->classeur->upload_db alwaysOpen=$ppo->classeur->upload_db}
                </ul>
            </div>
        </div>
    </div>
	
    <div class="submit">
<input class="button button-save" type="submit" name="save" value="{i18n key="classeur.message.save"}" />
</div>
</form>
</div>

{/if}
