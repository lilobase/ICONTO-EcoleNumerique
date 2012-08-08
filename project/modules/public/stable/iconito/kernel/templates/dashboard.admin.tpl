<h2>{i18n key="kernel|dashboard.admin.title" noEscape="true"}</h2>
<div id="dash-ct">

<form id="dash-form-ct" action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <div class="content-panel content-panel-edit">
        <h3>{i18n key="kernel|dashboard.admin.desc"}</h3>
        {$ppo->editor}
    </div>
    <div class="content-panel content-panel-edit">
        <h3>{i18n key="kernel|dashboard.admin.twitter"}</h3>
        <p>{i18n key="kernel|dashboard.admin.twitterDesc"}</p>
        <p>
            <label for="social_stream">{i18n key="kernel|dashboard.admin.twitterLabel"} : </label>
            <input type="text" name="social_stream" value="{$ppo->content.social_stream}"/>
            <em>( {i18n key="kernel|dashboard.admin.twitterExample"} )</em>
        </p>
    </div>

<div class="content-panel center">    
    <a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}" class="button button-reload" >{i18n key="kernel|dashboard.admin.default" noEscape="true"}</a>&nbsp;&nbsp;&nbsp;
    <a class="button button-cancel" href="{copixurl dest="||"}" class="button button-cancel" >{i18n key="kernel|dashboard.admin.cancel" noEscape="true"}</a>&nbsp;
    <input class="button button-confirm" type="submit" class="button button-confirm" value="{i18n key="kernel|dashboard.admin.save" noEscape="true"}" />
</div>
</form>
    