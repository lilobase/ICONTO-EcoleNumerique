<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: default.desc.php,v 1.11 2009-01-23 17:34:11 cbeyer Exp $
* @author	Croes Gérald, Julien Mercier, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

$listArticle  = new CopixAction ('FrontBlog', 'processGetListArticle');
$showArticle  = new CopixAction ('FrontBlog', 'getArticle');
$listPage     = new CopixAction ('FrontBlog', 'getListPage');
$showPage     = new CopixAction ('FrontBlog', 'getPage');
$showFluxRss  = new CopixAction ('FrontBlog', 'getFluxRss');
$listLink     = new CopixAction ('FrontBlog', 'getListLink');
$listCategory = new CopixAction ('FrontBlog', 'getListCategory');
$listArchive  = new CopixAction ('FrontBlog', 'getListArchive');
$validComment = new CopixAction ('FrontBlog', 'doValidComment');
$getBlogCss   = new CopixAction ('FrontBlog', 'getBlogCss');
$rss          = new CopixAction ('FrontBlog', 'getBlogRss');
$js           = new CopixAction ('FrontBlog', 'getBlogJs');
$jsPages      = new CopixAction ('FrontBlog', 'getBlogJsPages');
$logo					= new CopixAction ('FrontBlog', 'logo');

$go           = new CopixAction ('FrontBlog', 'go');

$default      = & $listArticle;


