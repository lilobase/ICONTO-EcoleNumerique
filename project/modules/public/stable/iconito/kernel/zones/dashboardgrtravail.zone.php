<?php
/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneDashboardGrTravail extends enicZone
{
    public function _createContent(&$toReturn)
    {
        //load TPL
        $tpl = new CopixTpl();

        //get the id for current (force int)
        $idZone = $this->getParam('idZone')*1;

        $descDatas = $this->model->query('SELECT description FROM module_groupe_groupe WHERE id = '.$idZone)->toString();

        //transform in UTF8
        $descDatas = utf8_encode($descDatas);

        $nbUsersInGroup = $this->model->query('SELECT COUNT(user_id) FROM kernel_link_user2node WHERE node_type=\'CLUB\' AND node_id = '.$idZone)->toInt();
        $tagsLink = $this->service('groupe|tagService')->createLinkForGroup($idZone);
        $tpl->assign('desc', $descDatas);
        $tpl->assign('nbUsers', $nbUsersInGroup);
        $tpl->assign('tags', $tagsLink);

        //return the html content
        $toReturn = $tpl->fetch ('zone.dashboard.grtravail.tpl');
        return true;
    }

}
