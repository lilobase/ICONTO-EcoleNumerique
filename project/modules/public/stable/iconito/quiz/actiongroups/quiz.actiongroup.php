<?php
/*
 * TODO : gérer les réponses textuelles !
 *
 */
class ActionGroupQuiz extends CopixActionGroup {

    public function beforeAction(){
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function Index(){
        $criters = _daoSP()->addCondition('date_start', '<', time())
                            ->addCondition('date_end', '>', time(), 'and')
                            ->addCondition('date_start', '=', 0, 'or')
                            ->addCondition('date_end', '=', 0, 'or')
                            ->addCondition('lock', '=', 0, 'and')
                            ->orderBy(array('date_end', 'DESC'));
        $dataQuiz = _dao('quiz_quiz')->findBy($criters);

        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        $ppo = new CopixPPO();
        $ppo->quiz = $dataQuiz;
        return _arPPO($ppo, 'quiz.tpl');
    }

    /* show answers */
    public function processQuiz(){

        $pId = CopixRequest::getInt('id', false);
        //init & secure quiz system !
        if(is_null(CopixSession::get('id')) || $pId != qSession('id')){
            if($pId === false || !($quizData = _dao('quiz|quiz_quiz')->get($pId)))
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz||')));

            //to propagate the quizData
            $quizData = &$quizData;
            //session storage :
            qSession('id', $pId);
            qSession('name', $quizData->name);
            qSession('opt', array(
                'save' => $quizData->opt_save,
                'show_result' => $quizData->opt_show_results,
            ));
            $desc = ($quizData->description == null) ? null : $quizData->description;
            qSession('help', $desc);

            if($quizData->lock == 1)
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.lock'), 'back'=>CopixUrl::get('quiz||')));

//time test :
            if(time() < $quizData->date_start && $quizData->date_start != 0)
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.beforeStart'), 'back'=>CopixUrl::get('quiz||')));

            if(time() > $quizData->date_end && CopixRequest::get('action') != 'end_quiz' &&  $quizData->date_end != 0)
                return CopixActionGroup::process('quiz|quiz::EndQuiz', array ('id' => $pId));
            
        }else{
            $quizData = _dao('quiz|quiz_quiz')->get($pId);
        }

        //echo CopixRequest::get('action');
        //ICI REROUTAGE EN FONCTION BESOIN !!

        //get informations from DB
        $userId = _currentUser()->getId();
        $authorInfos = Kernel::getUserInfo('ID', $quizData->id_author);
        $questionsData = _ioDAO('quiz|quiz_questions')->getQuestionsForQuiz($pId);
        $responsesFromUser = _ioDAO('quiz|quiz_responses')->getResponsesFromUser($userId, $pId);

        //si pas de questions : erreur :
        if($questionsData == null)
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuestions'), 'back'=>CopixUrl::get('quiz||')));
        
        //if user have already begin the quiz :
        $uResp = false;
        $userResponses = array();
        if(count($responsesFromUser) != 0){
            $uResp = true;
            foreach($responsesFromUser as $resp){
                $userResponses[] = $resp->id_question;
                $userChoices[$resp->id_question] = $resp->id_choice;
            }
            array_unique($userResponses);
        }

        //fetch all question
        $i=0;
        $userAllQuestions = true;
        foreach($questionsData as $question){
            $questionsReturn[$i]['id'] = $question->id;
            $questionsReturn[$i]['order'] = $question->order;
            $questionsReturn[$i]['key'] = $i;
            $questionsReturn[$i]['userChoices'] = array();
            $questionsReturn[$i]['opt_type'] = $question->opt_type;
            if(in_array($question->id, $userResponses)){
                $questionsReturn[$i]['userResp'] = true;
                $questionsReturn[$i]['userChoices'][] = $userChoices[$question->id];
            }else{
                $questionsReturn[$i]['userResp'] = false;
                $userAllQuestions = false;
            }
            $i++;
        }
        //pas de questions :
        if($i == 0)
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuestions'), 'back'=>CopixUrl::get('quiz||')));

        //data storage
        qSession('questions', $questionsReturn);
        qSession('current', false);
        qSession('next', $questionsReturn[0]);
        qSession('prev', false);
		
        //if qID exists in url : routing to question
        $qId = CopixRequest::get('qId', false);
        if($qId)
            return CopixActionGroup::process('quiz|quiz::Question', array ('id' => $pId, 'qId' => (int)$qId));
        elseif(!$uResp) //if users have not started the quiz :
            return CopixActionGroup::process('quiz|quiz::Question', array ('id' => $pId, 'qId' => $questionsReturn[0]['id']));

        //var_dump($questionsReturn);
        //start TPL
        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        $ppo = new CopixPPO();
        //global data for quiz
        $ppo->name = $quizData->name;
        $ppo->quizId = $pId;
        $ppo->description = stripslashes($desc);
        $ppo->nameAuthor = $authorInfos['nom'];
        $ppo->surname = $authorInfos['prenom'];
        $ppo->img = $quizData->pic;
        //user data for quiz
        $ppo->uResp = $uResp;
        $ppo->uEnd = $userAllQuestions;
        //questions datas 
        $ppo->questions = $questionsReturn;
        $ppo->next = qSession('next');
        $ppo->TITLE_PAGE = 'Quiz';
        return _arPPO($ppo, 'accueil_quiz.tpl');
    }

    /*
     * For ending quiz
     *
     */
    public function processEndQuiz(){
        $pId = CopixRequest::getInt('id', false);
        //security check
        if(!$pId || is_null(qSession('id')) || $pId != qSession('id')){
            return CopixActionGroup::process('quiz|quiz::Quiz', array ('id' => $pId));
        }
        return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get('quiz.errors.endQuiz'), 'back'=>CopixUrl::get('quiz||')));

    }

    /*
     * For Questions & end of the quiz
     *
     */
    public function processQuestion(){
        //get params
        $pId = CopixRequest::getInt('id', false);
        $pQId = CopixRequest::getInt('qId', false);
        
        //test params
        if(!$pId || qSession('id') == null || $pId != qSession('id') || !$pQId)
            return CopixActionGroup::process('quiz|quiz::Quiz', array ('id' => $pId, 'qId' => false));

        //test if question exist :
        $questions = qSession('questions');
        $questionExist = false;
        foreach($questions as $question){
            if($question['id'] == $pQId)
                $questionExist = true;
        }
        if(!$questionExist)
            return CopixActionGroup::process('quiz|quiz::Quiz', array ('id' => $pId, 'qId' => false));

        //update session informations :
        
        //if user directly access to question :
        $currentQ = qSession('next');
        if($pQId != $currentQ['id'] && $currentQ)
            foreach($questions as $question){
                if($question['id'] == $pQId)
                    qSession('next', $question);
        }
        $questions = qSession('questions');
        $currentQ = qSession('next');
        
        //create the next informations :
        $currentArrayPos = $currentQ['key'];
        $nextQ = (!isset($questions[$currentArrayPos+1])) ? false : $questions[$currentArrayPos+1];
        $prevQ = (!isset($questions[$currentArrayPos-1])) ? false : $questions[$currentArrayPos-1];
        qSession('prev', $prevQ);
        qSession('next', $nextQ);
        qSession('current', $currentQ);

        //fetch info :
        $choicesData = _ioDAO('quiz|quiz_choices')->getChoices($pQId);
        if(!count($choicesData))
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noChoice'), 'back'=>CopixUrl::get('quiz||')));
        $questionsData = _ioDAO('quiz|quiz_questions')->get($pQId);

        //data preparation
        $i=0;
        $one = 0;
        foreach($choicesData as $choice){

            $choiceReturn[$i]['txt'] = $choice->content_txt;
            $choiceReturn[$i]['pic'] = $choice->content_pic;
            $choiceReturn[$i]['id'] = $choice->id;
            if(in_array($choice->id, $currentQ['userChoices']))
                $choiceReturn[$i]['user'] = true;
            else
                $choiceReturn[$i]['user'] = false;
            if($choice->correct)
                $one++;

            $i++;
        }
        if($one != 1)
            $one = false;
        elseif($one == 0)
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get('quiz.errors.noTrue'), 'back'=>CopixUrl::get('quiz||')));
        
        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        CopixHTMLHeader::addCSSLink (_resource("styles/jquery.fancybox-1.3.1.css"));
        CopixHtmlHeader::addJSLink('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/help.js');
        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/jquery.fancybox-1.3.1.pack.js');
        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/jquery.easing-1.3.pack.js');
        $ppo = new CopixPPO();
        $ppo->error =  CopixRequest::get('error', false);
        $ppo->userResp = ($currentQ['userResp']) ? true : false;
        $ppo->choices = $choiceReturn;
        $ppo->prev = $prevQ;
        $ppo->next = $nextQ;
        $ppo->question = $questionsData;
        $ppo->type = ($questionsData->opt_type == 'choice') ? 'radio' : 'txt';
        $ppo->select = ($one == 1) ? 'radio' : 'checkbox';
        $ppo->help = qSession('help');
        return _arPPO($ppo, 'question.tpl'); 

    }

    public function processSave(){
        if(is_null(qSession('id')))
            return CopixActionGroup::process('quiz|quiz::Quiz', array ('id' => false));
        $pResponse = CopixRequest::get('response', false);
        $currentQ = qSession('current');
        $questions = qSession('questions');

        if(!$pResponse)
            return CopixActionGroup::process('quiz|quiz::Question', array ('id' => qSession('id'), 'qId' => $currentQ['id'], 'error' => CopixI18N::get('quiz.errors.emptyResponse')));

        $optType = ($currentQ['opt_type'] == 'choice') ? 'radio' : 'txt';
        $userId = _currentUser()->getId();

        //delete previous info
        $criteres = _daoSp()->addCondition('id_user', '=', $userId)
                            ->addCondition('id_question', '=', $currentQ['id'], 'and');
        _dao('quiz_response_insert')->deleteBy($criteres);

        if($optType == 'radio'){

            $i=0;
            foreach($pResponse as $response){
                $record = _record('quiz_response_insert');
                $record->id_user = $userId;
                $record->id_choice = $response+0;
                $record->id_question = $currentQ['id'];
                $record->date = time();
                $responses[$currentQ['id']][] = $response+0;
                _dao('quiz_response_insert')->insert($record);
                $i++;
            }

            //merge new record with session's data
            foreach($questions as $key => $question){
                if(isset($responses[$question['id']])){
                    $questions[$key]['userResp'] = true;
                    $questions[$key]['userChoices'] = $responses[$question['id']];
                }
            }

        }else{
            //cas du submit txt
        }

        //lock test
        $quizData = _dao('quiz_quiz')->get(qSession('id'));
        if($quizData->lock == 1)
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.lock'), 'back'=>CopixUrl::get('quiz||')));

        //in session :
        qSession('questions', $questions);
        $nextQ = qSession('next');
        //var_dump($nextQ);die();
        if(!$nextQ)
            return _arRedirect(_url('quiz|endQuestions', array ('id' => qSession('id'))));

        return _arRedirect(_url('quiz|question', array ('id' => qSession('id'), 'qId' => $nextQ['id'])));
    }

    public function processEndQuestions(){
        $pId = CopixRequest::getInt('id', false);
        if(!$pId || is_null(qSession('id')) || $pId != qSession('id')){
            return CopixActionGroup::process('quiz|quiz::Quiz', array ('id' => $pId));
        }
        $ppo = new CopixPPO();
        return _arPPO($ppo, 'end_questions.tpl');
    }
    
}



function qSession($key, $value = '__get'){
    if($value != '__get'){
        return CopixSession::set($key, $value, 'quiz');
    }
    if($key == 'delete'){
        CopixSession::destroyNamespace('quiz');
    }
    return CopixSession::get($key, 'quiz');
}
?>