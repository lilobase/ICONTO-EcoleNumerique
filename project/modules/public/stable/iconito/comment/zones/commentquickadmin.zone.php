<?php
/**
* @package	copix
* @subpackage comment
* @version	$Id: commentquickadmin.zone.php,v 1.1 2005-12-19 15:32:53 fmossmann Exp $
* @author	Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* shows all comment order by date.
*/
require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneCommentQuickAdmin extends CopixZone {
    function _createContent (& $toReturn) {
        $tpl = & new CopixTpl ();

        $dao = & _dao ('comment|comment');
        $sp  = & _daoSearchConditions ();
        $sp->addItemOrder ('date_cmt', 'desc');
        $sp->addItemOrder ('position_cmt', 'desc');
        $arComments = array();
        $arComments = $dao->findby($sp);
        if (count($arComments)>0) {
            $perPage = intval(CopixConfig::get('comment|quickAdminPerPage'));
            $params  = Array(
               'perPage'    => $perPage,
               'delta'      => 5,
               'recordSet'  => $arComments,
               'template'   => '|pager.tpl'
            );
            $pager = CopixPager::Load($params);
            $tpl->assign ('pager'    , $pager->GetMultipage());
            $tpl->assign ('comments' , $pager->data);
        }

        $toReturn = $lastComments === array () ? '' : $tpl->fetch ('comment.quickadmin.tpl');

        return true;
    }
}
?>
