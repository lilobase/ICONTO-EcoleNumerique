<?php
//

$index = new CopixAction('quiz', 'Index');

$quiz = new CopixAction('quiz', 'processQuiz');
$endQuiz = new CopixAction('quiz', 'processEndQuiz');
$question = new CopixAction('quiz', 'processQuestion');
$save = new CopixAction('quiz', 'processSave');

$default = & $index;
