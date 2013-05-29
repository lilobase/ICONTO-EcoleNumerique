<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {user} function plugin
 *
 * Type:     function<br>
 * Name:     user<br>
 * Date:     06/04/2006
 * Purpose:  Affiche une personne en rendant �ventuellement son nom cliquable pour d�tailler son profil, et �ventuellement l'enveloppe pour lui �crire un minimail.<br>
 * Input:<br>
 *         - label = nom � afficher (nom, nom+pr�nom, login...)
 *         - userType = (optional) type d'utilisateur. N�cessaire pour rendre le label cliquable et afficher son profil
 *         - userId = (optional) id d'utilisateur. N�cessaire pour rendre le label cliquable et afficher son profil
 *         - dispMail = (optional) 1 pour afficher l'enveloppe permettant d'�crire un minimail
 *         - login = (optional) login de l'utilisateur. N�cessaire pour afficher l'enveloppe
 *         - linkAttribs = (optional) attributs HTML de la balise A HREF entourant le label
 *				 - assign   = (optional) name of the template variable we'll assign
 *                      the output to instead of displaying it directly
 *				 - fromLogin = a partir du login, se charge d'afficher les infos du compte
 * Examples:
 * <pre>
 * {user label="Marc Dupond"}
 * {user label="Marc Dupond" userType="USER_ENS" userId=1}
 * {user label="Marc Dupond" userType="USER_ENS" userId=1 login="mdupond" dispMail=1}
 * {user label="Marc Dupond" userType="USER_ENS" userId=1 login="mdupond" dispMail=1 linkAttribs='CLASS="link"'}
 * {user fromLogin="mbraton"}
 * </pre>
 * @version  1.0
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @author   CAP-TIC
 * @param    array
 * @param    Smarty
 * @return   string
 */
function smarty_function_user ($params, &$smarty)
{

        if (isset($params['fromLogin'])) {
    } elseif (empty($params['label'])) {
        //$smarty->trigger_error("mailto: missing 'label' parameter");
        return;
    } else {
        $label = trim($params['label']);
    }

        if (trim($params['fromLogin'])) {
            $params['fromLogin'] = trim($params['fromLogin']);
            $userInfo = Kernel::getUserInfo ("LOGIN", $params['fromLogin']);
            if (count($userInfo)>0) {
                //var_dump($userInfo);
                $label = trim($userInfo['prenom'].' '.$userInfo['nom']);
                $params['userType'] = $userInfo['type'];
                $params['userId'] = $userInfo['id'];
                $params['login'] = $params['fromLogin'];
            } else
                $label = $params['fromLogin'];
        }


        if ($params['userType'] && $params['userId']) {
            // $res = '<A '.$params['linkAttribs'].' HREF="javascript:viewUser(\''.$params['userType'].'\', \''.$params['userId'].'\', \''.addslashes(htmlentities(CopixI18N::get ('annuaire|annuaire.profil.loading'))).'\');">'.$label.'</A>';
            $res = '<a '.$params['linkAttribs'].' class="viewuser" user_type="'.$params['userType'].'" user_id="'.$params['userId'].'">'.$label.'</a>';
        } else
            $res = $label;

        _classInclude('minimail|MinimailService');
        if ($params['dispMail']==1 && $params['login'] && MinimailService::hasUserAccess()) {

            $url = CopixUrl::get ('minimail||getNewForm', array('login'=>$params['login']));
            $res .= '&nbsp;<A HREF="'.$url.'"><IMG WIDTH="12" HEIGHT="9" SRC="'.CopixUrl::getResource ("img/minimail/new_minimail.gif").'" ALT="'.htmlentities(CopixI18N::get ('annuaire|annuaire.writeMinimail')).'" TITLE="'.htmlentities(CopixI18N::get ('annuaire|annuaire.writeMinimail')).'" BORDER="0" /></A>';
        }

        if (isset ($params['assign'])) {
      $smarty->assign($params['assign'], $res);
      return '';
       } else {
        return $res;
       }

}


