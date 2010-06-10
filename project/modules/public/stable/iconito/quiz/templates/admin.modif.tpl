<form id="quiz-form">
    <fieldset id="qf-main">
        <legend>{i18n key="quiz.form.infos" noEscape=1}</legend>

        <label for="qf-title">{i18n key="quiz.form.title" noEscape=1}</label>
            <input type="text" name="qf-title" value="{$ppo->quiz.name|utf8_encode}">
        <br />
        <label for="qf-description">{i18n key="quiz.form.desc" noEscape=1}</label>
            <textarea id="qf-description" name="qf-description">{$ppo->quiz.description|utf8_encode}</textarea>
        <br />
        <label for="qf-help">{i18n key="quiz.form.help" noEscape=1}</label>
            <textarea id="qf-help" name="qf-help">{$ppo->quiz.help|utf8_encode}</textarea>
    </fieldset>
    <fieldset id="qf-opt">
        <legend>{i18n key="quiz.form.options" noEscape=1}</legend>

        <label for="qf-datestart">{i18n key="quiz.form.datestart" noEscape=1}</label>
            <input type="text" class="qf-date" name="qf-datestart" value="{$ppo->quiz.date_start}" />
        <br />
        <label for="qf-dateend">{i18n key="quiz.form.dateend" noEscape=1}</label>
            <input type="text" class="qf-date" name="qf-dateend" value="{$ppo->quiz.date_end}" />
       <br />
       <label for="qf-optshow">{i18n key="quiz.form.optshow" noEscape=1}</label>
            <select name="qf-datestart" value="{$ppo->quiz.opt_show_results}">
                <option value="never">jamais</option>
                <option value="each">après chaque questions</option>
                <option value="endquiz">à la fin du quiz</option>
            </select>
    </fieldset>
</form>
{literal}
<script type="text/javascript">
    jQuery.noConflict();

    jQuery(document).ready(function($){
        $.datepicker.setDefaults($.datepicker.regional['fr']);
        $('.qf-date').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    } );
</script>
{/literal}