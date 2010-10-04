<?php

class kneService extends enicService {

    public function __construct() {
        parent::__construct();

        //add to url
        $this->hash = ''; //hash

        //activate KNE
        $this->active = FALSE;
    }

    public function testAccess() {
        return ($this->active && in_array($this->user->type, array('USER_ELE', 'USER_ENS', 'USER_DIR', 'USER_DID')));
    }

    function getRessources($id) {

        if(!$this->testAccess())
            return '';

        //connect to soap service
        $client = new SoapClient("http://www.kiosque-edu.com/knewebservice2/knews.asmx?WSDL", array('exceptions' => FALSE));

        //get school infos (for RNE)
        $school = $this->db->query('SELECT * FROM kernel_bu_ecole WHERE numero = '.(int)$id)->toArray1();

        if (empty($school) || empty($school['RNE']))
            return '';

        $params->RNE = $school['RNE'];
        $params->Profil = utf8_encode(($this->user->type == 'USER_ELE') ? 'Elève' :
                                ($this->user->type == 'USER_DIR') ? 'Direction' :
                                        ($this->user->type == 'USER_ENS') ? 'Enseignant' :
                                                'Demo');

        //get all user parent's nodes (for classe)
        $userNode = Kernel::getNodeParents($this->user->type, $this->user->idEn);
        $classId = null;
        foreach ($userNode as $node) {
            if ($node['type'] == 'BU_CLASSE') {
                $classId = (int) $node['id'];
                break;
            }
        }

        if (empty($classId))
            return '';

        $params->Classe = $classId;
        $params->IDUser = $this->user->idEn . '@' . $params->RNE;
        $params->hash = md5($params->IDUser . $params->RNE . 'ENT');

        //get final result :
        $result = $client->AccesENT($params);

        if(is_a($result, 'SoapFault'))
            return 'confError';

        $ressources = array();

        if (is_object($result->AccesENTResult->InfoRessource)) {
            $ressources[] = $result->AccesENTResult->InfoRessource;
        } else {
            foreach ($result->AccesENTResult->InfoRessource AS $ressource) {
                $ressources[] = $ressource;
            }
        }

        return $ressources;
    }

}