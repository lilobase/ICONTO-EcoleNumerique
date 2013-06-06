<?php

_classInclude ('cahierdetextes|baseMemoActionGroup');

/**
 * Classe des actions pour la gestion des mémos pour les directeurs d'école
 */
class ActionGroupMemoDirecteur extends BaseMemoActionGroup
{
    /**
     * Retourne le contexte dans lequel l'action est appelée
     *
     * @return string
     */
    protected function getMemoContext()
    {
        return 'ecole';
    }

    /**
     * Retourne la constante de role en fonction du contexte
     *
     * @return mixed
     */
    protected function getMemoRole()
    {
        _ioDAO('kernel|kernel_bu_personnel_entite');

        return DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL;
    }

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
        _ioDAO('kernel|kernel_bu_personnel_entite'); // Pour accéder aux constantes de roles
        if (!$myNode['type'] == 'BU_ECOLE' && !Kernel::hasRole(DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL, 'ecole', _request ('ecoleId', _request ('id')))) {
            return $this->redirectToNoRightsError();
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
        $ppo->memoContext = $this->getMemoContext();

        if (is_null($ppo->ecoleId = _request('ecoleId')) && is_null($ppo->ecoleId = _request('ecoleId'))) {
            return $this->redirectToErrorOccurred();
        }

        // Récupération des paramètres
        $ppo->jour = _request('jour', date('d'));
        $ppo->mois = _request('mois', date('m'));
        $ppo->annee = _request('annee', date('Y'));
        $ppo->msgSuccess = _request('msgSuccess', false);

        $memoDAO = _ioDAO('cahierdetextes|cahierdetextesmemo');
        $memos = $memoDAO->findByEcole($ppo->ecoleId);

        parent::paginateMemoList($ppo, $memos);

        $ppo->roleDirecteur = DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL;

        $nodeInfo = Kernel::getNodeInfo('BU_ECOLE', $ppo->ecoleId);
        $ppo->TITLE_PAGE = $nodeInfo['nom'];

        return _arPPO ($ppo, 'voir_memos.tpl');
    }

