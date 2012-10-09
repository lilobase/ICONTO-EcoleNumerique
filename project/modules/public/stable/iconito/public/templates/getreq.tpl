{literal}
<script type="text/javascript">

    jQuery(document).ready(function($){

    var baseUrl = '{/literal}{$ppo->baseUrl}{literal}';

        $(".f-classe").hide();

        $(".f-ecole").change(function(){
            $(this).next(".f-classe").html("").hide();
            if($(this).val() == 0 ) return;
            var current = $(this);
            $.getJSON(baseUrl+"?ecole_id="+ $(this).val(),function(listClasses){
                $.each(listClasses, function(key, value) {
                    current.next(".f-classe").append($("<option>"+value+"</option>").val(key));
                });
                current.next(".f-classe").show();
            });
            
        });

        $(".otherchild").hide();

        $("#add-child").click(function(){
            if($(".otherchild").size() == 1) $(this).parent(".content-panel").hide();
            var target = $(".otherchild:first");
            target.show();
            target.removeClass("otherchild");
            return false;
        });

    });

</script>
{/literal}

<form action="{$ppo->actionUrl}" method="post">

    <h2>{i18n key="public|public.getreq.title" noEscape=1}</h2>
    <div class="content-info">
    <p>Vous souhaitez avoir un acc&egrave;s au Portail des &eacute;coles ? Il vous suffit de remplir le formulaire ci-dessous avec vos informations et celles de votre(vos) enfant(s).</p>
    </div>
    <h3 class="content-panel">{i18n key="public.getreq.parent" noEscape=1}</h3>
    <div class="content-panel" class="getreqparent">

        <label for="parentnom">{i18n key="public.getreq.nom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentnom" id="parentnom" {if !empty($ppo->content.parentnom)}value="{$ppo->content.parentnom}"{/if} />
        {if !empty($ppo->errors.parentnom)}<span class="error">{$ppo->errors.parentnom}</span>{/if}<br />

        <label for="parentprenom">{i18n key="public.getreq.prenom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentprenom" id="parentprenom" {if !empty($ppo->content.parentprenom)}value="{$ppo->content.parentprenom}"{/if} />
        {if !empty($ppo->errors.parentprenom)}<span class="error">{$ppo->errors.parentprenom}</span>{/if}<br />

        <label for="parentadresse">{i18n key="public.getreq.adress" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentadresse" id="parentadresse" {if !empty($ppo->content.parentadresse)}value="{$ppo->content.parentadresse}"{/if} />
        {if !empty($ppo->errors.parentadresse)}<span class="error">{$ppo->errors.parentadresse}</span>{/if}<br />

        <label for="parentpostal">{i18n key="public.getreq.postal" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentpostal" id="parentpostal" {if !empty($ppo->content.parentpostal)}value="{$ppo->content.parentpostal}"{/if}/>
        {if !empty($ppo->errors.parentpostal)}<span class="error">{$ppo->errors.parentpostal}</span>{/if}<br />

        <label for="parentcity">{i18n key="public.getreq.ville" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentcity" id="parentcity" {if !empty($ppo->content.parentcity)}value="{$ppo->content.parentcity}"{/if} />
        {if !empty($ppo->errors.parentcity)}<span class="error">{$ppo->errors.parentcity}</span>{/if}<br />

        <label for="parentteldom">{i18n key="public.getreq.teldom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentteldom" id="parentteldom" {if !empty($ppo->content.parentteldom)}value="{$ppo->content.parentteldom}"{/if} />
        {if !empty($ppo->errors.parentteldom)}<span class="error">{$ppo->errors.parentteldom}</span>{/if}<br />

        <label for="parenttelpro">{i18n key="public.getreq.telpro" noEscape=1}</label>
        <input type="text" name="parenttelpro" id="parenttelpro" {if !empty($ppo->content.parenttelpro)}value="{$ppo->content.parenttelpro}"{/if} /><br />

        <label for="parentmail">{i18n key="public.getreq.mail" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="parentmail" id="parentmail" {if !empty($ppo->content.parentmail)}value="{$ppo->content.parentmail}"{/if} />
        {if !empty($ppo->errors.parentmail)}<span class="error">{$ppo->errors.parentmail}</span>{/if}<br />
    </div>

    <h3 class="content-panel">{i18n key="public.getreq.enfant" noEscape=1}</h3>
    <div class="content-panel">

        <label for="child1nom">{i18n key="public.getreq.nom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child1nom" id="child1nom" />
        {if !empty($ppo->errors.child1nom)}<span class="error">{$ppo->errors.child1nom}</span>{/if}<br />

        <label for="child1prenom">{i18n key="public.getreq.prenom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child1prenom" id="child1prenom" />
        {if !empty($ppo->errors.child1prenom)}<span class="error">{$ppo->errors.child1prenom}</span>{/if}<br />

        <select name="child1ecole" id="child1ecole" class="f-ecole">
            <option value="0">{i18n key="public|public.getreq.school" noEscape=1}</option>
            {foreach item=ecole from=$ppo->ecoles}
                <option value="{$ecole.numero}">{$ecole.nom|utf8_encode} ({$ecole.type|utf8_encode})</option>
            {/foreach}
        </select>
       

        <select name="child1classe" id="child1classe" class="f-classe">

        </select> {if !empty($ppo->errors.child1ecole) or !empty($ppo->errors.child1classe)}<span class="error">{i18n key="public|public.getreq.required" noEscape=1}</span>{/if}<br />
        
    </div>
    <div class="content-panel otherchild">

        <label for="child2nom">{i18n key="public.getreq.nom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child2nom" id="child2nom" />
        {if !empty($ppo->errors.child2nom)}<span class="error">{$ppo->errors.child2nom}</span>{/if}<br />

        <label for="child2prenom">{i18n key="public.getreq.prenom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child2prenom" id="child2prenom" />
        {if !empty($ppo->errors.child2prenom)}<span class="error">{$ppo->errors.child2prenom}</span>{/if}<br />

        <select name="child2ecole" id="child2ecole" class="f-ecole">
            <option value="0">{i18n key="public|public.getreq.school" noEscape=1}</option>
            {foreach item=ecole from=$ppo->ecoles}
                <option value="{$ecole.numero}">{$ecole.nom|utf8_encode}</option>
            {/foreach}
        </select>


        <select name="child2classe" id="child2classe" class="f-classe">

        </select> {if !empty($ppo->errors.child2ecole) or !empty($ppo->errors.child2classe)}<span class="error">{i18n key="public|public.getreq.required" noEscape=1}</span>{/if}<br />

    </div>
    <div class="content-panel otherchild">

        <label for="child3nom">{i18n key="public.getreq.nom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child3nom" id="child3nom" />
        {if !empty($ppo->errors.child3nom)}<span class="error">{$ppo->errors.child3nom}</span>{/if}<br />

        <label for="child3prenom">{i18n key="public.getreq.prenom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child3prenom" id="child3prenom" />
        {if !empty($ppo->errors.child3prenom)}<span class="error">{$ppo->errors.child3prenom}</span>{/if}<br />

        <select name="child3ecole" id="child3ecole" class="f-ecole">
            <option value="0">{i18n key="public|public.getreq.school" noEscape=1}</option>
            {foreach item=ecole from=$ppo->ecoles}
                <option value="{$ecole.numero}">{$ecole.nom|utf8_encode}</option>
            {/foreach}
        </select>


        <select name="child3classe" id="child3classe" class="f-classe">

        </select> {if !empty($ppo->errors.child3ecole) or !empty($ppo->errors.child3classe)}<span class="error">{i18n key="public|public.getreq.required" noEscape=1}</span>{/if}<br />

    </div>
    <div class="content-panel otherchild">

        <label for="child4nom">{i18n key="public.getreq.nom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child4nom" id="child4nom" />
        {if !empty($ppo->errors.child4nom)}<span class="error">{$ppo->errors.child4nom}</span>{/if}<br />

        <label for="child4prenom">{i18n key="public.getreq.prenom" noEscape=1}<sup class="f-required">*</sup></label>
        <input type="text" name="child4prenom" id="child4prenom" />
        {if !empty($ppo->errors.child4prenom)}<span class="error">{$ppo->errors.child4prenom}</span>{/if}<br />

        <select name="child4ecole" id="child4ecole" class="f-ecole">
            <option value="0">{i18n key="public|public.getreq.school" noEscape=1}</option>
            {foreach item=ecole from=$ppo->ecoles}
                <option value="{$ecole.numero}">{$ecole.nom|utf8_encode}</option>
            {/foreach}
        </select>


        <select name="child4classe" id="child4classe" class="f-classe">

        </select> {if !empty($ppo->errors.child4ecole) or !empty($ppo->errors.child4classe)}<span class="error">{i18n key="public|public.getreq.required" noEscape=1}</span>{/if}<br />

    </div>
    <div class="content-panel">
        <a href="#" id="add-child" class="button button-add">{i18n key="public.getreq.addChild" noEscape=1}</a>
    </div>
    <div class="content-panel">
        <input type="submit" class="button button-confirm" />
    </div>
</form>