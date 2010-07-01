{if empty($ppo->quiz.name)}
<h3>{i18n key="quiz.msg.newQuiz" noEscape=1}</h3>
{else}
<h3>{$ppo->quiz.name}</h3>
{if !empty($ppo->success)}
    <p class="ui-state-highlight"><strong>{$ppo->success}</strong></p>
{/if}
<a href="{copixurl dest="quiz|admin|delQuiz"}" id="q-suppr" class="button">{i18n key="quiz.admin.delQuiz" noEscape=1}</a>
<a href="{copixurl dest="quiz|quiz" id=$ppo->quiz.id}" id="q-goquiz" class="button">{i18n key="quiz.admin.goQuiz" noEscape=1}</a>
<a href="#goansw" id="q-goansw" class="button">{i18n key="quiz.admin.goAnsw" noEscape=1}</a>
{/if}

<hr class="quiz-separator" />
<form id="quiz-form" method="post" action="{$ppo->action}">

    <fieldset id="qf-main">

        <legend>{i18n key="quiz.form.infos" noEscape=1}</legend>

        <label for="qf-title">{i18n key="quiz.form.title" noEscape=1}</label>
        {$ppo->errors.title}
            <input type="text" name="qf-title" value="{$ppo->quiz.name}" />
        <br /><br />

        <label for="qf-description">{i18n key="quiz.form.desc" noEscape=1}</label>
            <textarea id="qf-description" name="qf-description">{$ppo->quiz.description}</textarea>
        <br />

        <label for="qf-help">{i18n key="quiz.form.help" noEscape=1}</label>
            <textarea id="qf-help" name="qf-help">{$ppo->quiz.help}</textarea>
        <br />
     <input type="submit" value="{i18n key="quiz.form.submit" noEscape=1}" class="button" />

    </fieldset>

    <fieldset id="qf-opt">
        <legend>{i18n key="quiz.form.options" noEscape=1}</legend>

       <label for="qf-lock">{i18n key="quiz.form.state" noEscape=1}</label>
            <select name="qf-lock">
                <option value="0">Activer le formulaire</option>
                <option value="1">Verrouiller le formulaire</option>
            </select>
        <br />

        <label for="qf-datestart">{i18n key="quiz.form.datestart" noEscape=1}</label>
            <input type="text" class="qf-date" name="qf-datestart" value="{$ppo->quiz.date_start}" />
        <br />

        <label for="qf-dateend">{i18n key="quiz.form.dateend" noEscape=1}</label>
            <input type="text" class="qf-date" name="qf-dateend" value="{$ppo->quiz.date_end}" />
       <br />
       
       <!-- <label for="qf-optshow">{i18n key="quiz.form.optshow" noEscape=1}</label>
            <select name="qf-optshow" value="{$ppo->quiz.opt_show_results}">
                <option value="never">jamais</option>
                <option value="each">après chaque questions</option>
                <option value="endquiz">à la fin du quiz</option>
            </select> -->
    <input type="hidden" name="qf-optshwo" value="never" />
    <input type="hidden" name="check" value="1" />
    <input type="hidden" name="quizId" value="{$ppo->quiz.id}" />
    </fieldset>
</form>

<hr class="quiz-space" />
<a name="goansw"></a>
<h3>{i18n key="quiz.msg.listQuestions" noEscape=1}</h3>
<hr class="quiz-separator" />

{if empty($ppo->questions)}
        <h4 class="quiz-index-title">{i18n key="quiz.errors.noQuestions" noEscape=1}</h4>
        <button class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
            <a href="{copixurl dest='quiz|admin|questions'}" class="ui-button-text">{i18n key="quiz.admin.addAnsw" noEscape=1}</a>
        </button>
{else}
        <button class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
            <a href="{copixurl dest='quiz|admin|questions'}" class="ui-button-text">{i18n key="quiz.admin.addAnsw" noEscape=1}</a>
        </button>
    <table id="quiz-t-list">
    <thead>
        <tr>
            <th>Nom :</td>
            <th>Enoncé :</td>
            <th>Action</td>
        </tr>
    </thead>
    <tbody>
    {foreach from=$ppo->questions item=question }
    <tr class="{cycle values="row1, row2"}">
        <td class="col1">
            {$question.name}
        </td>
        <td class="col2">
            {$question.content|truncate:50:'...'|strip_tags}
        </td>
        <td class="col3">
                <a href="{copixurl dest="quiz|admin|questions" id=$question.id qaction="modif"}">
                    <p>
                        <img class="arrow" src="{copixresource path="images/colorful/16x16/next.png"}" alt="">
                        &nbsp;{i18n key="quiz.admin.modifQuestion" noEscape=1}&nbsp;&nbsp;&nbsp;
                        <img src="{copixresource path="images/colorful/16x16/process.png"}" alt="{i18n key="quiz.admin.onemodif" noEscape=1}">
                    </p>
                </a>
        </td>
    </tr>
    {/foreach}
    </tbody>

    </table>
{/if}