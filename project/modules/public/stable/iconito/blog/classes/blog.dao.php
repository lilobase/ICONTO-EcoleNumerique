<?php
/**
* @package	copix
* @version	$Id: blog.dao.class.php,v 1.9 2006-10-09 16:21:31 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlog
{
    /**
    * get blog by name
    * @param  name
    * @return
    */
    public function getBlogByName ($url_blog)
    {
        $sp = _daoSp ();
        $sp->addCondition ('url_blog', '=', $url_blog);
    $arBlog = $this->findBy ($sp);
        if (count($arBlog) > 0)  {
            foreach ($arBlog as $blog)
        return $blog;
        } else {
            return false;
        }
    }


    /**
    * get blog by name
    * @param  name
    * @return
    */
    public function getBlogById ($id_blog)
    {
        $sp = _daoSp ();
        $sp->addCondition ('id_blog', '=', $id_blog);
    $arBlog = $this->findBy ($sp);
        if (count($arBlog) > 0)  {
            foreach ($arBlog as $blog)
        return $blog;
        } else {
            return false;
        }
    }


    /**
    * @param
    * delete
    * @return
    */
    public function delete ($id_blog)
    {
        // Delete item
        $sqlDelete = 'DELETE FROM module_blog WHERE id_blog=' . $id_blog;
        _doQuery($sqlDelete);

        // Delete item
        $sqlDelete = 'DELETE FROM module_blog_functions WHERE id_blog=' . $id_blog;
        _doQuery($sqlDelete);
    }
}


class DAORecordBlog
{
}
