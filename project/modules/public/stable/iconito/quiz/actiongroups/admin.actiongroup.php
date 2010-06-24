<?php
class ActionGroupAdmin extends enicActionGroup{

    public function beforeAction(){
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processIndex(){

        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        //start tpl :
        $ppo = new CopixPPO();
        $ppo->success = (isset($this->flash->success)) ? $this->flash->success : null;
        $ppo->list = $ppo->list = CopixZone::process('adminList');
        return _arPPO($ppo, 'admin.index.tpl');

    }

    public function processList(){

        $this->addCss("styles/module_quiz.css");

        //get the active quiz liste
        $action = $this->request('qaction', 'str');

        //start tpl :
        $ppo = new CopixPPO();
        $ppo->list = CopixZone::process('adminList', array('action' => $action));
        $ppo->MENU = array(
                        array( 'txt' => $this->i18n('quiz.admin.index'),
                            'url' => $this->url('quiz|admin|'))
                      );
        
        return _arPPO($ppo, 'admin.list.tpl');
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
        
        $author = Kernel::getUserInfo('ID', $QuizData->id_author, array('link_data' => true));
        $ppo = new CopixPPO();
        $ppo->quiz = $QuizData;
        $ppo->author = $author;
        return _arPPO($ppo, 'admin.quiz.tpl');
    }

    public function processModif(){
        //modif or new quiz or errors
        $action = ($this->flash->has('modifAction')) ? $this->flash->modifAction : $this->request('qaction', 'str');
        $qId    = ($this->flash->has('quizId')) ? $this->flash->quizId : $this->request('id', 'int');
        $errors = $this->flash->has('errors');

        //init data's array
        $quizDatas = array();
        $questionsDatas = array();

        //init ppo object
        $ppo = new CopixPPO();

        //case of modification :
        if(!empty($action) && $action=='modif' && !$errors && !empty($qId)){

            //case of only qId : set the action type
            $action = 'modif';

            //get quiz datas
            $quizDatas = $this->service('QuizService')->getQuizDatas($qId);
            
            /*
             * SECURITY CHECK
             */
            //test if the quiz exists
            if(empty($quizDatas))
                return $this->error('quiz.errors.noQuiz');

          //test if the current user is owner of the quiz
            if($quizDatas['id_owner'] !== $this->user->id)
                return $this->error('quiz.admin.noRight');

            //get questions datas :
            $questionsDatas = $this->service('QuizService')->getQuestionsByQuiz($qId);

            //formate timestamp to fr date
            $quizDatas['date_start'] = $this->service('QuizService')->timeToDate($quizDatas['date_start']);
            $quizDatas['date_end'] = $this->service('QuizService')->timeToDate($quizDatas['date_end']);
        }

        //case of errors :
        if($errors){

            //get datas :
            $quizDatas = $this->flash->quizDatas;

            //get errors :
            $ppo->errors = $this->flash->quizErrors;

        }

        /*
         * generate flash :
         */
        $this->flash->processAction = $action;
        $this->flash->quizId = $qId;

        //generate the tpl
        $this->js->wysiwyg('#qf-description');
        $this->js->wysiwyg('#qf-help');
        $this->js->date('.qf-date', 'full');

        $this->addCss('styles/module_quiz.css');

        $ppo->quiz = $quizDatas;
        $ppo->questions = $questionsDatas;
        $ppo->action = $this->url('quiz|admin|processModif');
        $ppo->MENU = array(
                        array( 'txt' => $this->i18n('quiz.admin.index'),
                                'url' => $this->url('quiz|admin|'))
                      );
        return _arPPO($ppo, 'admin.modif.tpl');
    }

    function processProcessModif(){

        /*
         * SECURITY CHECK
         */
        
        //test the user right :
        if(!$this->flash->has('processAction'))
            return $this->error('quiz.admin.noRight', true, 'quiz|admin|index');

        //test if form's datas exists && test errors flash
        if($this->request('check') != 1 || $this->flash->has('errors'))
            return $this->go('quiz|admin|');

        //if modif case : test if the current id is right :
        if($this->flash->get('processAction') == 'modif' && $this->request('quizId', 'int') != $this->flash->quizId)
            return $this->error('quiz.errors.badOperation', true, 'quiz|admin|index');

        /*
         * GET DATAS
         */
        //init errors :
        $error = array();

        //get non-optional values
        $form['name'] = $this->request('qf-title');
        if(empty($form['name']))
            $error['title'] = '<p class="ui-state-error" >'.$this->i18n('quiz.form.required').'</p>';

        //get other values :
        $form['description']       = $this->request('qf-description');
        $form['help']       = $this->request('qf-help');
        $form['optshow']    = $this->request('qf-optshow');
        $form['lock']       = $this->request('qf-lock');
        $form['id']         = $this->request('quizId');
        $form['date_start']  = $this->request('qf-datestart');
        $form['date_end']    = $this->request('qf-dateend');

        //check errors :
        if(!empty($error)){
            $this->flash->set('quizErrors', $error);
            $this->flash->set('quizDatas', $form);
            $this->flash->set('quizId', $this->flash->get('quizId'));
            $this->flash->set('errors', true);
            $this->flash->set('modifAction', $this->flash->get('processAction'));

            //redirect to modif
            return $this->go('quiz|admin|modif', array('id' => $this->flash->quizId));
        }

        /*
         * PROCESS DATAS
         */
         //date format
        $form['date_start'] = $this->service('QuizService')->dateToTime($form['date_start']);
        $form['date_end'] = $this->service('QuizService')->dateToTime($form['date_end']);

        if($this->flash->processAction == 'modif')
            $this->service('QuizService')->updateForm($form);
        else
            $this->service('QuizService')->newForm($form);

        //if all is OK :
        $this->flash->success = $this->i18n('quiz.admin.modifSuccess');
        return $this->go('quiz|admin|');
    }

    public function processQuestions(){
        if(!isset($this->flash->quizId))
            return $this->error('quiz.admin.noRight');

        //get the current quizId
        $quizId = $this->flash->quizId;

        //get quiz infos
        $quizDatas = $this->service('QuizService')->getQuizDatas($quizId);
        
        //verify quiz existence
        if(empty($quizDatas))
            return $this->error('quiz.errors.noQuiz');

        //check type of modif
        $modifAction    = (isset($this->flash->typeAction)) ? $this->flash->typeAction : $this->request('qaction');
        $answId         =  (isset($tthis->flash->answId)) ? $this->flash->answId : $this->request('id', 'int');

        //test if is an error :
        $error = isset($this->flash->error);

        /*
         * modification :
         */
        //init the datas arrays
        $answerDatas = array();
        $responsesDatas = array();
        $errorDatas = array();
        
        //check and validate modification :
        if($modifAction == 'modif' && !empty($answId) && !$error){
            $answerDatas = $this->service('QuizService')->getAnswerDatas($answId);

            //if no datas return to quiz
            if(empty($questionDatas))
                $this->error('quiz.errors.noQuestions');

            //check that id_quiz is the current modif quiz
            if($answerDatas['id_quiz'] != $quizId)
                $this->error('quiz.admin.noRight');

            $responsesDatas = $this->service('QuizService')->getChoicesByAnswer($answId);

        //if errors
        }elseif ($error) {
            //if is datas in responses :
            if(isset($this->flash->respDatas)){
                $responsesDatas = $this->flash->respDatas;

            //if is datas in answ
            }elseif(isset($this->flash->answDatas)){
                $answerDatas = $this->flash->answDatas;

            }

            $errorDatas = $this->flash->errorMsg;
        }

        /*
         * build flash
         */
        //for Quiz
        $this->flash->modifAction = 'modif';
        $this->flash->quizId = $quizId;

        //for validation :
        $this->flash->answId = $answId;

        $this->addCss('styles/module_quiz.css');

        $this->js->wysiwyg('#qf-q-content');
        $ppo             = new CopixPPO();
        $ppo->question  = $answerDatas;
        $ppo->resp      = $responsesDatas;
        $ppo->error     = $errorDatas;
        $ppo->id        = $answId;
        $ppo->quizName = $quizDatas['name'];
        $ppo->actionAnsw = ($modifAction == 'modif') ? $this->url('quiz|admin|updateAnsw') : $this->url('quiz|admin|newAnsw');
        $ppo->actionResp = ($modifAction == 'modif') ? $this->url('quiz|admin|updateAnsw') : $this->url('quiz|admin|newAnsw');

        $ppo->MENU = array(
                array( 'txt' => $this->i18n('quiz.admin.goBackToQuiz'),
                        'url' => $this->url('quiz|admin|modif'))
              );

        return _arPPO($ppo, 'admin.question.tpl');
    }

    public function processUpdateAnsw(){

        //get the flash infos :
        if(!isset($this->flash->answId))
            return $this->error('quiz.admin.noRight');

        $answId = $this->flash->answId;

        //check is the correct quiz :
        $quizId = $this->flash->quizId;

        $answIdIn = $this->request('aw-id');

        if($answId != $answIdIn)
            return $this->error('quiz.errors.badOperation');
        /*
         * BUILD FORM DATA ARRAY
         */
        $form['id'] = $answId;
        $form['name'] = $this->request('aw-name');
        $form['quiz_id'] = $quizId;
        $form['content'] = $this->request('aw-content');
        
        //build global flash 
        $this->flash->quizId = $quizId;
        $this->flash->answId = $answId;
        $this->flash->modifAction = 'modif';

        //check error :
        $valid = $this->service('QuizService')->validAnsw($form);
        if($valid[0] == false){
            $this->flash->error = true;
            $this->flash->errorMsg = $valid[1];
            $this->flash->answDatas = $form;
            return $this->go('quiz|admin|questions');
        }

        //update responses
        $this->service('QuizService')->updateAnsw($form);

        $this->flash->success = "ça marche";
        //return $this->go('quiz|admin|modif');
    }

    public function processNewAnsw(){

    }

    public function processUpdateResp(){

        //get the flash infos :
        if(!isset($this->flash->answId))
            return $this->error('quiz.admin.noRight');

        $answId = $this->flash->answId;

        //check is the correct quiz :
        $quizId = $this->flash->quizId;

        $answIdIn = $this->request('aw-id');

        if($answId != $answIdIn)
            return $this->error('quiz.errors.badOperation');
        /*
         * BUILD FORM DATA ARRAY
         */
        //get the input 
        $form['id'] = $answId;
        $form['name'] = $this->request('aw-name');
        $form['id_quiz'] = $quizId;
        $form['content'] = $this->request('aw-content');

        //build global flash
        $this->flash->quizId = $quizId;
        $this->flash->answId = $answId;
        $this->flash->modifAction = 'modif';

        //check error :
        $valid = $this->service('QuizService')->validAnsw($form);
        if($valid[0] == false){
            $this->flash->error = true;
            $this->flash->errorMsg = $valid[1];
            $this->flash->answDatas = $form;
            return $this->go('quiz|admin|questions');
        }

        //update responses
        $this->service('QuizService')->updateAnsw($form);

        $this->flash->success = "ça marche";
        //return $this->go('quiz|admin|modif');
    }

    public function processResults(){
        $pId = CopixRequest::getInt('id', false);
        if(!$pId){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz|admin|')));
        }
        $quizData = _ioDAO('quiz_quiz')->get($pId);
        if($quizData == null || count($quizData) == 0){
             return CopixActionGroup::process('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('quiz.errors.noQuiz'), 'back'=>CopixUrl::get('quiz||')));
        }


        $responsesData = _ioDAO('quiz_responses')->getResponsesByQuiz($pId);
        $questionsData = _ioDAO('quiz_questions')->getQuestionsForQuiz($pId);
		
		
		
		/* ========================================
		PREPARE QUESTIONS DATA : ARRAY CREATION
		questions = [
			$i = [
				'choices' : { QUESTION's CHOICES }
				'id' = QUESTION ID
				'correct' = [CORRECT ANSWERE]
			]
		]
		==========================================*/
		$i=0;
        foreach($questionsData as $question){
            $choicesData = _ioDAO('quiz_choices')->getChoices($question->id);
			$listQuestions[] = $question->id;
            $questions[$i]['choices'] = $choicesData;
			$questions[$i]['id'] = $question->id;
            foreach($choicesData as $choice){
                if($choice->correct == 1)
                    $questions[$i]['correct'][] = (int)$choice->id;
            }
			$i++;
        }
		
		/*========================================
		PREPARE USERS INFOS : ARRAY CREATION
		users = [
			$i = [
				'name' = USER's NAME
				'surname' = USER's SURNAME
				'class' = USER's CLASS - IF NOT EXISTS - : NULL
				'school' = USER's SCHOOL - IF NOT EXISTS - : NULL
			]
		]
		listUsers = [
			$i = ID USER
		]
		=========================================*/
        $i = 0;
		$listUsers = array();
        foreach($responsesData as $response){
			if(!in_array($response->id_user, $listUsers)){
				//for no duplicate data
				$listUsers[$i] = $response->id_user;
				$user = Kernel::getUserInfo('ID', $response->id_user, array('link_data' => true));
				$users[$i]['id'] = $response->id_user;
				$users[$i]['name'] = $user['nom'];
				$users[$i]['surname'] = $user['prenom'];
				if(isset($user['link_data'])){
					//get first class id
					$class = key($user['link']->classe);
					$users[$i]['classe'] = $user['link_data']->classe[$class]['nom'];
					$users[$i]['school'] = $user['link_data']->classe[$class]['parent']['nom'];
				}else{
					$users[$i]['classe'] = null;
					$users[$i]['school'] = null;
				}
				$i++;
			}
			$responses[$response->id_user][$response->id_question][] = (int)$response->id_choice;
			$responses[$response->id_user]['date'][] = $response->date;
        }

		/*====================================
		MERGE ALL DATA : ARRAY INSERTION
		users +=
                        'goodresp' = COUNT GOOD QUESTIONS,
		=====================================*/
		foreach($users as $key => $user){
			$response = $responses[$user['id']];			
			$users[$key]['date'] = date("d/m H:i",max($response['date']));
                        $users[$key]['goodresp'] = 0;
                        $nbQuestions = 0;
			foreach($questions as $Qkey => $question){
                                $nbQuestions++;
				if(!isset($response[$question['id']])){
					$users[$key]['responses'][$Qkey] = 'no-resp';
					continue;
				}
				$localResponse = $response[$question['id']];
				$correct = array_diff($localResponse, $question['correct']);
				if(count($correct) == 0){
					$users[$key]['responses'][$Qkey] = 'correct';
                                        $users[$key]['goodresp']++;
				}else{
					$users[$key]['responses'][$Qkey] = 'resp';
				}
			}
                        if($users[$key]['goodresp'] < 10){
                            $users[$key]['goodresp'] = '0'.$users[$key]['goodresp'];
                        }
		}
		
                $this->addCss("styles/module_quiz.css");
		$this->addCss("styles/datatable.css");
                
		$this->addJs('js/datatable/jquery.dataTables.min.js');
		$this->addJs('js/datatable/TableTools.min.js');
		$this->addJs('js/datatable/ZeroClipboard.js');

		$ppo = new CopixPPO();
                $ppo->quiz = $quizData;
		$ppo->users = $users;
                $ppo->nbQuestions = $nbQuestions;
		$ppo->pathClip = CopixUrl::get().'js/datatable/';
                $ppo->MENU = array(
                        array( 'txt' => $this->i18n('quiz.admin.index'),
                            'url' => $this->url('quiz|admin|'))
                      );
		return _arPPO($ppo, 'admin.allresults.tpl');
    }

}
?>
