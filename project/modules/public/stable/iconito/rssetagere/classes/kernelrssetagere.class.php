<?php

_classInclude('quiz|quizService');

class kernelQuiz {

    public function __construct(){
        $this->db =& enic::get('model');
    }

    public function create($infos = null){
        return 1;
    }

    public function delete($iId){
        
    }

    public function getStats(){

    }
}
?>
