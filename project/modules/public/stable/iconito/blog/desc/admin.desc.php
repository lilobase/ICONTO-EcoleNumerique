<?php
/**
* @package	copix
* @version	$Id: admin.desc.php,v 1.10 2007-06-04 14:39:50 cbeyer Exp $
* @author	C�dric VALLAT see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// Blog
$prepareEditBlog        = new CopixAction ('AdminBlog', 'doPrepareEditBlog');
$showBlog               = new CopixAction ('AdminBlog', 'processGetShowBlog');
$deleteBlog             = new CopixAction ('AdminBlog', 'doDeleteBlog');
$validBlog              = new CopixAction ('AdminBlog', 'doValidBlog');
$deleteLogoBlog         = new CopixAction ('AdminBlog', 'doDeleteLogoBlog');
$prepareEditBlogStyle   = new CopixAction ('AdminBlog', 'doPrepareEditBlogStyle');
$validBlogStyle         = new CopixAction ('AdminBlog', 'doValidBlogStyle');

// Articles
$prepareEditArticle   = new CopixAction ('AdminArticle', 'doPrepareEditArticle');
$validArticle         = new CopixAction ('AdminArticle', 'doValidArticle');
$validEditArticle     = new CopixAction ('AdminArticle', 'doValidEditArticle');
$deleteArticle        = new CopixAction ('AdminArticle', 'doDeleteArticle');
$showBlogArticle      = new CopixAction ('AdminArticle', 'showBlogArticle');

// Comment
$prepareEditComment   = new CopixAction ('AdminComment', 'doPrepareEditComment');
$listComment          = new CopixAction ('AdminComment', 'getListComment');
$deleteComment        = new CopixAction ('AdminComment', 'doDeleteComment');
$validComment         = new CopixAction ('AdminComment', 'doValidComment');
$validModifyComment   = new CopixAction ('AdminComment', 'doValidModifyComment');
$onlineComment     		= new CopixAction ('AdminComment', 'doOnlineComment');
$offlineComment     	= new CopixAction ('AdminComment', 'doOfflineComment');

// Pages
$prepareEditPage   = new CopixAction ('AdminPage', 'doPrepareEditPage');
$validPage         = new CopixAction ('AdminPage', 'doValidPage');
$validEditPage     = new CopixAction ('AdminPage', 'doValidEditPage');
$deletePage        = new CopixAction ('AdminPage', 'doDeletePage');
$upPage            = new CopixAction ('AdminPage', 'doPageUp');
$downPage          = new CopixAction ('AdminPage', 'doPageDown');

// Category
$prepareEditCategory   = new CopixAction ('AdminCategory', 'doPrepareEditCategory');
$validCategory         = new CopixAction ('AdminCategory', 'doValidCategory');
$deleteCategory        = new CopixAction ('AdminCategory', 'doDeleteCategory');
$upCategory            = new CopixAction ('AdminCategory', 'doCategoryUp');
$downCategory          = new CopixAction ('AdminCategory', 'doCategoryDown');

// Link
$prepareEditLink   = new CopixAction ('AdminLink', 'doPrepareEditLink');
$validLink         = new CopixAction ('AdminLink', 'doValidLink');
$deleteLink        = new CopixAction ('AdminLink', 'doDeleteLink');
$upLink            = new CopixAction ('AdminLink', 'doLinkUp');
$downLink          = new CopixAction ('AdminLink', 'doLinkDown');

// Flux RSS
$prepareEditRss   = new CopixAction ('AdminRss', 'doPrepareEditRss');
$validRss         = new CopixAction ('AdminRss', 'doValidRss');
$deleteRss        = new CopixAction ('AdminRss', 'doDeleteRss');
$upRss            = new CopixAction ('AdminRss', 'doRssUp');
$downRss          = new CopixAction ('AdminRss', 'doRssDown');

// Droits
$doSubscribe   = new CopixAction ('AdminBlog', 'doSubscribe');
$doUnsubscribe   = new CopixAction ('AdminBlog', 'doUnsubscribe');


//update URL
//mettre en commentaire une fois lanc�
$updateUrl          = new CopixAction ('AdminArticle', 'doUpdateUrl');

$default       = & $listBlog;
