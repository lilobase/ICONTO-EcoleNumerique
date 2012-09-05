<?php
/**
 * Admin - Zone
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: cachestatus.zone.php,v 1.1 2006-12-05 16:18:47 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|cacheservices');

class ZoneCacheStatus extends CopixZone
{
  public function _createContent (& $toReturn)
  {
        $tpl = new CopixTpl ();

    $size = CacheServices::getCacheSize ();
    // Cherche

        $tpl->assign ('size', $size);
        $toReturn = $tpl->fetch ('cache.status.tpl');
        return true;

  }


}
