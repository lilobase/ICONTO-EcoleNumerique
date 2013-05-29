{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classe.configuration.success"}</p>
{/if}

<form action="{copixurl dest="classe||configure"}" method="post">
    <div class="row">
        <label for="classe_configuration_minimail">
            <input id="classe_configuration_minimail" type="checkbox" name="minimail"{if $ppo->has_minimail_enabled} checked="checked"{/if} />
            {i18n key="classe.configuration.minimail"}
        </label>
    </div>

    <input type="submit" value="{i18n key="classe.configuration.sendForm"}" class="button button-save">
</form>
