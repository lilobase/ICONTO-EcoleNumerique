<?php
/**
* @package	  copix
* @version	  $Id: blogarticle_blogarticlecategory.dao.class.php,v 1.6 2006-10-09 16:21:31 cbeyer Exp $
* @author	    Vallat Cédric see copix.aston.fr for other contributors.
* @copyright  2001-2005 CopixTeam
* @link       http://copix.org
* @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * handle the exemple
 */

class DAOBlogarticle_blogarticlecategory
{
    public function deleteAndInsert ($id_bact, $tabCategories)
    {
        if(count($tabCategories) > 0) {
            // Delete all data
            $sqlSwap1 = 'DELETE FROM module_blog_article_blogarticlecategory WHERE id_bact='.$id_bact;
            _doQuery($sqlSwap1);
            // insert new data
            foreach($tabCategories as $id_bacg) {
                $sqlSwap2 = 'INSERT INTO module_blog_article_blogarticlecategory (id_bact , id_bacg)VALUES (\''.$id_bact.'\', \''.$id_bacg.'\')';
                _doQuery($sqlSwap2);
            }
        }
    }


    public function findIdCategoryForArticle($id_bact)
    {
        $critere = ' SELECT DISTINCT artctg.id_bacg as id_bacg '.
        ' FROM module_blog_article as art LEFT JOIN module_blog_article_blogarticlecategory as artctg ON art.id_bact = artctg.id_bact'.
        ' WHERE art.id_bact = '.$id_bact;
        $res = _doQuery($critere);
        $resultat = array();
        foreach($res as $ctg) {
            array_push($resultat, $ctg->id_bacg);
        }
        return $resultat;
    }

}
