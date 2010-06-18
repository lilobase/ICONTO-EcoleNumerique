<script type="text/javascript">
{literal}

jQuery.noConflict();

jQuery(document).ready(function($){
    $(".quiz-quiz .quiz-a-img").click(function(){
        $(this).fadeOut();
        $(this).fadeIn('fast');
    })
} );

{/literal}
</script>
<h3>{i18n key="quiz.admin.admin" noEscape=1}</h3>
<hr class="quiz-separator" />
{if !empty($ppo->success)}
    <p class="ui-state-highlight"><strong>{$ppo->success}</strong></p>
{/if}
<div class="quiz-quiz">
    <h4>{i18n key="quiz.admin.new" noEscape=1}</h4>
    <p class="quiz-center">
        <a class="quiz-a-img" href="{copixurl dest="quiz|admin|modif" qaction="new"}">
            <img src="{copixresource path="images/colorful/64x64/add.png"}" alt="{i18n key="quiz.admin.new" noEscape=1}">
        </a>
    </p>
</div>
<div class="quiz-quiz">
    <h4>{i18n key="quiz.admin.modif" noEscape=1}</h4>
    <p class="quiz-center">
        <a class="quiz-a-img" href="{copixurl dest="quiz|admin|list" qaction="modif"}">
            <img src="{copixresource path="images/colorful/64x64/process.png"}" alt="{i18n key="quiz.admin.modif" noEscape=1}">
        </a>
    </p>
</div>
<div class="quiz-quiz">
    <h4>{i18n key="quiz.admin.results" noEscape=1}</h4>
    <p class="quiz-center">
        <a class="quiz-a-img" href="{copixurl dest="quiz|admin|list" qaction="result"}">
            <img src="{copixresource path="images/colorful/64x64/pie_chart.png"}" alt="{i18n key="quiz.admin.results" noEscape=1}">
        </a>
    </p>
</div>
<hr class="quiz-space" />
<h3>{i18n key="quiz.msg.listQuiz" noEscape=1}</h3>
<hr class="quiz-separator" />
{$ppo->list}