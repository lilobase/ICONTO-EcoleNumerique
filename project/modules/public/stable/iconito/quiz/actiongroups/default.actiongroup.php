<?php
/*
 * TODO : g�rer les r�ponses textuelles !
 *
 */
class ActionGroupDefault extends enicActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processGo()
    {
        $id = $this->request('id');
        return $this->go('quiz|default|default', array('id' => $id));
    }

    public function processDefault()
    {
        $idGrQuiz = (int)$this->request('id');
        if(empty($idGrQuiz) ){
            if(!$this->session->exists('id_gr_quiz')){
                return $this->error('quiz.errors.noQuiz');
            }else{
                $idGrQuiz = $this->session->load('id_gr_quiz');
            }
        }

        if(Kernel::getLevel( "MOD_QUIZ", $idGrQuiz ) < PROFILE_CCV_READ)
            return $this->error ('quiz.admin.noRight');

        qSession('id_gr_quiz', $idGrQuiz);
        $this->session->save('id_gr_quiz', $idGrQuiz);

        //get current quiz :
        $currentQuiz = $this->model->query('SELECT DISTINCT(quiz.id), quiz.* FROM module_quiz_quiz AS quiz
                                            INNER JOIN module_quiz_questions AS answ ON quiz.id = answ.id_quiz
                                            WHERE
                                            ((`date_start` < '.time().' AND `date_end` > '.time().') OR
                                            (`date_start` = 0 OR `date_end` = 0)) AND
                                            `lock` = 0 AND gr_id = '.$idGrQuiz.'
                                            ORDER BY date_end DESC')
                                    ->toArray();

        $this->addCss('styles/module_quiz.css');
        $this->js->button('.button');

        $ppo = new CopixPPO();
        $ppo->quiz = $currentQuiz;

        if(Kernel::getLevel( 'MOD_QUIZ', $idGrQuiz) >= PROFILE_CCV_ADMIN){

        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listActive'),
                            'type' => 'list-active',
                            'url' => $this->url('quiz|default|default', array('qaction' => 'list')));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listAll'),
                            'type' => 'list',
                            'url' => $this->url('quiz|admin|list'));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.new'),
                            'type' => 'create',
                            'url' => $this->url('quiz|admin|modif', array('qaction' => 'new')));
        }
        return _arPPO($ppo, 'quiz.tpl');
    }

    /* show answers */
    public function processQuiz()
    {
        $pId = CopixRequest::getInt('id', false);
        //init & secure quiz system !
        if(is_null(CopixSession::get('id')) || $pId != qSession('id')){
            if($pId === false || !($quizData = _dao('quiz|quiz_quiz')->get($pId)))
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz||')));

            //to propagate the quizData
            $quizData = &$quizData;

            //security for quiz
            $gr_id = qSession('id_gr_quiz');
            if($gr_id != $quizData->gr_id)
                return $this->error ('quiz.errors.noQuiz');

            //session storage :
            qSession('id', $pId);
            qSession('name', $quizData->name);
            qSession('opt', array(
                'save' => $quizData->opt_save,
                'show_result' => $quizData->opt_show_results,
            ));

            //description
            $desc = ($quizData->description == null) ? null : $quizData->description;

            if($quizData->lock == 1)
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.lock'), 'back'=>CopixUrl::get('quiz||')));

//time test :
            if(time() < $quizData->date_start && $quizData->date_start != 0)
                return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.beforeStart'), 'back'=>CopixUrl::get('quiz||')));

            if(time() > $quizData->date_end && CopixRequest::get('action') != 'end_quiz' &&  $quizData->date_end != 0)
                return CopixActionGroup::process('quiz|default::EndQuiz', array ('id' => $pId));

        }else{
            $quizData = _dao('quiz|quiz_quiz')->get($pId);
        }

        //echo CopixRequest::get('action');
        //ICI REROUTAGE EN FONCTION BESOIN !!

        //get informations from DB
        $userId = _currentUser()->getId();
        $authorInfos = Kernel::getUserInfo('ID', $quizData->id_owner);
        $questionsData = _ioDAO('quiz|quiz_questions')->getQuestionsForQuiz($pId);
        $responsesFromUser = _ioDAO('quiz|quiz_responses')->getResponsesFromUser($userId, $pId);

        //si pas de questions : erreur :
        if(empty($questionsData))
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
            $currentQuestionId = $question->id*1;
            //pile for question
            $qQueue[] = $currentQuestionId;
            $questionsReturn[$i]['ct'] = $question;
            $questionsReturn[$i]['userResp'] = isset($userChoices[$currentQuestionId]);
            $i++;
        }

        if(!isset($qQueue))
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuestions'), 'back'=>CopixUrl::get('quiz||')));


        //data storage
        $this->session->save('questions', $qQueue);
        $this->session->save('quizId', $pId);
        $this->session->save('authorName', $authorInfos['nom']);
        $this->session->save('authorSurname', $authorInfos['prenom']);
        //load help
        $help = (empty($quizData->help)) ? $this->i18n('quiz.msg.noHelp') : $quizData->help ;
        qSession('help', $help);

        //if qID exists in url : routing to question
        $qId = CopixRequest::get('qId', false);
        if($qId)
            return CopixActionGroup::process('quiz|default::Question', array ('id' => $pId, 'qId' => (int)$qId));
        elseif(!$uResp) //if users have not started the quiz :
            return CopixActionGroup::process('quiz|default::Question', array ('id' => $pId, 'qId' => $qQueue[0]));

        //var_dump($questionsReturn);
        //start TPL
        $this->addCss('styles/module_quiz.css');
