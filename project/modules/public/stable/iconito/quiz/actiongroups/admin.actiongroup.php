<?php
class ActionGroupAdmin extends CopixActionGroup{

    public function beforeAction(){
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processIndex(){

        echo 'index';

    }

    public function processQuiz(){
        $pId = CopixRequest::getInt('id', false);
        if(!$pId){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz|admin|')));
        }
        //get the quiz's informations
        $QuizData = _ioDAO('quiz_quiz')->get($pId);
        if($QuizData == null || count($QuizData) == 0){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz||')));
        }
        
        $author = Kernel::getUserInfo('ID',$QuizData->id_author);
        echo '<pre>';
        var_dump($author);
        echo '</pre>';
        $ppo = new CopixPPO();
        $ppo->quiz = $QuizData;
        $ppo->author = $author;
        return _arPPO($ppo, 'admin.quiz.tpl');
    }

    public function processResults(){
        $pId = CopixRequest::getInt('id', false);
        if(!$pId){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz|admin|')));
        }
        $QuizData = _ioDAO('quiz_quiz')->get($pId);
        if($QuizData == null || count($QuizData) == 0){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz||')));
        }


        $responsesData = _ioDAO('quiz_responses')->getResponsesByQuiz($pId);
        $questionsData = _ioDAO('quiz_questions')->getQuestionsForQuiz($pId);
        foreach($questionsData as $question){
            $choicesData = _ioDAO('quiz_choices')->getChoices($question->id);
            $questions[]['choice'] = $choicesData;
            foreach($choicesData as $choice){
                if($choice->correct == 1)
                    $question[]['correct'][] = $choice->id;
            }
        }
        $i = 0;
        foreach($responsesData as $response){
            $user = Kernel::getUserInfo('ID', $response->id_user);
            $users[$i]['name'] = $user['nom'];
            $users[$i]['surname'] = $user['prenom'];
            $users[$i]['school'] = $user['scool'];
            $i++;
        }

        $ppo->quiz = $quizData;
    }


}
?>
