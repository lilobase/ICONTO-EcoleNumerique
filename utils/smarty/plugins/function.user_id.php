<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Affichage des infos d'une personne selon son ID utilisateur
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @since    2010/12/13
 * @param    integer  $id Id utilisateur (cf table dbuser)
 * @return   string
 */
function smarty_function_user_id ($params, &$smarty)
{

  if (!isset($params['id'])) {
    $smarty->trigger_error("mailto: missing 'id' parameter");
    return;
  }

  $res = '';

  if ($userInfo = Kernel::getUserInfo ("ID", $params['id'])) {
    $label = trim($userInfo['prenom'].' '.$userInfo['nom']);
    $res = '<a '.$params['linkAttribs'].' class="viewuser" user_type="'.$userInfo['type'].'" user_id="'.$userInfo['id'].'">'.$label.'</a>';
  }


  if (isset ($params['assign'])) {
    $smarty->assign($params['assign'], $res);
    return '';
  } else {
    return $res;
  }




}


