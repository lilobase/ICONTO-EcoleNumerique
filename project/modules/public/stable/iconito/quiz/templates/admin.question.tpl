{literal}
<script type="text/javascript">
jQuery.noConflict();

jQuery(document).ready(function($){
        $("#qf-tabs").tabs();
    });
</script>
{/literal}

<div id="qf-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
            <a href="#qf-tabs-answ">
                {i18n key="quiz.msg.answ" noEscape=1}
            </a>
        </li>
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
            <a href="#qf-tabs-resp">
                {i18n key="quiz.msg.resp" noEscape=1}
            </a>
        </li>
    </ul>

    <div id="qf-tabs-answ" class="ui-tabs-panel ui-widget-content ui-corner-bottom" >
        <form id="qf-form-answ" action="" method="" >
            <fieldset>
                <legend>{i18n key="quiz.msg.answ" noEscape=1}</legend>
                <label for="qf-q-name">{i18n key="quiz.form.title" noEscape=1}</label>
                <input type="text" id="qf-q-name" name="qf-q-name" value="{$ppo->question.name}"/>
                <label for="qf-q-content">{i18n key="quiz.admin.enonce" noEscape=1}</label>
                <textarea id="qf-q-content" name="qf-q-content">{$ppo->question.content}</textarea>
            </fieldset>
        </form>
    </div>

    <div id="qf-tabs-resp" class="ui-tabs-panel ui-widget-content ui-corner-bottom" >
        <form id="qf-form-resp" action="" method="" >
            <fieldset>
                <legend>{i18n key="quiz.msg.resp" noEscape=1}</legend>
            <ul>
                <li><input type="text" id="" name="" /><input type="checkbox" id="" name="" /></li>
                <li><input type="text" id="" name="" /><input type="checkbox" id="" name="" /></li>
            </ul>
            </fieldset>
        </form>
    </div>
</div>