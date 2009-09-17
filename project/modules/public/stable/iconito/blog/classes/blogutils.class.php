<?php
/**
* @package	copix
* @version	$Id: blogutils.class.php,v 1.15 2007-06-01 16:08:43 cbeyer Exp $
* @author	Cédric VALLAT, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Class de gestion des droits utilisateur
 */

//require_once (COPIX_UTILS_PATH.'CopixUtils.lib.php');
_classInclude ('blog|blog.dao');
//require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'blog.dao.class.php');

function killBadUrlChars ($url) {
	$result = strtolower($url); 
	$result = killFrenchChars($result); 
	$result	 = strtr($result,'&~#"\'\\/{}[]`@%&:| .?!','_____________________');
	$result = eregi_replace("[^a-zA-Z0-9]", "_", $result);
	$result = eregi_replace("_{2,}", "_", $result);

	// On ne peut pas commencer ni finir par autre chose qu'un chiffre ou une lettre
	while (!ereg("^([A-Za-z0-9])$", substr($result,0,1), $regs)) {
		$result = substr($result,1);
	}
	while (!ereg("^([A-Za-z0-9])$", substr($result,-1,1), $regs)) {
		$result = substr($result,0,strlen($result)-1);
	}

	return $result;
}

function timeToBD($time) {
	$result = '';
	if(strlen($time)==5) $result = substr($time, 0, 2).substr($time, 3, 2);
	return $result;
}

function BDToTime($time) {
	$result = '';
	if(strlen($time)==4) $result = substr($time, 0, 2).':'.substr($time, 2, 2);
	return $result;
}

function BDToDate($date) {
	$result = '';
	if(strlen($date)==8) $result = substr($date, 6, 2).'/'.substr($date, 4, 2).'/'.substr($date, 0, 4);
	return $result;
}


function BDToDateTime($date, $time, $format) {
	$result = '0000-00-00 00:00:00';
	if(strlen($date)==8 && strlen($time)==4) {
		switch ($format) {
			case "mysql" :
				$result = substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2);
				$result .= ' ';
				$result .= substr($time, 0, 2).':'.substr($time, 2, 2).':00';
		}
	}
	return $result;
}


	/**
    * Initialise un tableau avec toutes les fonctions d'un blog.
    */
	function returnAllBlogFunctions() {
		$results = array();
		$arMaj = array ('article_bfct', 'archive_bfct', 'find_bfct', 'link_bfct', 'rss_bfct', 'photo_bfct', 'option_bfct');
		foreach ($arMaj as $var){
			$function->value  = $var;
			$function->text = 'blog|dao.blogfunctions.fields.'.$var;
			$function->selected = 0;
			array_push($results, $function);
		}
		//print_r($results);
		return $results;
	}


	/**
	 * Retourne le blog d'un noeud (personne, école, classe...)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/16
	 * @param string $parent_type Type du parent (club, classe...)
	 * @param string $parent_id Id du parent
	 * @return mixed NULL si pas de blog, le recordset sinon
	 */
	function getNodeBlog ($parent_type, $parent_id) {
		$blog = NULL;
		$hisModules = Kernel::getModEnabled ($parent_type, $parent_id);
		foreach ($hisModules as $node) {
			//print_r($node);
			if ($blog)
				break;
			if ($node->module_type == 'MOD_BLOG') {
				$dao = _dao("blog|blog");
				$blog = $dao->get($node->module_id);
			}
		}
		return $blog;
	}

	/**
	 * Crée un objet de type BLOG à partir d'un ID
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/06/01
	 * @param integer $id_blog Id du blog
	 * @return object Objet
	 */
	function create_blog_object ($id_blog) {
		$blog = new DAOBlog();
		$blog->id_blog = $id_blog;
		return $blog;
	}


?>
