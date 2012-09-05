<?php
/**
* @package	copix
* @version	$Id: blogoptions.class.php,v 1.5 2006-10-09 16:21:31 cbeyer Exp $
* @author	Cédric VALLAT, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Class de gestion des droits utilisateur
 */

class BlogOptions
{
    /**
    * fonction articleIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function articleIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->article_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

    /**
    * fonction articleIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function pageIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->article_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

    /**
    * fonction ArchiveIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function archiveIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->archive_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

    /**
    * fonction findIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function findIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->find_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

    /**
    * fonction linkIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function linkIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->link_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

    /**
    * fonction rssIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function rssIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->rss_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }


    /**
    * fonction photoIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function photoIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->photo_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }


    /**
    * fonction optionIsActive
    * param : Id du blog courant
    * return : vrai si authorisé à afficher
    */
    public function optionIsActive($id_blog)
    {
        $blogFunctionsDAO = _dao('blog|blogfunctions');
        $result = true;
        if( ($blogFunctions = $blogFunctionsDAO->get($id_blog)) && ($blogFunctions->option_bfct=='0') ) {
            $result = false;
        }
        return $result;
    }

}
