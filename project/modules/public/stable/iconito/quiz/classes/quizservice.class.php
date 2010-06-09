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
    public function start($id){
        return true;
    }

    public function getCurrentQuiz(){
        $model =& enic::get('model');
        return $model->query('SELECT * FROM module_quiz_quiz WHERE
                                            (`date_start` < '.time().' AND `date_end` > '.time().') OR
                                            (`date_start` = 0 OR `date_end` = 0) AND
                                            `lock` = 0
                                            ORDER BY date_end DESC')
                                    ->toArray();
    }

    public function getQuizByOwner($iIdOwner){
        //secure $iIdAuthor
        $idOwner = $iIdOwner*1;

        //get the enic model
        $model =& enic::get('model');

        //get all quiz
        return $model->query('SELECT * FROM module_quiz_quiz WHERE id_owner = '.$idOwner)
                                    ->toArray();
    }
}
?>
