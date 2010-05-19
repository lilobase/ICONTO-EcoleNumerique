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
$start =  microtime_float();
        $this->matrix =& enic::get('matrix');
        $this->matrix->add('villes')->add('ville')->add('ecole')->add('classe');
$end =  microtime_float();
$time = $end-$start;
echo 'time is : '.$time;
        $this->matrix->debug();

    }

}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}