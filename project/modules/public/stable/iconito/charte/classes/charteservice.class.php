<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of charteservice
 *
 * @author alemaire
 */
class CharteService
{
    public function  __construct()
    {
        $this->user =& enic::get('user');
        $this->db   =& enic::get('model');
    }

    public function checkUserValidation()
    {
        if($this->db->query('SELECT COUNT(id) FROM module_charte_users_validation WHERE user_id = '.$this->user->id)->count() != 0){ // id_copix !!!
            $_SESSION['chartValid'] = true;
            return  true;
        }elseif($this->db->query('SELECT COUNT(id) FROM module_charte_chartes WHERE user_type = "'.$this->user->type.'" AND active = 1')->count() != 0){
            return false;
        }elseif($this->db->query('SELECT COUNT(id) FROM module_charte_chartes WHERE user_type = "USER_ALL" AND active = 1')->count() != 0){
            return false;
        }else{
            $_SESSION['chartValid'] = true;
            return true;
        }
    }

    public function deleteUserValidation($iUserTypes)
    {
        foreach($iUserTypes as $userType)
            $cond[] = 'user_type = "'.$userType.'"';

        $this->db->delete('module_charte_users_validation', implode(' AND ', $cond));
    }

    public function addUserValidation($iUserType)
    {
        $datas['date'] = time();
        $datas['user_id'] = $this->user->id; // id_copix !!!
        $datas['charte_id'] = 1;
        $datas['user_type'] = '"'.$iUserType.'"';
        $this->db->create('module_charte_users_validation', $datas);
    }

    public function getCharte()
    {
        $charte = $this->db->query('SELECT * FROM module_charte_chartes WHERE user_type = "'.$this->user->type.'" AND active = 1')->toArray1();

        if(empty($charte)){
            $charte = $this->db->query('SELECT * FROM module_charte_chartes WHERE user_type = "USER_ALL" AND active = 1')->toArray1();
        }

        return $charte;
    }

    public function addCharte($iUserType, $fileUrl, $iFileId, $iActive)
    {
        $this->delCharte($iUserType);

        //secure :
        $url = $this->db->quote($fileUrl);
        $active = (empty($iActive)) ? 0 : 1;
        $fileId = (empty($iFileId)) ? 1 : $iFileId*1;

        foreach($iUserType as $userType)
            $this->db->create('module_charte_chartes', array('user_type' => '"'.$userType.'"', 'file_url' => $url, 'file_id' => $fileId*1, 'active' => $iActive*1));
    }

    public function delCharte($iUserType)
    {
        foreach($iUserType as $userType)
            $this->db->delete('module_charte_chartes', 'user_type = "'.$userType.'"');
    }

    public function getChartesTypes()
    {
        $oReturn['all'] = $this->getChartesByTypes('USER_ALL');
        $oReturn['children'] = $this->getChartesByTypes('USER_ELE');
        $oReturn['adults'] = $this->getChartesByTypes('USER_RES');

        $oReturn['all']['title'] = 'charte.user.all';
        $oReturn['children']['title'] = 'charte.user.children';
        $oReturn['adults']['title'] = 'charte.user.adults';
        $oReturn['all']['info'] = 'charte.user.all.info';
        $oReturn['children']['info'] = 'charte.user.children.info';
        $oReturn['adults']['info'] = 'charte.user.adults.info';


        return $oReturn;
    }

    public function getChartesByTypes($iType)
    {
        return $this->db->query('SELECT * FROM `module_charte_chartes` WHERE user_type = "'.$iType.'"')->toArray1();
    }
}
