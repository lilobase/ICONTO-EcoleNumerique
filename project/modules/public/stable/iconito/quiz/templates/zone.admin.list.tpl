{if empty($ppo->quizList)}

        <h4 class="quiz-index-title">{i18n key="quiz.errors.noQuiz" noEscape=1}</h4>
{else}
    <table id="quiz-t-list">
    <thead>
        <tr>
            <th>Nom :</td>
            <th>Description :</td>
            <th>Action</td>
        </tr>
    </thead>
    <tbody>
    {foreach from=$ppo->quizList item=quiz }
    <tr class="{cycle values="row1, row2"}">
        <td class="col1">
            {$quiz.name}
        </td>
        <td class="col2">
            {$quiz.description|truncate:50:'...'|strip_tags}
        </td>
        <td class="col3">
            {if $ppo->action == 'modif' || empty($ppo->action)}
                <a href="{copixurl dest="quiz|admin|modif" id=$quiz.id qaction="modif"}">
                    <p>
                        <img class="arrow" src="{copixresource path="images/colorful/16x16/next.png"}" alt="">
                        &nbsp;{i18n key="quiz.admin.onemodif" noEscape=1}&nbsp;&nbsp;&nbsp;
                        <img src="{copixresource path="images/colorful/16x16/process.png"}" alt="{i18n key="quiz.admin.onemodif" noEscape=1}">
                    </p>
                </a>
            {/if}
            {if $ppo->action == 'result' || empty($ppo->action)}
                <a href="{copixurl dest="quiz|admin|results" id=$quiz.id}">
                    <p>
                        <img class="arrow" src="{copixresource path="images/colorful/16x16/next.png"}" alt="">
                        &nbsp;{i18n key="quiz.admin.results" noEscape=1}&nbsp;
                        <img src="{copixresource path="images/colorful/16x16/pie_chart.png"}" alt="{i18n key="quiz.admin.results" noEscape=1}">
                    </p>
                </a>
            {/if}
        </td>
    </tr>
    {/foreach}
    </tbody>

    </table>
{/if}
{literal}
<script type="text/javascript">
jQuery.noConflict();

jQuery(document).ready(function($){
    $(".col3 a").click(function(){
        $(this).fadeOut();
        $(this).fadeIn('fast');
        return true;
    });
});
</script>
{/literal}