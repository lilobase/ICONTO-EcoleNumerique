<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of quizserviceclass
 *
 * @author arnox
 */
class QuizService
{
    public $db;

    public function __construct()
    {
        //get connection to db
        $this->db =& enic::get('model');
    }

    public function start($id)
    {
        return true;
    }

    public function getHelp($iIdQuiz)
    {
        $id = $iIdQuiz*1;
        return $this->query('SELECT help FROM module_quiz_quiz WHERE id = '.$id)->toString();
    }

    public function getCurrentQuiz()
    {
        return $this->db->query('SELECT DISTINCT (quiz.id), quiz . *
                                    FROM module_quiz_quiz AS quiz
                                    INNER JOIN module_quiz_questions AS answ ON quiz.id = answ.id_quiz
                                            WHERE
                                            (`date_start` < '.time().' AND `date_end` > '.time().') OR
                                            (`date_start` = 0 OR `date_end` = 0) AND
                                            `lock` = 0
                                            ORDER BY date_end DESC')
                                    ->toArray();
    }

    public function getQuizByGroupe($idGroupe)
    {
        //secure $iIdAuthor
        $id = $idGroupe*1;

        //get all quiz with number of users as respond
        $oReturn = $this->db->query('
SELECT quiz .  *, COUNT(DISTINCT(resp.id_user)) AS numResp
FROM `module_quiz_quiz` AS quiz
LEFT JOIN module_quiz_questions AS question ON question.id_quiz = quiz.id
LEFT JOIN module_quiz_responses AS resp ON resp.id_question = question.id
WHERE quiz.gr_id = '.$id.'
GROUP BY quiz.id
                        ')->toArray();

        //format date and check lock from date
        foreach($oReturn as $k => $v){
            $oReturn[$k]['lock'] = ($v['lock'] != 1
                                        && (($v['date_start'] > time() || $v['date_start'] == 0)
                                        && ( $v['date_end'] < time() || $v['date_end'] == 0))
                                    )  ? 0 : 1;
            $oReturn[$k]['date_start'] = $this->timeToDate($v['date_start'], true);
            $oReturn[$k]['date_end'] = $this->timeToDate($v['date_end'], true);
        }

        return $oReturn;
    }

    public function getQuizDatas($iQuizId)
    {
        //secure $iQuizId
        $qId = $iQuizId*1;

        return $this->db->query('SELECT * FROM module_quiz_quiz WHERE id = '.$qId)->toArray1();
    }

    public function getQuestionsByQuiz($iQuizId)
    {
        //secure $iQuizId
        $qId = $iQuizId*1;
        return $this->db->query('SELECT question .  * , COUNT( choice.id ) AS respNum
                                FROM module_quiz_questions AS question
                                LEFT JOIN module_quiz_choices AS choice ON question.id = choice.id_question
                                WHERE question.id_quiz = '.$qId.'
                                GROUP BY question.id
                                ORDER BY `order` ASC')->toArray();
    }

    public function dateToTime($iDate)
    {
        if($iDate == 0 || empty($iDate))
            return 0;

        //get the dd/mm/yyyy format
        $dateArray = explode('/', $iDate);

        //if missing argument
        if(count($dateArray) != 3)
            return false;

        return mktime(0, 0, 0, $dateArray[1], $dateArray[0], $dateArray[2]);

    }

    public function timeToDate($iTime, $separator = false)
    {
        if($iTime == 0)
            return ($separator) ? '-' : 0;

        return date('d/m/Y', $iTime);
    }

    public function updateForm($iDatas)
    {
        //protect datas
        $datas = $this->formatFormDatas($iDatas);

        //get & delete id
        $id = $datas['id'];
        unset($datas['id']);

        //build query
        $query = '';
        //detecte end of array
        $lastkey = end(array_keys($datas));
        foreach($datas as $key => $data){
            $query .= '`'.$key.'` = '.$data;

            if($key !== $lastkey)
                $query .= ', ';
        }

        //execute query
        $this->db->query('UPDATE module_quiz_quiz SET '.$query.' WHERE id = '.$id)->close();

        return true;
    }

    public function newForm($iDatas)
    {
        $datas = $this->formatFormDatas($iDatas);

        $this->db->create('module_quiz_quiz', $datas);

        return true;
    }

    public function formatFormDatas($iDatas)
    {
        //get user's object
        $user = enic::get('user');
        $session = enic::get('session');

        //protect datas :
        $form = $iDatas;
        $form['name'] = $this->db->quote($form['name']);
        $form['description'] = $this->db->quote($form['description']);
        $form['help']  = $this->db->quote($form['help']);
        $form['optshow'] = $this->db->quote($form['optshow']);
        $form['lock'] = $form['lock']*1;
        $form['id']  = $form['id']*1;
        $form['date_start'] = $form['date_start']*1;
        $form['date_end'] = $form['date_end']*1;

        //format datas
        $form['optshow'] = (empty($form['optshow'])) ? 'never' : $form['optshow'];
        $form['lock'] = (empty($form['lock'])) ? 0 : $form['lock'];

        //build final data's array with id information
        $datas['id'] = $form['id']*1;
        $datas['id_owner'] = $user->id*1;
        $datas['date_start'] = $form['date_start'];
        $datas['date_end'] = $form['date_end'];
        $datas['name'] = $form['name'];
        $datas['description'] = $form['description'];
        $datas['help'] = $form['help'];
        $datas['pic'] = 'null';
        $datas['opt_save'] = $this->db->quote('each');
        $datas['opt_show_results'] = $form['optshow'];
        $datas['lock'] = $form['lock'];
        $datas['gr_id'] = $session->load('id_gr_quiz');

        return $datas;
    }

    public function isOwner($iIdQuiz)
    {
        //get users infos :
        $user =& enic::get('user');

        //protect id
        $id = $iIdQuiz*1;
        $id_owner = $this->db->query('SELECT id_owner FROM module_quiz_quiz WHERE id = '.$id)->toInt();

        $idUser = $user->id;

        return ($idUser == $id_owner);
    }

    public function getAnswerDatas($iId)
    {
        //secure quiz Id
        $id = $iId*1;

        return $this->db->query('SELECT * FROM module_quiz_questions WHERE id = '.$id)->toArray1();

    }

    public function prepareAnsw($iDatas)
    {
        $oReturn['name'] = $this->db->quote($iDatas['name']);
        $oReturn['content'] = $this->db->quote($iDatas['content']);
        $oReturn['opt_type'] = '"choice"';
        $oReturn['id'] = (isset($iDatas['id'])) ? $iDatas['id']*1 : null;
        $oReturn['id_quiz'] = $iDatas['id_quiz']*1;
        $oReturn['order'] = 1;

        return $oReturn;
    }

    public function prepareResp($iDatas)
    {
        foreach($iDatas as $key => $datas){
            $oReturn[$key]['id'] = (isset($datas['id'])) ? $datas['id']*1 : null;
            $oReturn[$key]['id_question'] = $datas['id_question']*1;
            $oReturn[$key]['content'] = $this->db->quote($datas['content']);
            $oReturn[$key]['correct'] = (isset($datas['correct'])) ? $datas['correct']*1 : 0;
            $oReturn[$key]['order'] = $datas['order']*1;
        }

        return $oReturn;
    }

    public function validAnsw($iDatas)
    {
        $errors = array();

        if(empty($iDatas['name']))
            $errors['name'] = 'Un énoncé court est au moins nécessaire...';

        $oReturn[] = empty($errors);
        $oReturn[] = $errors;

        return $oReturn;
    }

    public function validResp($iDatas)
    {
        $errors = array();

        //check if almost one choice is the good response
        $isValid = false;

        //fetch all resp
        foreach($iDatas as $i => $datas){
            if(empty($datas['content']))
                $errors['resp']['content'] = 'L\'une de vos propositions est vide...';

            if($datas['correct'] == 1)
                $isValid = true;
        }

        if(!$isValid){
            $errors['resp']['correct'] = 'Vous devez choisir au moins une bonne réponse parmi vos propositions...';
        }

        $oReturn[] = empty($errors);
        $oReturn[] = $errors;

        return $oReturn;
    }

    public function delResp($iIdAnsw)
    {
        $id = $iIdAnsw;
        $this->db->delete('module_quiz_choices', 'id_question = '.$id);
    }

    public function delAnsw($iIdAnsw)
    {
        $this->db->delete('module_quiz_questions', (int)$iIdAnsw);
        $this->delResp($iIdAnsw);
    }

    public function updateAnsw($iDatas)
    {
        $datas = $this->prepareAnsw($iDatas);
        $this->db->update('module_quiz_questions', $datas);
    }

    public function newAnsw($iDatas)
    {
        $data = $this->prepareAnsw($iDatas);
        $this->db->create('module_quiz_questions', $data);
    }

    public function newResp($iDatas)
    {
        $datas = $this->prepareResp($iDatas);
        foreach($datas as $data){
            $this->db->create('module_quiz_choices', $data);
        }
        return true;
    }

    public function delQuiz($iIdQuiz)
    {
        $id = $iIdQuiz*1;

        $this->db->delete('module_quiz_quiz', $id);

        //delete answ
        $answ = $this->db->query('SELECT id FROM module_quiz_questions WHERE id_quiz = '.$id)->toArray();
        foreach($answ as $an){
            $this->delAnsw($an['id']);
        }

        return true;
    }

    public function existsQuiz($iId)
    {
        //security
        $id = $iId*1;

        return !is_null($this->db->query('SELECT id FROM module_quiz_quiz WHERE id = '.$id)->toString());
    }

    public function existsAnsw($iId)
    {
        //security
        $id = $iId*1;

        return !is_null($this->db->query('SELECT id FROM module_quiz_questions WHERE id = '.$id)->toString());
    }

    public function getQuestion($iQid)
    {
        return $this->db->query('SELECT * FROM module_quiz_questions WHERE id = '.$iQid)->toArray1();
    }

    public function getResponses($iQid, $iUid)
    {
        return $this->db->query('SELECT * FROM module_quiz_responses WHERE id_question = '.$iQid.' AND id_user = '.$iUid)->toArray();
    }

    public function getChoices($iQid)
    {
        return $this->db->query('SELECT * FROM module_quiz_choices WHERE id_question = '.$iQid.' ORDER BY `order`')->toArray();
    }

}
