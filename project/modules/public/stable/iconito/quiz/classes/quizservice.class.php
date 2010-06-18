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
class QuizService {

    public $db;

    public function __construct(){
        //get connection to db
        $this->db =& enic::get('model');
    }

    public function start($id){
        return true;
    }

    public function getCurrentQuiz(){
        
        return $this->db->query('SELECT * FROM module_quiz_quiz WHERE
                                            (`date_start` < '.time().' AND `date_end` > '.time().') OR
                                            (`date_start` = 0 OR `date_end` = 0) AND
                                            `lock` = 0
                                            ORDER BY date_end DESC')
                                    ->toArray();
    }

    public function getQuizByOwner($iIdOwner){
        //secure $iIdAuthor
        $idOwner = $iIdOwner*1;

        //get all quiz
        return $this->db->query('SELECT * FROM module_quiz_quiz WHERE id_owner = '.$idOwner)
                                    ->toArray();
    }

    public function getQuizDatas($iQuizId){
        //secure $iQuizId
        $qId = $iQuizId*1;

        return $this->db->query('SELECT * FROM module_quiz_quiz WHERE id = '.$qId)->toArray();
    }

    public function getQuestionsByQuiz($iQuizId){
        //secure $iQuizId
        $qId = $iQuizId*1;
        return $this->db->query('SELECT * FROM module_quiz_questions WHERE id_quiz = '.$qId.' ORDER BY `order` ASC')->toArray();
    }

    public function dateToTime($iDate){

        if($iDate == 0 || empty($iDate))
            return 0;

        //get the dd/mm/yyyy format
        $dateArray = explode('/', $iDate);

        //if missing argument
        if(count($dateArray) != 3)
            return false;

        return mktime(0, 0, 0, $dateArray[1], $dateArray[0], $dateArray[2]);

    }

    public function timeToDate($iTime){
        if($iTime == 0)
            return 0;

        return date('d/m/Y', $iTime);
    }

    public function updateForm($iDatas){
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

    public function newForm($iDatas){

    }

    public function formatFormDatas($iDatas){
        
        //get user's object
        $user = enic::get('user');

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

        return $datas;
    }

    public function isOwner($iIdQuiz){
        //get users infos :
        $user = enic::get('user');

        //protect id
        $id = $iIdQuiz*1;
        $id_owner = $this->db->query('SELECT id_owner FROM module_quiz_quiz WHERE id = '.$id)->toInt();

        return ($id == $id_owner);
    }
}
?>
