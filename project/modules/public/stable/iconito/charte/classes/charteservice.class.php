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
class CharteService {

    public function  __construct() {
        $this->user =& enic::get('user');
        $this->db   =& enic::get('model');
    }

    public function checkUserValidation(){
        return ($this->db->query('SELECT COUNT(id) FROM module_charte_users_validation WHERE user_id = '.$this->user->id)->toInt() == 0) ? false : true;
    }

    public function deleteUserValidation($iUserTypes){
        foreach($iUserTypes as $userType)
            $cond[] = 'user_type = '.$userType;
        
        $this->db->delete('module_charte_users_validation', implode(' AND ', $cond));
    }

    public function addUserValidation(){
        $datas['date'] = time();
        $datas['user_id'] = $this->user->id;
        $datas['charte_id'] = 1;
        $this->db->create('module_charte_users_validation', $datas);
    }

    public function getCurrentChartForUser(){
        return $this->db->query('SELECT * FROM module_charte_chartes WHERE user_type = '.$this->user->type)->toArray1();
    }

    public function addCharte($iUserType, $fileUrl, $iFileId, $iActive){
        $this->delCharte($iUserType);

        //secure :
        $url = $this->db->quote($fileUrl);
        $active = (empty($iActive)) ? 0 : 1;
        $fileId = (empty($iFileId)) ? 1 : $fileId*1;

        foreach($iUserTypes as $userType)
            $this->db->create('module_charte_users_validation', array('user_type' => $userType, 'file_url' => $url, 'file_id' => $fileId*1, 'active' => $iActive*1));
    }

    public function delCharte($iUserType){
        foreach($iUserType as $userType)
            $this->db->delete('module_charte_users_validation', 'user_type = '.$userType);
    }

    public function getChartesTypes(){
        $oReturn['all'] = $this->getChartesByTypes('USER_ALL');
        $oReturn['children'] = $this->getChartesByTypes('USER_ELE');
        $oReturn['adults'] = $this->getChartesByTypes('USER_RES');

        //hack for foreach
        $oReturn['all']['exist'] = 'data';
        $oReturn['children']['exist'] = 'data';
        $oReturn['adults']['exist'] = 'data';
        return $oReturn;
    }

    public function getChartesByTypes($iType){
        return $this->db->query('SELECT * FROM `module_charte_charte` WHERE user_type = "'.$iType.'"')->toArray1();
    }
}
?>
