<?php

class ZoneBlogs extends CopixZone
{
  /**
  * Affiche le bouton menant aux publications des ecoles
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2010/09/15
  */
  public function _createContent (&$toReturn)
  {
    $ppo = new CopixPPO ();
    $toReturn = $this->_usePPO ($ppo, 'blogs.tpl');
    return true;
  }

}
