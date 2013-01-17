{if $ppo->flashMessages}
    {$ppo->flashMessage}
{/if}


<div class="dashboard module_dash tools_right ink_blue font_dash">
    <div class="dashpanel {$ppo->panelClass}">
        <div class="title">
            {if $ppo->moduleIsForUser !== false}
                <div class="groupname">{$ppo->myNodeData.prenom} {$ppo->myNodeData.nom}</div>
            {else}
                <div class="groupname">{$ppo->myNodeData.nom}</div>
            {/if}
            <div class="wcontrol">
                {if $ppo->closeButton}
                    <a class="dashclose" href="{$ppo->closeUrl}"></a>
                {/if}
            </div>
            <span>{$ppo->title}</span>
        </div>
        <div class="content content-{$ppo->module}">
