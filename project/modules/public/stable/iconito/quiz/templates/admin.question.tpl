{literal}
<script type="text/javascript">
jQuery.noConflict();

jQuery(document).ready(function($){

    /*
     * FUNCTIONS LIB
     */
    function updateClick(){
        /*
         * delete item
         */
        $(".qf-delresp").each(function(){
            $(this).click(function(){
                $(this).parent(".qf-resp").remove();
                return false;
            });
         });
        }

        //hide tpl item
        $("#qf-tpl-respform").hide();
        
        //make tabs
        $("#qf-tabs").tabs();

        {/literal}
            {$ppo->tabsSelect}
        {literal}

        //create sortable items
        $("#qf-sortable").sortable({
            placeholder: 'ui-state-highlight'
        });

        $("#qf-sortable").disableSelection();

        //apply 'button style'
        $("#qf-addresp, .qf-submit, .button").button();

        /*
         * ADD NEW RESPONSE ITEM
         */
        $("#qf-addresp").click(function(){
           $("#qf-sortable .qf-resp:last").after($("#qf-tpl-respform ul").html());
          $("#qf-sortable .qf-resp:last .qf-content").focus();
           return false;
        });

        /*
         * DELETE ITEM
         */
        $(".qf-delresp").live('click', function(){
           $(this).parent(".qf-resp").remove();
           return false;
        });
         
        $("#qf-form-resp").submit(function(){
            $(this).hide();
            $("#qf-sortable li").each(function(){
                //get the values :
                var mainct = $(this).children(".qf-content").val();
                var order = $(this).index("#qf-sortable li");
                var correct = ($(this).children(".qf-correct").is(":checked")) ? 1 : 0;
                
                var finalValue = mainct+'###'+correct+'###'+order;
                $(this).children(".qf-content").val(finalValue);
                return true;
            });
        });
    });
</script>
{/literal}
<h3>{i18n key="quiz.admin.admin" noEscape=1}</h3>

<hr class="quiz-separator" />
{if !empty($ppo->success)}
    <p class="ui-state-highlight"><strong>{$ppo->success}</strong></p>
{/if}
{if !$ppo->new}
    <a href="{copixurl dest="quiz|admin|delAnsw"}" id="a-suppr" class="button">{i18n key="quiz.admin.delAnsw" noEscape=1}</a>
{/if}
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
        <form id="qf-form-answ" action="{$ppo->actionAnsw}" method="post" >
            <fieldset>
                <legend>{i18n key="quiz.msg.answ" noEscape=1}</legend>

                
                <label for="aw-name">{i18n key="quiz.form.title" noEscape=1}</label>

                {if isset($ppo->error.name)}<p class="ui-state-error" >{$ppo->error.name}</p>{/if}

                <input type="text" id="aw-name" name="aw-name" value="{$ppo->question.name}"/><br />


                <label for="aw-content">{i18n key="quiz.admin.enonce" noEscape=1}</label>
                <textarea id="aw-content" name="aw-content">{$ppo->question.content}</textarea>


                <!-- process data's, integrity check by server side sessions storage -->
                <input type="hidden" name="aw-id" value="{$ppo->id}" />


                <input type="submit" class="qf-submit"/>
            </fieldset>
        </form>
    </div>

    <div id="qf-tabs-resp" class="ui-tabs-panel ui-widget-content ui-corner-bottom" >
        <form id="qf-form-resp" action="{$ppo->actionResp}" method="post" >
            <fieldset>
                <legend>{i18n key="quiz.msg.resp" noEscape=1}</legend>

                {if isset($ppo->error.resp.content)}<p class="ui-state-error" >{$ppo->error.resp.content}</p>{/if}
                {if isset($ppo->error.resp.correct)}<p class="ui-state-error" >{$ppo->error.resp.correct}</p>{/if}

                <button id="qf-addresp">{i18n key="quiz.admin.addResp" noEscape=1}</button>
             
                <ul id="qf-sortable">   
                {if !empty($ppo->resp)}
                    <!-- RESPONSES ARRAY -->
                    {foreach from=$ppo->resp item=resp}
                    <li class="ui-state-default qf-resp">
                        <img src="{copixresource path="images/colorful/24x24/up.png"}" />
                        <img src="{copixresource path="images/colorful/24x24/down.png"}" />
                        <input type="text" class="qf-content" name="qf-content[]" value="{$resp.content}"/>
                        <input type="checkbox" class="qf-correct" name="qf-correct" {if $resp.correct == 1} checked="checked" {/if} />
                        <a href="#" class="qf-delresp">
                            <img class="qf-delresp-img" src="{copixresource path="images/colorful/24x24/trash.png"}" alt="{i18n key="quiz.admin.delResp" noEscape=1}" title="{i18n key="quiz.admin.delResp" noEscape=1}"/>
                        </a>
                    </li>
                    {/foreach}
                {/if}

                    
                {* If no responses : *}
                {if empty($ppo->resp)}
                    <p class="ui-state-highlight"><strong>{i18n key="quiz.errors.noChoice" noEscape=1}</strong></p>
                    <li class="ui-state-default qf-resp">
                        <img src="{copixresource path="images/colorful/24x24/up.png"}" />
                        <img src="{copixresource path="images/colorful/24x24/down.png"}" />
                        <input type="text" class="qf-content" name="qf-content[]" value=""/>
                        <input type="checkbox" class="qf-correct" name="qf-correct" />
                        <a href="#" class="qf-delresp">
                            <img class="qf-delresp-img" src="{copixresource path="images/colorful/24x24/trash.png"}" alt="{i18n key="quiz.admin.delResp" noEscape=1}" title="{i18n key="quiz.admin.delResp" noEscape=1}"/>
                        </a>
                    </li>
                {/if}
                </ul>
                
                <!-- process data's, integrity check by server side sessions storage -->
                <input type="hidden" name="aw-id" value="{$ppo->id}" />
                <input type="submit" class="qf-submit"/>
            </fieldset>
        </form>
    </div>
</div>
<div id="qf-tpl-respform">
    <ul>
        <li class="ui-state-default qf-resp">
            <img src="{copixresource path="images/colorful/24x24/up.png"}" />
            <img src="{copixresource path="images/colorful/24x24/down.png"}" />
            <input type="text" class="qf-content" name="qf-content[]" />
            <input type="checkbox" class="qf-correct" name="qf-correct" />
            <a href="#" class="qf-delresp">
                <img class="qf-delresp-img" src="{copixresource path="images/colorful/24x24/trash.png"}" alt="{i18n key="quiz.admin.delResp" noEscape=1}" title="{i18n key="quiz.admin.delResp" noEscape=1}"/>
            </a>
        </li>
    </ul>
</div>