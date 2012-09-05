<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     login<br>
 * Date:     Feb 21, 2006
 * Purpose:  convertit un login utilisateur en login cliquable, pour afficher l'enveloppe (envoi minimail) ou le profil de l'utilisateur...
 * Input:<br>
 *         - login = Login de l'utilisateur
 * Example:  {$login|login}
 * @version  1.0
 * @author   Christophe Beyer <cbeyer at cap-tic dot fr>
 * @param string
 * @return string
 */
function smarty_modifier_login ($login)
{

    return $login.' <A TITLE="'.htmlentities(CopixI18N::get ('minimail|minimail.writeHim')).'" HREF="'.CopixUrl::get ('minimail||getNewForm', array('login'=>$login)).'"><IMG SRC="'._resource('img/minimail/new_minimail.gif').'" WIDTH="12" HEIGHT="9" BORDER="0" /></A>';




}

