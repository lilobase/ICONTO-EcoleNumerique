<?php
/**
 * Magicmail - KernelClass
 *
 * @package	Iconito
 * @subpackage  Magicmail
 * @version     $Id: kernelmagicmail.class.php,v 1.4 2006-10-24 17:14:49 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


class KernelMagicmail
{
    public function create ()
    {
        $dao = _dao("magicmail|magicmail");
        $new = _record("magicmail|magicmail");
        $new->magicmail_login = '';
        $new->magicmail_domain = '';
        $dao->insert ($new);
        return $new->magicmail_id;
    }

    public function delete ($id_magicmail)
    {
    Kernel::unregisterModule("MOD_MAGICMAIL", $id_magicmail);
        return true;
    }

    public function getStats ($id_magicmail)
    {
        $dao = _dao("magicmail|magicmail");
        $magic_result = $dao->get($id_magicmail);

        $res = array();
        if($magic_result && trim($magic_result->magicmail_login)!='' && trim($magic_result->magicmail_domain)!='' ) {
            $mail = '<a href="mailto:'.$magic_result->magicmail_login.'@'.$magic_result->magicmail_domain.'">'.$magic_result->magicmail_login.'@'.$magic_result->magicmail_domain.'</a>';
            $res['email'] = array (
                'name'=>CopixI18N::get ('magicmail|magicmail.message.stats_yourmailis', array('mail'=>$mail)),
            );
        } else
            $res['email'] = array (
                'name'=>CopixI18N::get ('magicmail|magicmail.message.stats_nomail')
            );
        return $res;
    }

}

