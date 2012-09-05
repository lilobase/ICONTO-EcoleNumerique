<?php


class DAOMinimail_to
{
    /**
     * Renvoie le nb de minimails recus et non lus
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/06/24
     * @param $iUser Id de l'utilisateur destinataire
     */
  public function getNbRecvUnread ($iUser)
  {
    $criteres = _daoSp ()
      ->addCondition  ('to_id', '=', $iUser)
      ->addCondition  ('is_deleted', '=', '0')
      ->addCondition  ('is_read', '=', '0')
      ;
    $oNb = _dao ('minimail|minimail_to')->countBy ($criteres);
    return $oNb;
  }

}

