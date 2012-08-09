<h2>{i18n key="kernel|dashboard.admin.title" noEscape="true"}</h2>
<div id="dash-ct">

<form id="dash-form-ct" action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
        <p>{i18n key="kernel|dashboard.admin.desc"}</p>
        {$ppo->editor}
    <p></p>
        <h2>{i18n key="kernel|dashboard.admin.twitter"}</h2>
        <p>{i18n key="kernel|dashboard.admin.twitterDesc"}</p>
        <div class="row">
            <label for="social_stream">{i18n key="kernel|dashboard.admin.twitterLabel"} </label>
            <input type="text" name="social_stream" value="{$ppo->content.social_stream}"/>
            <em>({i18n key="kernel|dashboard.admin.twitterExample"})</em>
        </div>
    

<div class="submit center">    
    <a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}" class="button button-reload" >{i18n key="kernel|dashboard.admin.default" noEscape="true"}</a>&nbsp;&nbsp;&nbsp;
    <a class="button button-cancel" href="{copixurl dest="||"}" class="button button-cancel" >{i18n key="kernel|dashboard.admin.cancel" noEscape="true"}</a>&nbsp;
    <input class="button button-confirm" type="submit" class="button button-update" value="{i18n key="kernel|dashboard.admin.save" noEscape="true"}" />
</div>
</form>
</div>    