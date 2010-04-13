<h3>{$ppo->quiz->name}</h3>
<p id="quiz-desc">{$ppo->quiz->description}</p>
<p id="quiz-author">{i18n key="quiz.msg.author" noEscape=1} {$ppo->author.nom} {$ppo->author.prenom}</p>
<div class="quiz-adm-action">
{i18n key="quiz.msg.modifQuiz" noEscape=1}
</div>
<div class="quiz-adm-action">
<a href="{copixurl dest="quiz|admin|results" id=$ppo->quiz->id type=all}" >{i18n key="quiz.msg.showResults" noEscape=1}</a>
</div>