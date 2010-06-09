<h2>{i18n key="quiz.msg.listQuiz" noEscape=1}</h2>

<hr class="quiz-separator" />
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
            {$quiz.description|truncate:50:'...'|utf8_encode|strip_tags}
        </td>
        <td class="col3">
            {if $ppo->action == 'modif' || empty($ppo->action)}
                <a href="">
                    <img class="arrow" src="{copixresource path="images/colorful/16x16/next.png"}" alt="">
                    <p>&nbsp;{i18n key="quiz.admin.onemodif" noEscape=1}</p>
                    <img src="{copixresource path="images/colorful/16x16/process.png"}" alt="{i18n key="quiz.admin.onemodif" noEscape=1}">
                </a>
            {/if}
            {if empty($ppo->action)}
                <br />
            {/if}
            {if $ppo->action == 'result' || empty($ppo->action)}
                <a href="{copixurl dest="quiz|admin|results" id=$quiz.id}">
                    <img class="arrow" src="{copixresource path="images/colorful/16x16/next.png"}" alt="">
                    <p>&nbsp;{i18n key="quiz.admin.oneresults" noEscape=1}</p>
                    <img src="{copixresource path="images/colorful/16x16/pie_chart.png"}" alt="{i18n key="quiz.admin.oneresults" noEscape=1}">
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