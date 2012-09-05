<?php
/**
* @package  Iconito
* @subpackage Sso
* @version   $Id: kernelsso.class.php,v 1.1 2006-11-13 15:44:35 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class KernelSso
{
    public function create ()
    {
        $return = NULL;
        $dao = _dao("sso|sso");
        $new = _record("sso|sso");
        $new->sso_date_crea = date("Y-m-d H:i:s");
        $dao->insert ($new);
        if ($new->sso_id!==NULL) {
            $return = $new->sso_id;
        }
        return $return;
    }

    public function getStats ($id_sso)
    {
        $dao = _dao("sso|sso");
        $res = array();
        /*
        $infos = $dao->getSso($id_sso);
        $res['nbPhotos'] = array ('name'=>CopixI18N::get ('album|album.stats.nbPhotos'), 'value'=>$infos[0]->nb);
        */
        return $res;
    }

    public function delete ($id_sso)
    {
        /*
        $ssoService = & CopixClassesFactory::Create ('sso|sso');
        $ssoService->delSso( $id_sso );
        */
        return true;
    }

}

