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
        
        $author = Kernel::getUserInfo('ID', $QuizData->id_author, array('link_data' => true));
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
		
        CopixHTMLHeader::addCSSLink(_resource("styles/module_quiz.css"));
		CopixHTMLHeader::addCSSLink(_resource("styles/datatable.css"));
		CopixHtmlHeader::addJSLink('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/jquery.dataTables.min.js');
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/TableTools.min.js');
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/datatable/ZeroClipboard.js');

		$ppo = new CopixPPO();
                $ppo->quiz = $quizData;
		$ppo->users = $users;
                $ppo->nbQuestions = $nbQuestions;
		$ppo->pathClip = CopixUrl::get().'js/datatable/';
		return _arPPO($ppo, 'admin.allresults.tpl');
    }

}
?>
