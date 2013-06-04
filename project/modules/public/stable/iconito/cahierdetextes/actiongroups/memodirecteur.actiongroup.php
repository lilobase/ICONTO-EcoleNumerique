<?php

/**
 * Classe des actions pour la gestion des mémos pour les directeurs d'école
 */
class ActionGroupMemoDirecteur extends CopixActionGroup
{
    /**
     * Contrôle des droits avant toute action
     *
     * @param string $actionName le nom de l'action
     *
     * @return CopixActionReturn|mixed
     */
    public function beforeAction($actionName)
    {
        CopixHTMLHeader::addJSLink(_resource('js/iconito/module_cahierdetextes.js'));

        // Contrôle d'accès au module
        $myNode = CopixSession::get('myNode');
        if (!$myNode['type'] == 'BU_ECOLE' && !Kernel::hasRole(DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL, 'ecole', _request ('ecoleId', _request ('id')))) {

            return CopixActionGroup::process('genericTools|Messages::getError',
                array('message'=> CopixI18N::get('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
        }
    }

    /**
     * Voir la liste des mémos d'un directeur de classe
     *
     * @return CopixActionReturn
     */
    public function processVoir()
    {
        $ppo = new CopixPPO ();

        if (is_null($ppo->ecoleId = _request('ecoleId')) && is_null($ppo->ecoleId = _request('ecoleId'))) {

            return CopixActionGroup::process('generictools|Messages::getError',
                array('message' => CopixI18N::get('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
        }

        // Récupération des paramètres
        $ppo->jour = _request('jour', date('d'));
        $ppo->mois = _request('mois', date('m'));
        $ppo->annee = _request('annee', date('Y'));
        $ppo->msgSuccess = _request('msgSuccess', false);
        $ppo->eleve = _request('eleve', null);

        $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);

        $memoDAO = _ioDAO('cahierdetextes|cahierdetextesmemo');
        $memos = $memoDAO->findByEcole($ppo->ecoleId);

        // Pager
        if (count($memos) > CopixConfig::get('cahierdetextes|nombre_max_memos')) {

            require_once (COPIX_UTILS_PATH . 'CopixPager.class.php');

            $params = array(
                'perPage' => intval(CopixConfig::get('cahierdetextes|nombre_max_memos')),
                'delta' => 5,
                'recordSet' => $memos,
                'template' => '|pager.tpl'
            );

            $pager = CopixPager::Load($params);
            $ppo->pager = $pager->GetMultipage();
            $ppo->memos = $pager->data;
        } else {

            $ppo->memos = $memos;
        }

        $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->ecoleId);
        $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, 'voir_memos_directeur.tpl');
    }

    /**
     * Création / Edition d'un mémo par un directeur de classe
     *
     * @return CopixActionReturn
     */
    public function processEditer()
    {
        _classInclude('kernel|kernel_bu_personnel_entite');
        $ppo = new CopixPPO ();
        if (is_null($ppo->cahierId = _request('cahierId', null))) {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                'back'    => CopixUrl::get('')
            ));
        } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {
            return CopixActionGroup::process('genericTools|Messages::getError', array(
                'message'=> CopixI18N::get('kernel|kernel.error.noRights'),
                'back'   => CopixUrl::get('')
            ));
        }
        // Récupération des paramètres
        $ppo->jour       = _request('jour', date('d'));
        $ppo->mois       = _request('mois', date('m'));
        $ppo->annee      = _request('annee', date('Y'));
        $ppo->msgSuccess = _request('msgSuccess', false);
        $ppo->dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
        $ppo->format           = CopixConfig::get('cahierdetextes|format_par_defaut');
        $cahierInfos           = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
        $ppo->nodeInfos        = array('type' => $cahierInfos[0]->module_type, 'id' => $cahierInfos[0]->module_id);
        if (is_null($memoId = _request('memoId', null))) {
            $ppo->memo = _record('cahierdetextes|cahierdetextesmemo');
        } else {
            $memoDAO   = _ioDAO('cahierdetextes|cahierdetextesmemo');
            $ppo->memo = $memoDAO->get($memoId);
            $ppo->memo->date_creation      = CopixDateTime::yyyymmddToDate($ppo->memo->date_creation);
            $ppo->memo->date_validite      = CopixDateTime::yyyymmddToDate($ppo->memo->date_validite);
            $ppo->memo->date_max_signature = CopixDateTime::yyyymmddToDate($ppo->memo->date_max_signature);
            // Récupération des élèves liés au mémo
            $memo2eleveDAO           = _ioDAO('cahierdetextes|cahierdetextesmemo2eleve');
            $ppo->classesSelectionnees = $memo2eleveDAO->findClassesParMemo($ppo->memo->id);
            // Récupération des fichiers liés au mémo
            $fichierMalleDAO    = _ioDAO('malle|malle_files');
            $fichierClasseurDAO = _ioDAO('classeur|classeurfichier');
            $memo2fichiersDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2files');
            $memo2fichiers    = $memo2fichiersDAO->retrieveByMemo($ppo->memo->id);
            $ppo->fichiers    = array();
            $fichiers         = array();
            foreach ($memo2fichiers as $memo2fichier) {
                if ($memo2fichier->module_file == 'MOD_MALLE') {
                    if ($fichier = $fichierMalleDAO->get($memo2fichier->file_id)) {
                        $ppo->fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier->nom);
                    }
                } elseif ($memo2fichier->module_file == 'MOD_CLASSEUR') {
                    if ($fichier = $fichierClasseurDAO->get($memo2fichier->file_id)) {
                        $ppo->fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier);
                    }
                }
            }
        }
        if (CopixRequest::isMethod('post')) {
            $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
            $ppo->memo->classe_id          = $cahierInfos[0]->node_id;
            $ppo->memo->date_creation      = CopixDateTime::dateToyyyymmdd(_request('memo_date_creation', null));
            $ppo->memo->date_validite      = CopixDateTime::dateToyyyymmdd(_request('memo_date_validite', null));
            $ppo->memo->message            = _request('memo_message', null);
            $ppo->memo->avec_signature     = _request('memo_avec_signature', 0);
            $ppo->memo->date_max_signature = CopixDateTime::dateToyyyymmdd(_request('memo_date_max_signature', null));
            $ppo->memo->supprime           = 0;
            $ppo->elevesSelectionnes       = _request('eleves', array());
            $ppo->fichiers                 = _request('memo_fichiers', array());
            // Traitement des erreurs
            $ppo->erreurs = array();
            if ($ppo->memo->date_creation == '') {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.noCreationDate');
            }
            if ($ppo->memo->date_validite == '') {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.noValidityDate');
            }
            if (
                !is_null($ppo->memo->date_validite)
                && ($ppo->memo->date_validite < $ppo->memo->date_creation)
            ) {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.wrongValidityDate');
            }
            if ($ppo->memo->message == '') {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.noContent');
            }
            if ($ppo->memo->avec_signature && $ppo->memo->date_max_signature == '') {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.noSignatureDate');
            }
            if (
                (
                    !is_null($ppo->memo->date_max_signature)
                    && !is_null($ppo->memo->date_validite)
                )
                && (
                    $ppo->memo->date_max_signature > $ppo->memo->date_validite
                    || $ppo->memo->date_max_signature < $ppo->memo->date_creation
                )
            ) {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.wrongMaxSignatureDate');
            }
            if (empty($ppo->elevesSelectionnes)) {
                $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.noStudents');
            }
            if (!empty($ppo->fichiers)) {
                $ppo->fichiers = array_unique($ppo->fichiers);
                // Récupération de l'identifiant de la malle du node
                $mods = Kernel::getModEnabled($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
                if ($malle = Kernel::filterModuleList($mods, 'MOD_MALLE')) {
                    $malleId = $malle[0]->module_id;
                }
                // Récupération des identifiants de classeur
                $classeurIds = array();
                // Classeur du node
                $mods = Kernel::getModEnabled($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
                if ($classeur = Kernel::filterModuleList($mods, 'MOD_CLASSEUR')) {
                    $classeurIds[] = $classeur[0]->module_id;
                }
                // Classeur personnel
                $mods = Kernel::getModEnabled(_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
                if ($classeur = Kernel::filterModuleList($mods, 'MOD_CLASSEUR')) {
                    $classeurIds[] = $classeur[0]->module_id;
                }
                // On détermine s'il s'agit de documents de la malle ou du classeur
                foreach ($ppo->fichiers as $fichierInfos) {
                    $fichierInfos = explode('-', $fichierInfos);
                    if ($fichierInfos[0] == 'MOD_MALLE') {
                        // Erreur : le fichier n'appartient pas à la malle du node
                        if (!$fichierMalleDAO->isFileOfMalle($fichierInfos[1], $malleId)) {
                            $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.invalidFile');
                            break;
                        } else {
                            $fichier    = $fichierMalleDAO->get($fichierInfos[1]);
                            $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier->nom);
                        }
                    } elseif ($fichierInfos[0] == 'MOD_CLASSEUR') {
                        $fichier = $fichierClasseurDAO->get($fichierInfos[1]);
                        // Erreur : le fichier n'appartient pas aux classeurs disponible à l'utilisateur
                        if (!in_array($fichier->classeur_id, $classeurIds)) {
                            $ppo->erreurs[] = CopixI18N::get('cahierdetextes|cahierdetextes.error.invalidFile');
                            break;
                        } else {
                            $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier);
                        }
                    }
                }
            }
            if (!empty ($ppo->erreurs)) {
                $ppo->memo->date_creation      = _request('memo_date_creation', null);
                $ppo->memo->date_validite      = _request('memo_date_validite', null);
                $ppo->memo->date_max_signature = _request('memo_date_max_signature', null);
                if (isset($fichiers)) {
                    $ppo->fichiers = $fichiers;
                }
                $modParentInfo   = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
                $ppo->TITLE_PAGE = $modParentInfo['nom'];

                return _arPPO($ppo, 'editer_memo.tpl');
            }
            $memoDAO         = _ioDAO('cahierdetextes|cahierdetextesmemo');
            $memo2eleveDAO   = _ioDAO('cahierdetextes|cahierdetextesmemo2eleve');
            $memo2fichierDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2files');
            // Création
            if ($ppo->memo->id == '') {
                $userInfos = Kernel::getUserInfo();
                // On défini le type de compte créateur
                $ppo->memo->created_by_role = $ppo->memo->created_by_role = DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL;
                // Insertion de l'enregistrement "memo"
                $memoDAO->insert($ppo->memo);
            } // Mise à jour
            else {
                // Mise à jour de l'enregistrement "memo"
                $memoDAO->update($ppo->memo);
                // Suppression des relations memo - eleves existantes
                $memo2eleveDAO->deleteByMemo($ppo->memo->id);
                // Suppression des relations memo - fichiers existantes
                $memo2fichierDAO->deleteByMemo($ppo->memo->id);
            }
            // Insertion des nouveaux liens memo > eleve
            foreach ($ppo->elevesSelectionnes as $eleveId) {
                $memo2eleve = _record('cahierdetextes|cahierdetextesmemo2eleve');
                $memo2eleve->memo_id  = $ppo->memo->id;
                $memo2eleve->eleve_id = $eleveId;
                $memo2eleveDAO->insert($memo2eleve);
            }
            // Insertion des liens "mémo > fichiers"
            if (!empty($fichiers)) {
                foreach ($fichiers as $fichier) {
                    $memo2fichier = _record('cahierdetextes|cahierdetextesmemo2files');
                    $memo2fichier->memo_id     = $ppo->memo->id;
                    $memo2fichier->module_file = $fichier['type'];
                    $memo2fichier->file_id     = $fichier['id'];
                    $memo2fichierDAO->insert($memo2fichier);
                }
            }

            return _arRedirect(CopixUrl::get('cahierdetextes|memodirecteur|voir', array(
                'cahierId'   => $ppo->cahierId,
                'msgSuccess' => CopixI18N::get('cahierdetextes|cahierdetextes.message.success')
            )));
        }
        $modParentInfo   = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
        $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO($ppo, 'editer_memo_directeur.tpl');
    }

    /**
     * Suppression d'un mémo par un directeur de classe
     *
     * @return CopixActionReturn
     */
    public function processSupprimer()
    {
        $ppo     = new CopixPPO ();
        $memoDAO = _ioDAO('cahierdetextes|cahierdetextesmemo');
        if (is_null($cahierId = _request('cahierId', null)) || !$memo = $memoDAO->get(_request('memoId', null))) {
            return CopixActionGroup::process('generictools|Messages::getError',
                array('message' => CopixI18N::get('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
        } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
            return CopixActionGroup::process('genericTools|Messages::getError',
                array('message'=> CopixI18N::get('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
        }
        // Suppression des relations mémo - eleves existantes
        $memo2eleveDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2eleve');
        $memo2eleveDAO->deleteByMemo($memo->id);
        // Suppression des relations mémo - fichiers existantes
        $memo2fichierDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2files');
        $memo2fichierDAO->deleteByMemo($memo->id);
        // Suppression du mémos
        $memoDAO->delete($memo->id);

        return _arRedirect(CopixUrl::get('cahierdetextes|memodirecteur|voir', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get('cahierdetextes|cahierdetextes.message.success'))));
    }
}
