<?php
/**
 * Admin - Classes
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: cacheservices.class.php,v 1.3 2007-09-07 08:30:12 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|demo_tools');

class CacheServices
{
    /**
     * Renvoie la taille occupée par le cache (dossier /temp/cache)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/05
     * @return array Tableau avec la taille occupée par les dossiers (index: [folders])
     */
    public function getCacheSize ()
    {
        $folder = COPIX_TEMP_PATH.'cache';
        $folders = Demo_Tools::dirSize ($folder);

        return array('folders'=>$folders);
    }

    /**
     * Vide le cache
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/05
     */
    public function clearCache ()
    {
        // Les dossiers de temp
        $folder = COPIX_TEMP_PATH.'cache';
        Demo_Tools::dirempty ($folder);

        $folder = COPIX_TEMP_PATH.'modules/registry';
        Demo_Tools::dirempty ($folder);

        return true;
    }

    /**
     * Vide la table des configurations sauf exceptions
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2007/01/19
     */
    public function clearConfDB ()
    {
        $sauvegarde = array('kernel|jeuEssaiInstalled');
        $saved_data = array();

        reset( $sauvegarde );
        foreach( $sauvegarde AS $saved_key ) {
            if( CopixConfig::exists($saved_key) )
                $saved_data[$saved_key] = CopixConfig::get($saved_key);
        }

        $sql = "DELETE FROM copixconfig";
        _doQuery ($sql);

        foreach( $saved_data AS $saved_key=>$saved_val ) {
            CopixConfig::set($saved_key,$saved_val);
        }
    }

}

