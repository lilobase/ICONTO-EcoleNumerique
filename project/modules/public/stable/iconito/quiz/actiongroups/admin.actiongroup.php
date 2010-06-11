<?php
class ActionGroupAdmin extends enicActionGroup{

    public function beforeAction(){
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processIndex(){

        CopixHTMLHeader::addCSSLink (_resource("styles/module_quiz.css"));
        //start tpl :
        $ppo = new CopixPPO();
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
        //modif or new quiz
        $action = $this->request('qaction', 'str');
        $qId    = $this->request('id', 'int');

        //if the form submit
        $error = array();
        if($this->request('check') == 1){
            $form['title'] = $this->request('qf-title');
            if(empty($form['title']))
                $error['title'] = '<p class="ui-state-error" >'.$this->i18n('quiz.form.required').'</p>';

            $form['desc'] = $this->request('qf-description');
            $form['help'] = $this->request('qf-help');
            $form['dateStart'] = $this->request('qf-datestart');
            $form['dateEnd'] = $this->request('qf-dateend');
            $form['optShow'] = $this->request('qf-optshow');
            $form['lock'] = $this->request('qf-optshow');
        }

        $quizDatas = array();
        $questionsDatas = array();
        if((!empty($action) && $action=='modif') || !empty($qId)){
            $quizDatas = $this->service('QuizService')->getQuizDatas($qId);
            $questionsDatas = $this->service('QuizService')->getQuestionsByQuiz($qId);
            _dump($quizDatas);
            _dump($questionsDatas);
        }

        $this->js->wysiwyg('#qf-description');
        $this->js->wysiwyg('#qf-help');
        $this->js->date('.qf-date', 'full');

        $this->addCss('styles/module_quiz.css');

        
        $ppo = new CopixPPO();
        $ppo->quiz = $quizDatas[0];
        $ppo->questions = $questionsDatas;
        $ppo->MENU = array(
                        array( 'txt' => $this->i18n('quiz.admin.index'),
                                'url' => $this->url('quiz|admin|'))
                      );
        return _arPPO($ppo, 'admin.modif.tpl');
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
