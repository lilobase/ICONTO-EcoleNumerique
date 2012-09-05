<?php

/**
 * Sso - Zone "SsoAuthList"
 *
 * @package	Iconito
 * @subpackage  Sso
 * @version     $Id: ssoauthlist.zone.php,v 1.1 2006-11-14 15:21:50 cbeyer Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneSsoAuthList extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $tpl = new CopixTpl ();

        $auth_list = $this->getParam('list', NULL);
        $id = $this->getParam('id', NULL);

        $tpl->assign('auth_list', $auth_list);
        $tpl->assign('id', $id);


        $toReturn = $tpl->fetch ('ssoauthlist_zone.tpl');

        return true;

    }

}

