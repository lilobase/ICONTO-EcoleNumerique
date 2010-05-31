<?php
_classInclude('rightmatrix');
/**
 * RightMatrix - ActionGroup
 *
 * @package	Iconito
 * @subpackage  RightMatrix
 * @author      Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright   2010 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupRightmatrix extends enicActionGroup {

    public function beforeAction(){
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processDefault(){

        $this->matrix =& enic::get('matrix');

        echo $this->matrix->display();
    }

}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}