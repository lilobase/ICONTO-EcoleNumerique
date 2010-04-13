<h3>{i18n key="quiz.msg.listQuiz" noEscape=1}</h3>
{foreach from=$ppo->quiz item=quiz }
<div class="quiz-quiz">
    <h5>{$quiz->name}</h5>
    <p class="quiz-desc">{$quiz->description}</p>
    <a href="{copixurl dest="quiz|default|quiz" id=$quiz->id}">{i18n key="quiz.msg.goQuiz" noEscape=1}</a>
</div>
{/foreach}