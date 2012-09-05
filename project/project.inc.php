<?php
/**
* @package		copix
* @subpackage	core
* @author		Croes Gérald, Jouanneau Laurent
* @copyright	2001-2006 CopixTeam
* @link			http://copix.org
* @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once(dirname (__FILE__).'/project.path.inc.php');

/**
 * Contrôller du projet
 * @package		copix
 * @subpackage 	core
 */
class ProjectController extends CopixController
{
    /**
     * Actions communes à toutes les pages
     */
    public function _processStandard ($tplObject)
    {
        $tplVars = $tplObject->getTemplateVars ();

        if (! isset ($tplVars['TITLE_PAGE'])) {
            $tplVars['TITLE_PAGE'] = CopixConfig::get ('|titlePage');
            $tplObject->assign ('TITLE_PAGE', $tplVars['TITLE_PAGE']);
        }

        if (! isset ($tplVars['TITLE_BAR'])) {
            $tplVars['TITLE_BAR'] = str_replace ('{$TITLE_PAGE}', $tplVars['TITLE_PAGE'], CopixConfig::get ('|titleBar'));
            $tplObject->assign ('TITLE_BAR', $tplVars['TITLE_BAR']);
        }

        $tplObject->assign ('menuItems', array ('Accueil'=>_url ('default|default|default'),
                                                'Présentation'=>'http://www.copix.org/index.php/wiki/Presentation',
                                                'Tutoriaux'=>'http://www.copix.org/index.php/wiki/Tutoriaux',
                                                'Documentation'=>'http://www.copix.org/index.php/wiki/Documentation',
                                                'Forum'=>'http://forum.copix.org',
                                                'Téléchargement'=>'http://forum.copix.org',
                                                'Site officiel'=>'http://www.copix.org')
                            );

    }
}



// Compatibilite Copix 2.3 - CB 16/09/2009
//CopixActionReturn
define ('COPIX_AR_DISPLAY',1);//to display the given template into the default template.
define ('COPIX_AR_ERROR', 2);//to display an error message
define ('COPIX_AR_REDIRECT', 3);//to redirect to an url.
define ('COPIX_AR_REDIR_ACT', 4);
define ('COPIX_AR_STATIC', 5);//to display a static file
define ('COPIX_AR_NONE', 6);//you won't do anything
define ('COPIX_AR_DISPLAY_IN', 7);//display n a particular template
define ('COPIX_AR_DOWNLOAD', 8);//to download a file.
define ('COPIX_AR_BINARY', 9);//to generate images, pdf, ...
define ('COPIX_AR_DOWNLOAD_CONTENT', 10);//to download a file.
define ('COPIX_AR_BINARY_CONTENT', 11);//to generate images, pdf, ...
define ('COPIX_AR_XMLRPC',20);
define ('COPIX_AR_XMLRPC_FAULT',21);
define ('COPIX_AR_USER',50);

define ('PROFILE_CCV_NONE',     0);
define ('PROFILE_CCV_SHOW',     10);
define ('PROFILE_CCV_READ',     20);
define ('PROFILE_CCV_WRITE',    30);
define ('PROFILE_CCV_MEMBER',   35); // Iconito : Membre d'un groupe
define ('PROFILE_CCV_VALID',    40);
define ('PROFILE_CCV_PUBLISH',  50);
define ('PROFILE_CCV_MODERATE', 60);
define ('PROFILE_CCV_ADMIN',    70);


// Fin compatibilite

