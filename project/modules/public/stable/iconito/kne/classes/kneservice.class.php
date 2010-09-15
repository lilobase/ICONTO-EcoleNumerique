<?php

class kneService extends enicService {

    public function __construct() {
        parent::__construct();

        //add to url
        $this->hash = 'limoges';

        //webservice's url
        $this->url = 'http://www.kiosque-edu.com/FrontOffice/pages/LimogesAcces.aspx?login={login}&rne={rne}&sign={sign}';

        //ressources storage
        $this->folder = '';

        //activate KNE
        $this->active = TRUE;
    }

    public function testAccess() {
        return ($this->active && in_array($this->user->type, array('USER_ELE', 'USER_ENS', 'USER_DIR', 'USER_DID')));
    }

    public function createUrl($id) {
        $school = _dao('kernel|kernel_bu_ecole')->get($id);

        if (empty($school))
            return '';

        $sign = md5($this->user->login . $school->RNE . $this->hash);

        return str_replace(array('{login}', '{rne}', '{sign}'), array($this->user->login, $school['RNE'], $sign), $this->url);
    }

    function getRessources($id) {
        //connect to soap service
        $client = new SoapClient("http://www.kiosque-edu.com/knewebservice2/knews.asmx?WSDL");

        //get school infos (for RNE)
        $school = $this->db->query('SELECT * FROM kernel_bu_ecole WHERE numero = '.(int)$id)->toArray1();
        var_dump($school);
        if (empty($school))
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