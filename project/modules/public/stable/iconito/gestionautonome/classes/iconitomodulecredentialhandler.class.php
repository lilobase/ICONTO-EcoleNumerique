<?php

class IconitoModulecredentialhandler implements ICopixCredentialHandler
{

    /**
     * S'assure que l'utilisateur peut réaliser la chaine de droit donnée
     *
     * @param	 string		  $pString	La chaine de droit à tester
     * @param  CopixUser	$pUser		L'utilisateur dont on teste les droits
     * @return boolean
     */
    public function assert($pStringType, $pString, $pUser)
    {
        switch ($pStringType) {

            case 'module':
                return $this->_module($pString, $pUser);
            default:
                return null;
        }
    }

    /**
     * Test si l'utilisateur a le credential requis
     *
     * @param string name
     * @param string value
     * @param string group
     * @param string module
     */
    private function _hasCredential($name, $value, $group, $module = null)
    {
        $arValues = array(
            ':name' => $name,
            ':group' => $group,
            ':handler_group' => 'auth|dbgrouphandler'
        );

        $sql = 'SELECT * '
            .'FROM modulecredentials mc, dbgroup, modulecredentialsgroups mcg ';
        if ($value != '') {

            $sql .= ', modulecredentialsvalues mcv1, modulecredentialsvalues mcv2 ';
        }
        $sql .= 'WHERE mc.id_mc = mcg.id_mc '
            .'AND mc.name_mc = :name '
            .'AND mcg.id_group = dbgroup.id_dbgroup AND dbgroup.caption_dbgroup = :group '
            .'AND mcg.handler_group = :handler_group ';
        if ($value != '') {

            $sql .= 'AND mcv2.id_mcv = mcg.id_mcv '
                .'AND mcv1.id_mc = mc.id_mc '
                .'AND mcv1.value_mcv = :value '
                .'AND ((mcv2.level_mcv > mcv1.level_mcv AND mcv1.level_mcv IS NOT NULL) OR mcv2.value_mcv = mcv1.value_mcv)';

            $arValues[':value'] = $value;
        }

        if (!is_null($module)) {

            $sql .= ' AND mc.module_mc = :module';
            $arValues[':module'] = $module;
        } else {

            $sql .= ' AND mc.module_mc is null';
        }

        return (count(_doQuery($sql, $arValues)) > 0);
    }

    /**
     * Gestion du type module
     *
     * @param string    $pString	la chaine à tester
     * @param CopixUser $pUser	  l'utilisateur dont on teste les droits
     */
    private function _module($pString, $pUser)
    {
        $userGroups = $pUser->getGroups();

        $mapResourceTypeToRole = array(
            'classroom' => array('teacher'),
            'school' => array('teacher_school', 'principal', 'administration_staff', 'schools_group_animator', 'cities_group_animator'),
            'city' => array('city_agent'),
            'cities_group' => array('cities_group_agent'),
            '*' => array('teacher', 'teacher_school', 'principal', 'administration_staff', 'city_agent', 'cities_group_agent', 'schools_group_animator', 'cities_group_animator'),
        );

        // Teste de la ressource parente
        $module = substr($pString, strrpos($pString, '@') + 1);
        $credentialParams = explode('|', substr($pString, 0, strrpos($pString, '@')));

        // Si le formatage du droit n'est pas bon
        if (count($credentialParams) != 4 || !isset($mapResourceTypeToRole[$credentialParams[0]])) {
            return false;
        }

        // Pour chaque role intervenant dans la ressource, on test les droits
        foreach ($mapResourceTypeToRole[$credentialParams[0]] as $role) {

            // Si l'id de la ressource est précisée => alors recherche ciblée
            if ($credentialParams[1] != '') {

                if ($pUser->testCredential('group:'.$role.'_'.$credentialParams[1].'@gestionautonome|iconitogrouphandler')) {

                    if ($this->_hasCredential($credentialParams[2], $credentialParams[3], $role, $module)) {

                        return true;
                    }
                }
            } elseif (isset($userGroups['gestionautonome|iconitogrouphandler'])) {
                // Recherche générique
                foreach ($userGroups['gestionautonome|iconitogrouphandler'] as $key => $group) {

                    if (substr($key, 0, strrpos($key, '_')) == $role) {

                        if ($this->_hasCredential($credentialParams[2], $credentialParams[3], $role, $module)) {

                            return true;
                        }
                    }
                }
            }
        }

        // Récupération du parent
        if ($credentialParams[1] != '') {

            $credential = $credentialParams[2].'|'.$credentialParams[3].(!is_null($module) ? '@'.$module : '');
            switch ($credentialParams[0]) {

                case 'classroom':
                    $classroomDAO = _ioDAO('kernel|kernel_bu_ecole_classe');
                    if ($classroom = $classroomDAO->get($credentialParams[1])) {

                        return $this->_module('school|'.$classroom->ecole.'|'.$credential, $pUser);
                    }
                    break;
                case 'school':
                    $schoolDAO = _ioDAO('kernel|kernel_bu_ecole');
                    if ($school = $schoolDAO->get($credentialParams[1])) {

                        return $this->_module('city|'.$school->id_ville.'|'.$credential, $pUser);
                    }
                    break;
                case 'city':
                    $cityDAO = _ioDAO('kernel|kernel_bu_ville');
                    if ($city = $cityDAO->get($credentialParams[1])) {

                        return $this->_module('cities_group|'.$city->id_grville.'|'.$credential, $pUser);
                    }
                    break;
            }
        }
        return false;
    }

}