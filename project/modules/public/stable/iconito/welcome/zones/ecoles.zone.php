<?php

/**
 * Affichage de la liste des ecoles
 *
 * @package Iconito
 * @subpackage Welcome
 */
class ZoneEcoles extends enicZone
{
    public function __construct()
    {
        parent::__construct();

        $this->defaultVille = null;
    }

    /**
     * Affiche la liste des ecoles
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/11/10
     * @param string $titre Titre a donner a la zone
     * @param integer $ajaxpopup 1 si on veut afficher le lien vers la fiche en Ajax, 0 pour afficher le lien Href classique. Par defaut : 0
     * @param integer $colonnes Nb de colonnes. Par defaut : 1
     * @param integer $grville Id du groupe de villes dans lequel on pioche les ecoles. Par defaut : 1
     * @param integer $ville Id de la ville dans laquelle on pioche les ecoles. Par defaut : null (prend le groupe de ville). Si on passe un grville et une ville, on prend la ville
     * @param string $groupBy Si regroupement. Peut valoir "type"
     * @param integer $dispType 1 pour afficher le type des ecoles, 0 pour n'afficher que leur nom. Par defaut : 1
     */
    public function _createContent(&$toReturn)
    {
        //params exclusion list
        $IdExclusionList = array();

        CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/module_fichesecoles.js');

        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');

        $titre = $this->getParam('titre');
        $ajaxpopup = $this->getParam('ajaxpopup', false);
        $colonnes = $this->getParam('colonnes');
        $colonnes = intval($colonnes);
        if (!$colonnes)
            $colonnes = 1;
        $grville = $this->getParam('grville', null);
        $ville = $this->getParam('ville', null);
        $search = $this->getParam('search', null);
        $pGroupBy = $this->getParam('groupBy');
        $pDispType = $this->getParam('dispType');
        $pDispFilter = ($this->getParam('dispFilter') === '') ? true : ($this->getParam('dispFilter')) ? true : false;
        $pDispHeader = $this->getParam('dispHeader', 1);

        if ($ville <= 0 && $ville_as_array = Kernel::getKernelLimits('ville_as_array')) {

            $list = array();
            if (!empty($search)) {
                $list = $annuaireService->searchEcolesByVilles($search, $ville_as_array);
            } else {
                foreach ($ville_as_array AS $ville_item) {
                    $list_tmp = $annuaireService->getEcolesInVille($ville_item);
                    $list = array_merge($list, $list_tmp);
                }
            }
        } else {

            //add default city :
            $ville = (empty($ville)) ? ((empty($this->defaultVille)) ? null : $this->defaultVille) : $ville;

            if (!empty($search))
                $list = $annuaireService->searchEcoles($search);
            elseif (!empty($ville) && $ville > 0)
                $list = $annuaireService->getEcolesInVille($ville);
            elseif (!empty($grville))
                $list = $annuaireService->getEcolesInGrville($grville);
            else
                $list = $annuaireService->getAllEcoles();

        }

        if ($pGroupBy == 'type') {
            usort($list, array($this, "usort_ecoles_type"));
        } elseif ($pGroupBy == 'ville') {
            usort($list, array($this, "usort_ecoles_ville"));
        } elseif ($pGroupBy == 'villeType') {
            $listByCityAndType = array();
            foreach ($list as $item) {
                if (!array_key_exists('ville', $item))
                    continue;

                if (in_array($item['id'], $IdExclusionList))
                    continue;

                $listByCityAndType[$item['ville_nom']][$item['type']][] = $item;
            }

            $listByCityAndTypeFinal = array();
            //order type
            foreach ($listByCityAndType as $k => $typeCollection) {
                if (array_key_exists('Elémentaire', $typeCollection))
                    $listByCityAndTypeFinal[$k]['Elémentaire'] = $typeCollection['Elémentaire'];
                if (array_key_exists('Primaire', $typeCollection))
                    $listByCityAndTypeFinal[$k]['Primaire'] = $typeCollection['Primaire'];
                if (array_key_exists('Maternelle', $typeCollection))
                    $listByCityAndTypeFinal[$k]['Maternelle'] = $typeCollection['Maternelle'];
                if (array_key_exists('Privée', $typeCollection))
                    $listByCityAndTypeFinal[$k]['Privée'] = $typeCollection['Privée'];
            }
            $list = $listByCityAndTypeFinal;
        }

        //kernel::myDebug($list);

        $nbEcoles = 0;
        foreach ($list as $k => $ecole) {
            if ($ecole['id'] > 0) {
                $nbEcoles++;
            }
        }

        //kernel::myDebug($list);
        // Nb elements par colonnes
        $parCols = ceil($nbEcoles / $colonnes);


        if (($ville_as_array = Kernel::getKernelLimits('ville_as_array')) && is_array($ville_as_array) && count($ville_as_array) > 0) {
            $listVille = $this->db->query('SELECT * FROM kernel_bu_ville WHERE id_vi IN (' . implode(',', $ville_as_array) . ') ORDER BY canon')->toArray();
            $displayVille = (count($listVille) > 1) ? true : false;
        } else {
            $listVille = $this->db->query('SELECT * FROM kernel_bu_ville ORDER BY canon')->toArray();
            $displayVille = (count($listVille) > 1) ? true : false;
        }

        $tpl = new CopixTpl ();
        $tpl->assign('titre', $titre);
        $tpl->assign('ajaxpopup', $ajaxpopup);
        $tpl->assign('list', $list);
        $tpl->assign('parCols', $parCols);
        $tpl->assign('widthColonne', round(100 / $colonnes, 1) . '%');
        $tpl->assign('displayVille', $displayVille);
        $tpl->assign('villes', $listVille);
        $tpl->assign('defaultVille', $ville);
        $tpl->assign('groupBy', $pGroupBy);
        $tpl->assign('dispType', $pDispType);
        $tpl->assign('dispFilter', $pDispFilter);
        $tpl->assign('dispHeader', $pDispHeader);
        $searchInputValue = (empty($search)) ? $this->i18n('welcome.ecoles.search') : $search;
        $tpl->assign('searchInputValue', $searchInputValue);

        $toReturn = $tpl->fetch('zone_ecoles.tpl');

        return true;
    }

    // Tri des ecoles selon leur type - CB 20/10/2009
    public function usort_ecoles_type($a, $b)
    {
        if ($a['type'] == $b['type']) {
            if ($a['nom'] == $b['nom'])
                return 0;
            return strcmp($a['nom'], $b['nom']);
        }
        return strcmp($a['type'], $b['type']);
    }

    // Tri des ecoles selon leur type - CB 20/10/2009
    public function usort_ecoles_ville($a, $b)
    {
        if ($a['ville_nom'] == $b['ville_nom']) {
            if ($a['nom'] == $b['nom'])
                return strcmp($a['type'], $b['type']);
            return strcmp($a["nom"], $b["nom"]);
        }
        return strcmp($a["ville_nom"], $b["ville_nom"]);
    }

}

