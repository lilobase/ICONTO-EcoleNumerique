<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneAdminList extends enicZone{

    function _createContent (&$toReturn) {

        //get the active quiz liste
        $quizList = $this->service('QuizService')->getQuizByOwner($this->user->id);
        $action = $this->request('qaction', 'str');

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
?>
