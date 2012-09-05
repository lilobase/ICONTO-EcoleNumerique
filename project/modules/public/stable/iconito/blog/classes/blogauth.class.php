<?php
/**
* @package	copix
* @version	$Id: blogauth.class.php,v 1.11 2007-06-15 15:05:48 cbeyer Exp $
* @author	Cédric VALLAT, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Class de gestion des droits utilisateur
 */

_classInclude ('blog|blogoptions');

class user
{
    public $name;
    public $userId;
    public $email;
    public $web;
    public $isConnected;

    public function user()
    {
        if (Kernel::is_connected()) {
            $session = Kernel::getSessionBU ();
            $this->userId = $session['user_id'];
            $this->name = trim($session['prenom'].' '.$session['nom']);
            $this->email = '';
            $this->web = '';
            $this->isConnected = true;
        } else {
            $this->name = '';
            $this->userId = 0;
            $this->email = '';
            $this->web = '';
            $this->isConnected = false;
        }
    }

    public function isConnected()
    {
        /* ... */
        return $this->isConnected;
    }

    /*...*/
}


class BlogAuth
{
    /**
    * fonction getUserInfos
    * param :
    * return : Le prénom et le nom de l'utilisateur connecté
    */
    public function getUserInfos($id_blog=NULL)
    {
        $user = new user();
        if ($id_blog) {
            if (!_sessionGet ('cache|right|MOD_BLOG|'.$id_blog)) {
                _sessionSet ('cache|right|MOD_BLOG|'.$id_blog, Kernel::getLevel("MOD_BLOG", $id_blog));
            }
            $user->right = _sessionGet ('cache|right|MOD_BLOG|'.$id_blog);
        }
        return $user;
    }

    /**
    * fonction canComment
    * param : $id_blog = Id du blog
    * return : vrai si l'utilisateur a les droits de commenter les articles de ce blog, faut sinon
    */
    public function canComment($id_blog)
    {
        /* ... */
        return true;
    }




    /**
     * Gestion des droits dans un blog
     *
     * Teste si l'usager peut effectuer une certaine opération par rapport à son droit. Le droit sur le blog est calculé ou récupéré de la session dans la fonction
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/31
     * @param string $action Action pour laquelle on veut tester le droit
     * @param object $r L'objet sur lequel on teste le droit
     * @return bool true s'il a le droit d'effectuer l'action, false sinon
     */
    public function canMakeInBlog ($action, $r)
    {
        $can = false;
        if (!$r)
            return false;
        $userInfos = BlogAuth::getUserInfos ($r->id_blog);
        //print_r($userInfos);
        $droit = $userInfos->right;
        // Kernel::deb("action=$action / droit=$droit / privacy=".$r->privacy);
        switch ($action) {
            case "READ" :
                $can = ($droit >= $r->privacy);
                if( $r->privacy == 10 && Kernel::is_connected() ) $can = true;
                break;
            case "ACCESS_ADMIN" :
            case "ADMIN_ARTICLES" :
            case "ADMIN_PHOTOS" :
            case "ADMIN_DOCUMENTS" :
                $can = ($droit >= PROFILE_CCV_VALID);
                break;
            case "ADMIN_CATEGORIES" :
            case "ADMIN_COMMENTS" :
            case "ADMIN_LIENS" :
            case "ADMIN_PAGES" :
            case "ADMIN_RSS" :
            case "ADMIN_ARTICLE_MAKE_ONLINE" :
            case "ADMIN_ARTICLE_DELETE" :
                $can = ($droit >= PROFILE_CCV_MODERATE);
                //$can = false;
                break;
            case "ADMIN_OPTIONS" :
            case "ADMIN_DROITS" :
            case "ADMIN_STATS" :
                $can = ($droit >= PROFILE_CCV_ADMIN);
                break;
        }
        return $can;
    }

}
