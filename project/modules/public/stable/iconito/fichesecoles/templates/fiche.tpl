{if $canModify}<p class="right"><a href="{copixurl dest="admin|form" id=$rEcole->numero}" class="button button-update">{i18n key="kernel|kernel.btn.modify"}</a></p>{/if}

<div id="fichesecoles">

    <table>
        <tr>
            <td class="pratique" rowspan="3">

                <div class="photo">{if $rFiche->photo}<img src="{copixurl dest="fichesecoles||photo" photo=$rFiche->photo}" alt="{$rFiche->photo|escape}" />{else}<img src="{copixresource path="img/fichesecoles/no_photo.gif"}" alt="{i18n key="fichesecoles.fields.nophoto"}" title="{i18n key="fichesecoles.fields.nophoto"}" />{/if}</div>

                {if $rEcole->hasAdresse() || $rEcole->tel}
                    <p></p>
                    <div class="fiche">{i18n key="fichesecoles.fields.adresse"}</div>
                    <div>
                        {if $rEcole->hasAdresse()}
                            {$rEcole->num_rue|escape} {$rEcole->num_seq|escape} {$rEcole->adresse1|escape} {if $rEcole->adresse2}<br/>{$rEcole->adresse2|escape}{/if}<br/>{$rEcole->code_postal|escape} {$rEcole->commune|escape}<br />
                        {/if}
                        {if $rEcole->tel}
                            <img width="11" height="9" src="{copixresource path="img/annuaire/icon_tel.gif"}" alt="{i18n key="annuaire|annuaire.telephone"}" title="{i18n key="annuaire|annuaire.telephone"}" border="0" hspace="1" /> {$rEcole->tel|escape}<br />
                        {/if}
                    </div>
                {/if}

                {if $rEcole->hasAdresse()}
                    <div class="fiche">{i18n key="fichesecoles.fields.plan"}</div>
                    <p>{adress_map address=$rEcole->getFullAddress() size='230x180'}</p>
                    <p><a class="button button-search" target="_blank" href="http://maps.google.fr/maps?q={$rEcole->getFullAddress()|urlencode}">{i18n key="fichesecoles.fields.viewplan"}</a></p>
                {/if}

                {if $rFiche->doc1_fichier}
                    <div class="fiche">{i18n key="fichesecoles.fields.doc"}</div>
                    <a class="fichier" href="{copixurl dest="fichesecoles||doc" fichier=$rFiche->doc1_fichier}">{if $rFiche->doc1_titre}{$rFiche->doc1_titre}{else}{$rFiche->getDocumentNom(1)}{/if}</a>
                {/if}

            </td>
            <td class="texte">
                {if $rFiche->zone_ville_titre && $rFiche->zone_ville_texte}
                <div class="ficheVille">
                    <div class="fiche">{$rFiche->zone_ville_titre|escape}</div>
                    <div>{$rFiche->zone_ville_texte}</div>
                </div>
                {/if}

                <div class="classes contentBox">
                    <img class="icon" alt="{customi18n key="fichesecoles|fichesecoles.fields.%%Structure_element_fiche%%" catalog=$id_vc}" title="{customi18n key="fichesecoles|fichesecoles.fields.%%Structure_element_fiche%%" catalog=$id_vc}" border="0" width="32" height="32" src="{copixresource path="img/fichesecoles/icon_classes.gif"}" />
                         <div class="fiche">{customi18n key="fichesecoles|fichesecoles.fields.%%Structure_element_fiche%%" catalog=$id_vc}</div>

                    <div class="ecole_classe_enseignant">

                        <table>
                            <tr>
                                <td>{i18n key="fichesecoles.fields.direction"}</td>
                                <td class="right">{foreach from=$rEcole->directeur item=directeur}
                                    {assign var=nom value=$directeur.prenom|cat:" "|cat:$directeur.nom}
                                    {if $canViewDir}
                                    {user label=$nom|escape userType=$directeur.type userId=$directeur.id login=$directeur.login dispMail=$canWriteDir escape=1}
                                    {else}
                                    {$nom|escape}
                                    {/if}
                                    {assign var=sep value=", "}
                                    {/foreach}
                                </td>
                            </tr>
                            {assign var=sep value=""}
                            {if $arClasses}
                            {foreach from=$arClasses item=class}
                            <tr>
                                <td class="left">
                                    {assign var=sep value=""}
                                    {foreach from=$class.niveaux item=niveau}
                                    {$sep}
                                    {$niveau->niveau_court}
                                    {assign var=sep value=" - "}
                                    {foreachelse}
                                    {$class.nom|escape}
                                    {/foreach}
                                </td>
                                <td class="right">
                                    {if $class.enseignant}
                                    <div class="ecole_classe_enseignant">
                                        {assign var=sep value=""}
                                        {foreach from=$class.enseignant item=enseignant}
                                        {$sep}
                                        {assign var=nom value=$enseignant.prenom|cat:" "|cat:$enseignant.nom}
                                        {if $canViewEns}
                                        {user label=$nom|escape userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=$canWriteEns escape=1}
                                        {else}
                                        {$nom|escape}
                                        {/if}
                                        {assign var=sep value=", "}
                                        {/foreach}
                                    </div>
                                    {/if}
                                    {*<b><a href="{copixurl dest="|getAnnuaireClasse" classe=$class.id}">{$class.nom|escape}</a></b>*}
                                </td>
                            </tr>
                            {/foreach}
                            {/if}
                        </table>
                    </div>
                </div>
            </td>
            <td class="horairesCol">
                {if $rFiche->horaires}
                <div class="horaires contentBox">
                    <img class="icon" alt="{i18n key="dao.fiches_ecoles.fields.horaires"}" title="{i18n key="dao.fiches_ecoles.fields.horaires"}" border="0" width="32" height="32" src="{copixresource path="img/fichesecoles/icon_horaires.gif"}" />
                         <div class="fiche">{i18n key="dao.fiches_ecoles.fields.horaires"}</div>
                    <div>{$rFiche->horaires}</div>
                </div>
                {/if}
            </td>
        </tr>
        {if $rEcole->blog || $arClassesBlogs}
        <tr>
            <td colspan="2" class="voirBlogs">

                <script type="text/javascript">{literal}jQuery(document).ready(function($){{/literal}ficheViewBlogs({$rEcole->numero},'');{literal}}{/literal});</script>
                <div id="ficheblogs" class="contentBox"></div>
            </td>
        </tr>
        {/if}
        <tr>
            <td class="texte" colspan="2">
                {if $rFiche->zone1_titre && $rFiche->zone1_texte}
                <div class="fiche">{$rFiche->zone1_titre|escape}</div>
                <div class="contentBox">{$rFiche->zone1_texte}</div>
                {/if}

                {if $rFiche->zone2_titre && $rFiche->zone2_texte}
                <div class="fiche">{$rFiche->zone2_titre|escape}</div>
                <div class="contentBox">{$rFiche->zone2_texte}</div>
                {/if}

                {if $rFiche->zone3_titre && $rFiche->zone3_texte}
                <div class="fiche">{$rFiche->zone3_titre|escape}</div>
                <div class="contentBox">{$rFiche->zone3_texte}</div>
                {/if}

                {if $rFiche->zone4_titre && $rFiche->zone4_texte}
                <div class="fiche">{$rFiche->zone4_titre|escape}</div>
                <div class="contentBox">{$rFiche->zone4_texte}</div>
                {/if}
            </td>
        </tr>
    </table>

</div>