    /**
     * Création / Edition d'un mémo par un directeur de classe
     *
     * @return CopixActionReturn
     */
    public function processEditer()
    {
        $ppo = new CopixPPO ();
        $ppo->memoContext = $this->getMemoContext();

        if (is_null($ppo->ecoleId = _request('ecoleId', null))) {
            return $this->redirectToErrorOccurred();
        }

        // Récupération des paramètres
        $ppo->jour       = _request('jour', date('d'));
        $ppo->mois       = _request('mois', date('m'));
        $ppo->annee      = _request('annee', date('Y'));
        $ppo->msgSuccess = _request('msgSuccess', false);
        $ppo->dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
        $ppo->format           = CopixConfig::get('cahierdetextes|format_par_defaut');
        $nodeType              = 'BU_ECOLE';
        $nodeId                = $ppo->ecoleId;
        $ppo->nodeInfos        = array('type' => $nodeType, 'id' => $nodeId);

        // Set le titre
        $nodeInfo = Kernel::getNodeInfo($nodeType, $nodeId);
        $ppo->TITLE_PAGE = $nodeInfo['nom'];

        if (is_null($memoId = _request('memoId', null))) {
            $ppo->memo = _record('cahierdetextes|cahierdetextesmemo');
        } else {
            $memoDAO   = _ioDAO('cahierdetextes|cahierdetextesmemo');
            $ppo->memo = $memoDAO->get($memoId);

            // On contrôle que le mémo peut être edité à cette endroit
            if ($ppo->memo->created_by_role != $this->getMemoRole()) {
                return $this->redirectToNoRightsError();
            }

            $ppo->memo->date_creation      = CopixDateTime::yyyymmddToDate($ppo->memo->date_creation);
            $ppo->memo->date_validite      = CopixDateTime::yyyymmddToDate($ppo->memo->date_validite);
            $ppo->memo->date_max_signature = CopixDateTime::yyyymmddToDate($ppo->memo->date_max_signature);

            // Récupération de la classe à laquelle est liée le mémo
            $ppo->classesSelectionnees = array($ppo->memo->classe_id);

            // Récupération des fichiers liés au mémo
            _classInclude('cahierdetextes|cahierdetextesmemoservices');
            $ppo->fichiers = CahierDeTextesMemoServices::getFichiersForList($ppo->memo);
        }
        if (CopixRequest::isMethod('post')) {
            $ppo->memo->date_creation      = CopixDateTime::dateToyyyymmdd(_request('memo_date_creation', null));
            $ppo->memo->date_validite      = CopixDateTime::dateToyyyymmdd(_request('memo_date_validite', null));
            $ppo->memo->message            = _request('memo_message', null);
            $ppo->memo->avec_signature     = _request('memo_avec_signature', 0);
            $ppo->memo->date_max_signature = CopixDateTime::dateToyyyymmdd(_request('memo_date_max_signature', null));
            $ppo->memo->supprime           = 0;
            $ppo->classesSelectionnees     = _request('classes', array());
            $ppo->fichiers                 = _request('memo_fichiers', array());

            // Traitement des erreurs
            _classInclude('cahierdetextes|ecolememovalidator');

            $validator = new EcoleMemoValidator($ppo->memo, array(
                'classes'      => _request ('classes', array()),
                'fichiers'     => _request ('memo_fichiers', array()),
                'nodeType'     => $nodeType,
                'nodeId'       => $nodeId
            ));

            // Formulaire non valide
            if (!$validator->isValid()) {
                $ppo->memo->date_creation      = _request ('memo_date_creation', null);
                $ppo->memo->date_validite      = _request ('memo_date_validite', null);
                $ppo->memo->date_max_signature = _request ('memo_date_max_signature', null);
                $ppo->fichiers                 = $validator->getFichiers();
                $ppo->classesSelectionnees     = $validator->getOption('classes', array());

                $ppo->erreurs = $validator->getErrors();

                return _arPPO($ppo, 'editer_memo.tpl');
            }

            // Formulaire valide
            // Création
            if ($ppo->memo->id == '') {
                foreach ($validator->getOption('classes', array()) as $classeId) {
                    $ppo->memo->classe_id = $classeId;

                    $this->insertMemo($ppo->memo);
                    $this->makeLinksForMemo($ppo->memo, $validator->getFichiers());
                }
            } // Mise à jour
            else {
                $originalClasseId = $ppo->memo->classe_id;

                if (in_array($originalClasseId, $validator->getOption('classes', array()))) {
                    // La classe courante est toujours sélectionnée, on met à jour
                    $this->updateMemo($ppo->memo);
                    $this->makeLinksForMemo($ppo->memo, $validator->getFichiers());
                }
                else {
                    // Sinon on supprime
                    $this->deleteMemo($ppo->memo);
                }

                foreach ($validator->getOption('classes', array()) as $classeId) {
                    if ($classeId != $originalClasseId) {
                        $ppo->memo->classe_id = $classeId;

                        $this->insertMemo($ppo->memo);
                        $this->makeLinksForMemo($ppo->memo, $validator->getFichiers());
                    }
                }
            }

            return _arRedirect(CopixUrl::get('cahierdetextes|memodirecteur|voir', array(
                'ecoleId'   => $ppo->ecoleId,
                'msgSuccess' => CopixI18N::get('cahierdetextes|cahierdetextes.message.success')
            )));
        }

        return _arPPO($ppo, 'editer_memo.tpl');
    }

    /**
     * Ajout des liens aux élèves et aux fichiers pour le mémo
     *
     * @param $memo
     * @param array $fichiers
     */
    public function makeLinksForMemo($memo, $fichiers = array())
    {
        $memo2eleveDAO   = _ioDAO('cahierdetextes|cahierdetextesmemo2eleve');
        $memo2fichierDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2files');

        // Récupération des élèves de la classe
        $eleveDAO = _ioDAO ('kernel|kernel_bu_ele');
        $eleves = $eleveDAO->getStudentsByClass($memo->classe_id);
        foreach($eleves as $eleve) {
            $memo2eleve = _record ('cahierdetextes|cahierdetextesmemo2eleve');

            $memo2eleve->memo_id   = $memo->id;
            $memo2eleve->eleve_id  = $eleve->id;

            $memo2eleveDAO->insert($memo2eleve);
        }

        // Insertion des liens "mémo > fichiers"
        foreach ($fichiers as $fichier) {
            $memo2fichier = _record('cahierdetextes|cahierdetextesmemo2files');
            $memo2fichier->memo_id     = $memo->id;
            $memo2fichier->module_file = $fichier['type'];
            $memo2fichier->file_id     = $fichier['id'];
            $memo2fichierDAO->insert($memo2fichier);
        }
    }

    /**
     * Suppression d'un mémo par un directeur de classe
     *
     * @return CopixActionReturn
     */
    public function processSupprimer()
    {
        parent::supprimerMemo();

        return _arRedirect(CopixUrl::get('cahierdetextes|memodirecteur|voir', array(
            'ecoleId' => _request('ecoleId'),
            'msgSuccess' => CopixI18N::get('cahierdetextes|cahierdetextes.message.success')
        )));
    }

    /**
     * Affichage du suivi d'un mémo (élèves concernés & signatures) - * Enseignant *
     */
    public function processSuivi ()
    {
        return parent::suiviMemo();
    }
}
