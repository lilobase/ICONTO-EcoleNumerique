<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: projectuser.class.php,v 1.3 2006-03-01 10:36:14 cbeyer Exp $
* @author	Croes Gérald, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * The user object we're gonna use as a default
 */
include (COPIX_AUTH_PATH.'CopixDBUser.class.php');
class ProjectUser extends CopixDBUser {
}
?>