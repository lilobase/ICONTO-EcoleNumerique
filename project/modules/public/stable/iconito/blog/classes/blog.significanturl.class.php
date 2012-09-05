<?php
/**
* @package	copix
* @version	$Id: blog.significanturl.class.php,v 1.5 2006-10-09 16:21:31 cbeyer Exp $
* @author	Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class BlogSignificantUrl
{
    /**
    * parse
    *
    * Handle url decryption
    *
    * @param path          array of path element
    * @param parameters    array of parameters (eq $this-vars)
    * @param mode          urlsignificantmode : prepend / none
    * @return array([module]=>, [desc]=>, [action]=>, [parametre]=>)
    */
    public function parse ($path, $mode)
    {
        if ($mode!='prepend'){
            return false;
        }

        if (!(count($path) > 1 && $path[0]=='blog')) {
            return false;
        }

        $toReturn = array();
        $toReturn['module']  = 'blog';
        $toReturn['desc']    = 'default';
        $toReturn['blog']    = $path[1];

        if (isset($path[2]) && strlen($path[2]) > 0 ) {
            if ($path[2] == "page") {
                if (isset($path[3]) && strlen($path[3])>0) {
                    $toReturn['action']  = 'showPage';
                    $toReturn['page']    = $path[3];
                }else{
                    $toReturn['action']  = 'listPage';
                }
            }
            if (isset($path[3]) && $path[2] == 'article') {
                $toReturn['action']  = 'showArticle';
                $toReturn['article'] = $path[3];
            }else{
                $toReturn['cat'] = $path[2];
            }
        }
        return $toReturn;
    }

    /**
    * get
    *
    * Handle url encryption
    *
    * @param dest          array([module]=>, [desc]=>, [action]=>)
    * @param parameters    array of parameters (eq $this-vars)
    * @param mode          urlsignificantmode : prepend / none
    * @return object([path]=>, [vars]=>)
    */
    public function get ($dest, $parameters, $mode)
    {
        if ($mode=='none'){
            return false;
        }else{
            if ($dest['module'] == 'blog' && ($dest['desc'] == 'default' || $dest['desc'] == '') &&
            ($dest['action'] == '' || $dest['action'] == 'listArticle' || $dest['action'] == 'showArticle' || $dest['action'] == 'default'
            || $dest['action'] == 'listPage' || $dest['action'] == 'showPage' )) {
                if (!isset($parameters['blog'])) {
                    return false;
                }
                $toReturn->path = array('blog', $parameters['blog']);
                unset($parameters['blog']);

                if ($dest['action'] == 'showPage' || $dest['action'] == 'listPage') {
                    $toReturn->path[] = 'page';

                    if (isset($parameters['page']) && strlen($parameters['page'])> 0){
                        $toReturn->path[] = $parameters['page'];
                        unset($parameters['page']);
                    }
                }else{
                    if (isset($parameters['cat']) && strlen($parameters['cat'])> 0){
                        $toReturn->path[] = $parameters['cat'];
                        unset($parameters['cat']);
                    }

                    if (isset($parameters['article']) && strlen($parameters['article'])> 0){
                        $toReturn->path[] = 'article';
                        $toReturn->path[] = $parameters['article'];
                        unset($parameters['article']);
                    }
                }
                $toReturn->vars = $parameters;
                return $toReturn;
            }else{
                return null;
            }
        }
    }
}
