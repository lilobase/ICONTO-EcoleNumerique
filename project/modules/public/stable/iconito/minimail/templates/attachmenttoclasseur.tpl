{if $ppo->ok}
    <script>
        parent.location.reload();
    </script>
{else}


    {if $ppo->error}
        <div title="{i18n key=kernel|kernel.error.problem}">
        <ul class="mesgErrors">
        {foreach from=$ppo->error item=error}
                <li>{$error}</li>
        {/foreach}
        </ul>
        </div>
    {/if}


    {if $ppo->message->getNbAttachments() > 0}
    <p>
        <form action="{copixurl dest="|attachmentToClasseur" id=$ppo->message->id}" method="post" id="attachmentToClasseurForm">    
            <fieldset>
                <legend>{i18n key="minimail.attachmentToClasseur.action" pNb=$ppo->message->getNbAttachments()}</legend>


                <ul class="attachmentsToClasseur">
                    {if $ppo->message->attachment1}<li><input type="checkbox" name="files[]" value="{$ppo->message->attachment1|escape}" id="attachment1"><label for="attachment1"> {$ppo->message->getAttachmentFilename(1)}{if $ppo->message->isAttachmentImage(1)}<br/><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$ppo->message->attachment1}">{/if}</label></li>{/if}

                    {if $ppo->message->attachment2}<li><input type="checkbox" name="files[]" value="{$ppo->message->attachment2|escape}" id="attachment2"><label for="attachment2"> {$ppo->message->getAttachmentFilename(2)}{if $ppo->message->isAttachmentImage(2)}<br/><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$ppo->message->attachment2}">{/if}</label></li>{/if}

                    {if $ppo->message->attachment3}<li><input type="checkbox" name="files[]" value="{$ppo->message->attachment3|escape}" id="attachment3"><label for="attachment3"> {$ppo->message->getAttachmentFilename(3)}{if $ppo->message->isAttachmentImage(3)}<br/><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$ppo->message->attachment3}">{/if}</label></li>{/if}

                </ul>

                <div class="field selectFolder">{copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId targetType=$ppo->destinationType targetId=$ppo->destinationId}</div>


                <p>

                </p>

            </fieldset>



            <div id="popup_actions" class="content-panel center">

                <a href="{copixurl dest="|getMessage" id=$ppo->message->id}" class="button button-cancel fancyboxClose">{i18n key="kernel|kernel.btn.cancel"}</a>
                <input class="button button-confirm" type="submit" value="{i18n key="kernel|kernel.btn.save"}" />


            </div>

        </form>    
    </p>
    {/if}

{/if}

