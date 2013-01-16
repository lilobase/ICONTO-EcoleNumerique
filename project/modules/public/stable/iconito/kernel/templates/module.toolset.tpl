</div><!-- END content -->
<div class="toolset hop">
    <ul>
        {foreach from=$ppo->modules item=module}
                {assign var=module_type value="_"|explode:$module->module_type}
                {assign var=module_type value=$module_type[1]|lower}
                {if isset($module->module_id)}
                    {assign var=module_id  value=$module->module_id}
                {else}
                    {assign var=module_id value=''}
                {/if}
                
                {if $module_type eq $ppo->curmod}
                    {assign var=highlight value=true}
                {else}
                    {assign var=highlight value=false}
                {/if}
                
                {if isset($module->module_popup)}
                    {assign var=target value="_blank"}
                {else}
                    {assign var=target value=''}
                {/if}
                
            <li {if $highlight}class="selected"{/if}>
                <a title="{$module->module_nom}" class="{$module->module_type}" href="{copixurl dest="kernel||go" ntype=$ppo->myNode.type nid=$ppo->myNode.id mtype=$module_type mid=$module_id}" target="{$target}">
                    <span class="label">{$module->module_nom}</span>
                    <span class="valign"></span>
                </a>
            </li>
        {/foreach}
    </ul>
<!-- END la suite dans module.footer.tpl -->