//        $this->js->button('.button');
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
        $ppo->next = $qQueue[0];
        $ppo->TITLE_PAGE = 'Quiz';

         if(Kernel::getLevel( 'MOD_QUIZ', $pId) >= PROFILE_CCV_ADMIN){

        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listActive'),
                            'type' => 'list-active',
                            'url' => $this->url('quiz|default|default', array('qaction' => 'list')));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listAll'),
                            'type' => 'list',
                            'url' => $this->url('quiz|admin|list'));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.new'),
                            'type' => 'create',
                            'url' => $this->url('quiz|admin|modif', array('qaction' => 'new')));
        }

        return _arPPO($ppo, 'accueil_quiz.tpl');
    }

    /*
     * For ending quiz
     *
     */
    public function processEndQuiz()
    {
        $pId = CopixRequest::getInt('id', false);
        //security check
        if(!$pId || is_null(qSession('id')) || $pId != qSession('id')){
            return CopixActionGroup::process('quiz|default::Quiz', array ('id' => $pId));
        }
        return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get('quiz.errors.endQuiz'), 'back'=>CopixUrl::get('quiz||')));

    }

    /*
     * For Questions & end of the quiz
     *
     */
    public function processQuestion()
    {
        //get params
        $pId = CopixRequest::getInt('id', false);
        $pQId = CopixRequest::getInt('qId', false);

        if(empty($pId) || !$this->session->exists('questions'))
            $this->error ('quiz.errors.badOperation');

        if($pQId == false)
            return CopixActionGroup::process('quiz|default::Quiz', array ('id' => $this->session->load('quizId')));

        //get question datas
        $questionDatas = $this->service('QuizService')->getQuestion($pQId);

        //check if question exists
        if(empty($questionDatas))
            $this->error ('quiz.errors.noQuestions');

        //check if question & quiz are same id_quiz
        if($pId != $questionDatas['id_quiz'])
            $this->error ('quiz.errors.badOperation');

        //get responses from users :
        $responsesDatas = $this->service('QuizService')->getResponses($pQId, $this->user->id);

        //check if user as already respond to the question
        $uResp = (!empty($responsesDatas)) ? true : false;

        //get choices for question
        $choicesDatas = $this->service('QuizService')->getChoices($pQId);

        //if no choices : error :
        $this->error('quiz.errors.noChoice');

        //data preparation
        $i=0;
        $correct = 0;

        $choiceReturn = array();
        foreach($choicesDatas as $choice){

            $choiceReturn[$i]['ct'] = $choice['content'];
            $choiceReturn[$i]['id'] = $choice['id'];
            $choiceReturn[$i]['user'] = false;

            if($choice['correct'] == 1)
                $correct++;

            foreach($responsesDatas as $response){
                if((int)$response['id_choice'] == (int)$choice['id']){
                    $choiceReturn[$i]['user'] = true;
                }
            }

            $i++;
        }

        //check the current pos in queue, and build array for nav
        $qQueue = $this->session->load('questions');
        foreach($qQueue as $key => $qe){
            $questionTpl[$key+1] = $qe;
            //if queue id == current id
            if($qe == $pQId){
                $questionTpl[$key+1] = 'current';
                $prev = (isset($qQueue[$key-1])) ? $qQueue[$key-1] : false;
                $next = (isset($qQueue[$key+1])) ? $qQueue[$key+1] : false;
            }

        }

        //put next answ in flash
        $this->flash->nextAnsw = $next;
        $this->flash->currentAnsw = $pQId;
        $this->flash->typeAnsw = $questionDatas['opt_type'];

        //build tpl
        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        CopixHTMLHeader::addCSSLink (_resource("styles/jquery.fancybox-1.3.4.css"));
//        $this->js->button('.button');
        $ppo = new CopixPPO();
        $ppo->error =  ($this->flash->has('error')) ? $this->flash->error : null;
        $ppo->userResp = $uResp;
        $ppo->choices = $choiceReturn;
        $ppo->prev = $prev;
        $ppo->nameAuthor = $this->session->load('authorName');
        $ppo->surname = $this->session->load('authorSurname');
        $ppo->questionTpl = $questionTpl;
        $ppo->question = $questionDatas;
        $ppo->type = ($questionDatas['opt_type'] == 'choice') ? 'radio' : 'txt';
        $ppo->select = ($correct > 1) ? 'checkbox' : 'radio';
        $ppo->help = qSession('help');
        $ppo->name = qSession('name');
        $ppo->next = $next;

         if(Kernel::getLevel( 'MOD_QUIZ', $pId) >= PROFILE_CCV_ADMIN){

        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listActive'),
                            'type' => 'list-active',
                            'url' => $this->url('quiz|default|default', array('qaction' => 'list')));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.listAll'),
                            'type' => 'list',
                            'url' => $this->url('quiz|admin|list'));
        $ppo->MENU[] = array('txt' => $this->i18n('quiz.admin.new'),
                            'type' => 'create',
                            'url' => $this->url('quiz|admin|modif', array('qaction' => 'new')));
        }
        $this->js->dialog('#qd-help', '#help-data');
        return _arPPO($ppo, 'question.tpl');

    }

    public function processSave()
    {
        if(!$this->flash->has('nextAnsw'))
            return $this->error('quiz.errors.badOperation');

        if(is_null(qSession('id')))
            return CopixActionGroup::process('quiz|default::Quiz', array ('id' => false));

        //get url's answ id
        $qId = $this->request('qId')*1;

        //test id validity
        if($qId != $this->flash->currentAnsw)
           return $this->error('quiz.errors.badOperation');

        //get responses form datas
        $pResponse = CopixRequest::get('response', false);

        if(!$pResponse){
            $this->flash->error = $this->i18n('quiz.errors.emptyResponse');
            return $this->go('quiz|default|question', array ('id' => qSession('id'), 'qId' => $this->flash->currentAnsw));
        }

        $optType = ($this->flash->typeAnsw == 'choice') ? 'radio' : 'txt';
        $userId = $this->user->id;

        //delete previous info
        $criteres = _daoSp()->addCondition('id_user', '=', $userId)
                            ->addCondition('id_question', '=', $this->flash->currentAnsw, 'and');

        _dao('quiz_response_insert')->deleteBy($criteres);

        if($optType == 'radio'){

            $i=0;

            foreach($pResponse as $response){
                $record = _record('quiz_response_insert');
                $record->id_user = $userId;
                $record->id_choice = $response+0;
                $record->id_question = $this->flash->currentAnsw;
                $record->date = time();
                $responses[] = $response+0;
                _dao('quiz_response_insert')->insert($record);
                $i++;
            }

        }else{
            //cas du submit txt
        }

        //lock test
        $quizData = _dao('quiz_quiz')->get(qSession('id'));
        if($quizData->lock == 1)
            return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.lock'), 'back'=>CopixUrl::get('quiz||')));

        $nextQ = $this->flash->nextAnsw;

        //if next answ = flash : end of quiz
        if(!$nextQ)
            return _arRedirect(_url('quiz|default|endQuestions', array ('id' => qSession('id'))));

        return _arRedirect(_url('quiz|default|question', array ('id' => qSession('id'), 'qId' => $nextQ)));
    }

    public function processEndQuestions()
    {
        $pId = CopixRequest::getInt('id', false);
        if(!$pId || is_null(qSession('id')) || $pId != qSession('id')){
            return CopixActionGroup::process('quiz|default::Quiz', array ('id' => $pId));
        }
        $ppo = new CopixPPO();
        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        return _arPPO($ppo, 'end_questions.tpl');
    }

}



function qSession($key, $value = '__get')
{
    if($value != '__get'){
        return CopixSession::set($key, $value, 'quiz');
    }
    if($key == 'delete'){
        CopixSession::destroyNamespace('quiz');
    }
    return CopixSession::get($key, 'quiz');
}
