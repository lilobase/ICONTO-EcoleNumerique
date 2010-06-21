{literal}
<script type="text/javascript">
jQuery.noConflict();

jQuery(document).ready(function($){
        $("#qf-tabs").tabs();

        $("#qf-sortable").sortable({
			placeholder: 'ui-state-highlight',
		});
        $("#qf-sortable").disableSelection();

        $("#qf-addresp, .qf-submit").button();

        /*
         * ADD NEW RESPONSE ITEM
         */
        $("#qf-addresp").click(function(){
           $("#qf-sortable")
        });

        $("#qf-form-resp").submit(function(){
            //$(".qf-sortable").each(callback)
            return false;
        });
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
                <button id="qf-addresp">{i18n key="quiz.admin.addResp" noEscape=1}</button>
                {if !empty($ppo->resp)}
                <ul id="qf-sortable">
                    <!-- RESPONSES ARRAY -->
                    {foreach from=$ppo->resp item=resp}
                    <li class="ui-state-default qf-resp">
                        <img src="{copixresource path="images/colorful/24x24/up.png"}" />
                        <img src="{copixresource path="images/colorful/24x24/down.png"}" />
                        <input type="text" class="qf-respvalue" name="qf-respvalue[]" value="{$resp.content|utf8_encode}"/>
                        <input type="checkbox" name="" {if $resp.correct == 1} checked="checked" {/if} />
                        <a href="#" id="qf-delResp">
                            <img class="qf-delresp" src="{copixresource path="images/colorful/24x24/trash.png"}" alt="{i18n key="quiz.admin.delResp" noEscape=1}" title="{i18n key="quiz.admin.delResp" noEscape=1}"/>
                        </a>
                       <input class="qf-jq-order" type="hidden" name="order" value="" />
                    </li>
                    {/foreach}
                </ul>
                {/if}
                <input type="submit" class="qf-submit"/>
            </fieldset>
        </form>
    </div>
</div>