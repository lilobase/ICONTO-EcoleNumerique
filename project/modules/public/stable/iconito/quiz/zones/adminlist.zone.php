<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneAdminList extends enicZone
{
    public function _createContent (&$toReturn)
    {
        $id_gr_quiz = $this->session->load('id_gr_quiz');

        //get the active quiz liste
        $quizList = $this->service('QuizService')->getQuizByGroupe($id_gr_quiz);
        $action = $this->request('qaction', 'str');

        $this->js->confirm('.button-delete', 'quiz.confirm.delQuiz');

        //start tpl :
        $ppo = new CopixPPO();
        $ppo->quizList = $quizList;
        $ppo->action = $action;

        $tpl = new CopixTpl();
        $tpl->assign('ppo', $ppo);
        $toReturn = $tpl->fetch('zone.admin.list.tpl');

        return true;
    }

}
