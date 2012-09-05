<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneLienMinimail extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $cahierId  = $this->getParam('cahierId');
    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $cahierId);

    // Récupération des logins des enseignants de la classe
    if (isset($cahierInfos[0]) && $cahierInfos[0]->node_type == 'BU_CLASSE') {

      $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
        $enseignants = $personnelDAO->findTeachersByClassroomId ($cahierInfos[0]->node_id);
        $logins = array();
      foreach ($enseignants as $enseignant) {
        $logins[] = $enseignant->login_dbuser;
      }
    }

    $ppo->logins = implode(',', $logins);

      $toReturn = $this->_usePPO ($ppo, '_lien_minimail.tpl');
  }
}