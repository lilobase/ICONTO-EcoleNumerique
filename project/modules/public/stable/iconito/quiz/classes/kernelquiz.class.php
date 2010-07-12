<?php

_classInclude('quiz|quizService');

class kernelQuiz {

    public function __construct(){
        $this->db =& enic::get('model');
    }

    public function create(& $infos = null){
        $datas['title'] = ($infos['title']) ? $infos['title'] : 'Quiz';
        $this->db->create('module_quiz_groupes', $datas);
        return $this->db->lastId;
    }

    public function delete($iId){
        $quizService = new QuizService();

        //secure id
        $id = $iId*1;
        //get liste of groupe quiz
        $quizs = $this->db->query('SELECT id FROM module_quiz_quiz WHERE gr_id = '.$id)->toArray();

        //fetch 'nd delete quiz
        foreach($quizs as $quiz){
            $quizService->delQuiz($quiz['id']);
        }
        
    }
}
?>
