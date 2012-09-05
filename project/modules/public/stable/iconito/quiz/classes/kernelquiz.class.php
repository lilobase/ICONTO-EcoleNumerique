<?php

_classInclude('quiz|quizService');

class kernelQuiz
{
    public function __construct()
    {
        $this->db =& enic::get('model');
    }

    public function create($infos = null)
    {
        $datas['title'] = ($infos['title']) ? '"'.$infos['title'].'"' : '"Quiz"';
        $this->db->create('module_quiz_groupes', $datas);
        return $this->db->lastId;
    }

    public function delete($iId)
    {
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

    public function getStats()
    {
        return '';
    }

    public function getStatsRoot()
    {
        $res = array();

        /*
         * Nombre de quizs
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_quiz_quiz';
        $a = _doQuery($sql);
        $res['nbQuizs'] = array('name' => CopixI18N::get('quiz|quiz.stats.nbQuizs', array($a[0]->nb)));

        /*
         * Nombre de questions
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_quiz_questions';
        $a = _doQuery($sql);
        $res['nbQuestions'] = array('name' => CopixI18N::get('quiz|quiz.stats.nbQuestions', array($a[0]->nb)));

        /*
         * Nombre de choix
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_quiz_choices';
        $a = _doQuery ($sql);
        $res['size'] = array ('name'=>CopixI18N::get ('quiz|quiz.stats.nbChoices', array($a[0]->nb)));

        /*
         * Nombre de réponses
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_quiz_responses';
        $a = _doQuery($sql);
        $res['nbReponses'] = array('name' => CopixI18N::get('quiz|quiz.stats.nbResponses', array($a[0]->nb)));

        return $res;
    }
}
