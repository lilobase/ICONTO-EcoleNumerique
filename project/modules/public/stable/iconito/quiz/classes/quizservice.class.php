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
        return $this->db->query('SELECT * FROM module_quiz_questions WHERE id_quiz = '.$qId)->toArray();
    }
}
?>
