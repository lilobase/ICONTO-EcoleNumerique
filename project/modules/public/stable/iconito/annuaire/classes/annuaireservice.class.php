<?php

/**
 * Fonctions diverses du module Annuaire
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class AnnuaireService extends enicService
{
    /**
     * Retourne les villes d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/23
     * @param integer $grville Id du groupe de villes
     * @param array $options Tableau d'options : [droit] pour n'avoir que les villes sur lesquelles on a ce droit
     * @return array Tableau avec les villes
     */
    public function getVillesInGrville ($grville, $options=array())
    {
        $villes = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
        } else {
            $getNodeInfo_full = true;
        }

        $matrix = & enic::get('matrixCache');

        $childs = Kernel::getNodeChilds ('BU_GRVILLE', $grville);
        foreach ($childs as $child) {
            if ($child['type']=='BU_VILLE') {

                if (isset($options['droit']) && $options['droit']) {
                    //Kernel::myDebug($options['droit']);
                    $droit = $matrix->ville($child['id'])->_right->count->$options['droit'];
                    if (!$droit) {
                        continue;
                    }
                }

                $node = Kernel::getNodeInfo ($child['type'], $child['id'], $getNodeInfo_full);
                //print_r($node);
                $villes[] = array('id'=>$child['id'], 'nom'=>$node['nom']);
            }
        }
        //print_r($villes);
        usort ($villes, array('AnnuaireService', 'usort_nom'));
        return $villes;
    }


    /**
     * Retourne une liste de villes selon leurs ID
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/09/25
     * @param array $pVilles Tableau des ID de ville (ID en valeur)
     * @param array $options Tableau d'options : [droit] pour n'avoir que les villes sur lesquelles on a ce droit
     * @return array Tableau avec les villes
     */
    public function getVilles ($pVilles, $options=array())
    {
        $villes = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
        } else {
            $getNodeInfo_full = true;
        }

        $matrix = & enic::get('matrixCache');

        foreach ($pVilles as $child) {

            if (isset($options['droit']) && $options['droit']) {
                //Kernel::myDebug($options['droit']);
                $droit = $matrix->ville($child)->_right->count->$options['droit'];
                if (!$droit) {
                    continue;
                }
            }

            $node = Kernel::getNodeInfo ('BU_VILLE', $child, $getNodeInfo_full);
            //$villes[] = array('id'=>$child['id'], 'nom'=>$node['nom']);
            $villes[] = array('id'=>$child, 'nom'=>$node['nom']);
        }
        //print_r($villes);
        usort ($villes, array('AnnuaireService', 'usort_nom'));
        return $villes;
    }

    /**
     * Retourne une liste de groupes de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/10/29
     * @param array $pVilles Tableau des ID de ville (ID en valeur), ou null si aucun filtre selon ces villes
     * @param array $options Tableau d'options : [droit] pour n'avoir que les groupes sur lesquels on a ce droit
     * @return array Tableau avec les groupes de villes
     */
    public function getGrVilles ($pVilles, $options=array())
    {
        $grvilles = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
        } else {
            $getNodeInfo_full = true;
        }

        $matrix = & enic::get('matrixCache');

        if ($pVilles) // On ne prend que les groupes rattachés à ces villes
        {
            $groupes = _ioDAO('kernel|kernel_bu_groupe_villes')->findByVilles($pVilles);
        }
        else
        {
            $groupes = _ioDAO('kernel|kernel_bu_groupe_villes')->findAllOrderByName();
        }


        foreach ($groupes as $child) {
            if (isset($options['droit']) && $options['droit']) {
                $droit = $matrix->grville($child->id_grv)->_right->count->$options['droit'];
                if (!$droit) {
                    continue;
                }
            }
            $node = Kernel::getNodeInfo ('BU_GRVILLE', $child->id_grv, $getNodeInfo_full);
            $grvilles[] = array('id'=>$child->id_grv, 'nom'=>$node['nom']);
        }

        //_dump($grvilles);
        //usort ($villes, array('AnnuaireService', 'usort_nom'));
        return $grvilles;
    }



    /**
     * Retourne les �coles d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/17
     * @param integer $ville Id de la ville
     * @param array $params (option) Options facultatives, [directeur] (true/false) indiquant, [droit] pour n'avoir que les ecoles sur lesquelles on a ce droit
     * @return array Tableau avec les �coles
     */
    public function getEcolesInVille ($ville, $params=array('directeur'=>true))
    {
        $ecoles = array();

        $matrix = & enic::get('matrixCache');

        $childs = Kernel::getNodeChilds ('BU_VILLE', $ville);
        foreach ($childs as $child) {
            if ($child['type']=='BU_ECOLE') {

                if (isset($params['droit']) && $params['droit']) {
                    $droit = $matrix->ecole($child['id'])->_right->count->$params['droit'];
                    //Kernel::myDebug("id=".$child['id']." / droit=".$droit);
                    if (!$droit) {
                        continue;
                    }
                }

                $node = Kernel::getNodeInfo ($child['type'], $child['id'], false);
                //kernel::myDebug($node);
                $ecoles[] = array('id'=>$child['id'], 'nom'=>$node['nom'], 'type'=>$node['ALL']->eco_type, 'web'=>$node['ALL']->eco_web, 'ville'=>$ville, 'ville_nom'=>$node['ALL']->vil_nom, 'directeur'=>((isset($params['directeur']) && $params['directeur']) ? AnnuaireService::getDirecteurInEcole($child['id']) : NULL));
            }
        }
        //print_r($ecoles);
        usort ($ecoles, array('AnnuaireService', 'usort_nom'));
        return $ecoles;
    }

        function searchEcoles($search, $villes = array())
        {
            //search by ville
            $cond = '';
            if(!empty($villes)){
                $cond .= 'AND ( e.id_ville = '.$villes[0];
                foreach ($villes as $k => $ville) {
                    if($k == 0)
                        continue;
                    $cond .= ' OR e.id_ville = '.(int)$ville;
                }
                $cond .= ')';
            }

            $query = ' WHERE e.nom LIKE "%'.addslashes($search).'%" '.$cond;

            $cond = $query.$cond;

            return $this->getAllEcoles($cond);
        }

        function getAllEcoles($cond = '')
        {
            $query = 'SELECT e.numero AS id, e.type AS type, e.nom AS nom, e.web AS web, e.id_ville AS ville, v.nom AS ville_nom FROM kernel_bu_ecole AS e JOIN kernel_bu_ville AS v ON e.id_ville = v.id_vi'.$cond;
            $ecolesList = $this->db->query($query)->toArray();
            foreach($ecolesList as $key => $ecole)
                $ecolesList[$key]['directeur'] = (isset($params['directeur']) && $params['directeur']) ? AnnuaireService::getDirecteurInEcole($ecole['id']) : NULL;

            //utf8 !
            foreach ($ecolesList as $key => $ecole)
                foreach($ecole as $keyi => $item)
                    $ecolesList[$key][$keyi] = utf8_encode ($item);

            usort ($ecolesList, array('AnnuaireService', 'usort_nom'));
            return $ecolesList;
        }

        function searchEcolesByVilles($search, $villes = array())
        {
            return $this->searchEcoles($search, $villes);
        }


    /**
     * Retourne les �coles d'un groupe de ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $grville Id du groupe de ville
     * @param array $options Options facultatives : [droit] pour n'avoir que les ecoles sur lesquelles on a ce droit
     * @return array Tableau avec les �coles
     */
    public function getEcolesInGrville ($grville, $options=array())
    {
        $ecoles = array();

        $childs = Kernel::getNodeChilds ('BU_GRVILLE', $grville);
        //print_r($childs);
        foreach ($childs as $child) {
            if ($child['type']=='BU_VILLE') {

                if ( ($ville_as_array = Kernel::getKernelLimits('ville_as_array')) && !in_array($child['id'],$ville_as_array))
                    continue;



                if (isset($options['droit']))
                    $tmp = AnnuaireService::getEcolesInVille ($child['id'], array('droit'=>$options['droit']));
                else
                    $tmp = AnnuaireService::getEcolesInVille ($child['id']);

                if (count($tmp)>0) {
          //kernel::myDebug($tmp);
                    $node = Kernel::getNodeInfo ($child['type'], $child['id'], false);
                    $ecoles[] = array('id'=>'0', 'nom'=>$node['nom']);
                    $ecoles = array_merge ($ecoles, $tmp);
                }

            }
        }
        //print_r($ecoles);
        return $ecoles;
    }


    /**
     * Retourne les classes d'une �cole, avec les infos des enseignants affect�s
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $ecole Id de l'�cole
     * @param array $options Tableau d'options. Implemente : [forceCanViewEns] force l'affichage des enseignants au lieu de regarder si l'usager a les droits [onlyWithBlog] ne renvoie que les classes ayant un blog [onlyWithBlog] ne renvoie que les classes ayant un blog [onlyWithBlogIsPublic] verifie que le blog est public (ou pas, selon valeur onlyWithBlogIsPublic) [enseignant] si on veut avoir l'enseignant de la classe (true par defaut) [withNiveaux] cherche les niveaux de chaque classe [annee] Force une annee scolaire [droit] pour n'avoir que les classes sur lesquelles on a ce droit
     * @return array Tableau avec les classes
     * @todo Voir pour remplacer le -1 par un ID d'un enseignant
     */
    public function getClassesInEcole ($ecole, $options=array())
    {
        $classes = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
        } else {
            $getNodeInfo_full = true;
        }

        $matrix = & enic::get('matrixCache');

        $getNodeChildsOptions = array();
        if (isset($options['annee']) && $options['annee'])
            $getNodeChildsOptions['annee'] = $options['annee'];

        $childs = Kernel::getNodeChilds ('BU_ECOLE', $ecole, $getNodeInfo_full, $getNodeChildsOptions);

//return($childs);
//echo "<pre>"; print_r($childs); die();

//return($childs);
        foreach ($childs as $child) {
            //print_r($child);
            if ($child['type']=='BU_CLASSE') {

                if (isset($options['droit']) && $options['droit']) {
                    $droit = $matrix->classe($child['id'])->_right->count->$options['droit'];
                    //Kernel::myDebug("id=".$child['id']." / droit=".$droit);
                    if (!$droit) {
                        continue;
                    }
                }

                $add = true;
                $node = Kernel::getNodeInfo ($child['type'], $child['id'], false);
                $classe = array(
                    'id' => $child['id'],
                    'nom' => $node['nom'],
                );

                // On cherche les enseignants
                if (!isset($options['enseignant']) || $options['enseignant']) {
                    if (isset($options['forceCanViewEns']))
                        $canViewEns = $options['forceCanViewEns']; // TODO verifier quand appelle et pertinence
                    else {
                        $droit = $matrix->classe($child['id'])->_right->USER_ENS->voir;
                        //Kernel::myDebug("id=".$child['id']." / droit=".$droit);
                        $canViewEns = ($droit);
                    }
                    //Kernel::deb ("canViewEns=$canViewEns");
                    if( !isset($options['no_enseignant']) || $options['no_enseignant']==0 )
                        $classe['enseignant'] = (($canViewEns) ? AnnuaireService::getEnseignantInClasse($child['id']) : NULL);
                }
                // On cherche seulement les classes avec blog
                if (isset($options['onlyWithBlog']) && $options['onlyWithBlog']) {

                    $getNodeBlogOptions = array();
                    if (isset($options['onlyWithBlogIsPublic']))
                        $getNodeBlogOptions['is_public'] = $options['onlyWithBlogIsPublic'];

                    if ($blog = getNodeBlog ('BU_CLASSE', $child['id'], $getNodeBlogOptions)) {
                        //var_dump($blog);
                        $classe['url_blog'] = $blog->url_blog;
                    } else
                        $add = false;
                }

                // Ajout eventuel des niveaux
                if (isset($options['withNiveaux']) && $options['withNiveaux']) {
                    //var_dump($child);
                    $classe['niveaux'] = $child['ALL']->getNiveaux();
                }


                if ($add)
                    $classes[] = $classe;
            }
        }
// $start = microtime(true);
// echo "&gt;&gt; foreach (childs) ".(microtime(true)-$start)."<br />";
        return $classes;
    }

    /**
     * Retourne les classes d'une ville, avec les infos des enseignants affect�s
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $ville Id de la ville
     * @param array $options Tableau d'options. [droit] pour n'avoir que les classes sur lesquelles on a ce droit
     * @return array Tableau avec les classes
     */
    public function getClassesInVille ($ville, $options=array())
    {
        $classes = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
            $getClassesInEcole_params = array('no_enseignant'=>1, 'getNodeInfo_light'=>true);
        } else {
            $getNodeInfo_full = true;
            $getClassesInEcole_params = array();
        }

        $matrix = & enic::get('matrixCache');

        if (isset($options['droit'])) {
            $ecoles = AnnuaireService::getEcolesInVille ($ville, array('droit'=>$options['droit']));
            $getClassesInEcole_params['droit'] = $options['droit'];
        } else
            $ecoles = AnnuaireService::getEcolesInVille ($ville);

//echo "&gt; getEcolesInVille ".(microtime(true)-$start)."<br />";
//$start = microtime(true);
        foreach ($ecoles as $ecole) {

            $tmp = AnnuaireService::getClassesInEcole ($ecole['id'], $getClassesInEcole_params);

            if (count($tmp)>0) {
                $nom = $ecole['nom'];
                if (isset($ecole['type']) && $ecole['type'])
                    $nom .= ' ('.$ecole['type'].')';
                $classes[] = array('id'=>'0', 'nom'=>$nom);
                $classes = array_merge ($classes, $tmp);
            }

        }
//echo "&gt; getClassesInEcole ".(microtime(true)-$start)."<br />";
        return $classes;
    }


    /**
     * Retourne les classes d'un groupe de villes, avec les infos des enseignants affect�s
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $grville Id du groupe de villes
     * @param array $options Tableau d'options. [droit] pour n'avoir que les classes sur lesquelles on a ce droit
     * @return array Tableau avec les classes
     */
    public function getClassesInGrville ($grville, $options=array())
    {
        $classes = array();

        if( isset($options['getNodeInfo_light']) && $options['getNodeInfo_light'] ) {
            $getNodeInfo_full = false;
        } else {
            $getNodeInfo_full = true;
        }

        $matrix = & enic::get('matrixCache');

        $childs = Kernel::getNodeChilds ('BU_GRVILLE', $grville);
        foreach ($childs as $child) {
            if ($child['type']=='BU_VILLE') {

                if ( ($ville_as_array = Kernel::getKernelLimits('ville_as_array')) && !in_array($child['id'],$ville_as_array))
                    continue;

                if (isset($options['droit'])) {
                    $tmp = AnnuaireService::getClassesInVille ($child['id'], array('droit'=>$options['droit']));
                } else
                    $tmp = AnnuaireService::getClassesInVille ($child['id']);

                if (count($tmp)>0) {
                    $node = Kernel::getNodeInfo ($child['type'], $child['id'], $getNodeInfo_full);
                    $classes[] = array('id'=>'0', 'nom'=>"----- ".$node['nom']." -----");
                    $classes = array_merge ($classes, $tmp);

                }
            }
        }
        return $classes;
    }


    /**
     * Retourne le(s) directeur(s) d'une �cole
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $ecole Id de l'�cole
     * @return array Tableau avec les directeurs
     */
    public function getDirecteurInEcole ($ecole, $options=array())
    {
        $directeur = array();

    $sql = "SELECT PER.numero, PER.nom, PER.prenom1 FROM kernel_bu_personnel PER, kernel_bu_personnel_entite ENT WHERE PER.numero=ENT.id_per AND ENT.reference=".$ecole." AND type_ref='ECOLE' AND role=2 ORDER BY PER.nom, PER.prenom1";
       $list = _doQuery ($sql);
    foreach ($list as $r) {
      $res = array('type'=>'USER_ENS', 'id'=>$r->numero, 'login'=>NULL, 'nom'=>$r->nom, 'prenom'=>$r->prenom1);
      // A-t-il un compte ?
      $sql = "SELECT USR.login_dbuser AS login FROM dbuser USR, kernel_link_bu2user LIN WHERE LIN.user_id=USR.id_dbuser AND LIN.bu_id=".$r->numero." AND LIN.bu_type='USER_ENS' LIMIT 1";
         if ($usr = _doQuery ($sql)) {
        $res['login'] = $usr[0]->login;
      }
      $directeur[] = $res;
    }
    //print_r($directeur);
        return $directeur;
    }


    /**
     * Retourne le personnel administratif d'une �cole
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/20
     * @param integer $ecole Id de l'�cole
     * @return array Tableau avec le personnel
     */
    public function getAdministratifInEcole ($ecole, $options=array())
    {
        $personnel = array();
        $result = Kernel::getNodeChilds('BU_ECOLE', $ecole );
        foreach ($result AS $key=>$value) {
            //print_r($value);
            if ($value['type']=='USER_ADM') {
                    $tmp = array('type'=>$value['type'], 'id'=>$value['id'], 'login'=>$value['login'], 'nom'=>$value['nom'], 'prenom'=>$value['prenom']);
                    $personnel[] = $tmp;
            }
        }
        usort ($personnel, array('AnnuaireService', 'usort_nom'));
        return $personnel;
    }


    /**
     * Retourne le(s) enseignants(s) d'une classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $classe Id de la classe
     * @return array Tableau avec les enseignants
     */
    public function getEnseignantInClasse ($classe, $options=array())
    {
        $enseignant = array();
        $result = Kernel::getNodeChilds('BU_CLASSE', $classe, false); // true=normal false=optimis�
        foreach ($result AS $key=>$value) {
            //print_r($value);
            if ($value['type']=='USER_ENS') {
                $nodeInfo = Kernel::getUserInfo ($value["type"], $value["id"]);
                //print_r($nodeInfo);
                $login = isset($nodeInfo['login']) ? $nodeInfo['login'] : '';
                $ens = array('type'=>$nodeInfo['type'], 'id'=>$nodeInfo['id'], 'login'=>$login, 'nom'=>$nodeInfo['nom'], 'prenom'=>$nodeInfo['prenom'], 'sexe'=>$nodeInfo['sexe']);
                //$enseignants[] = $result[$key];
                $enseignant[] = $ens;
            }
        }
        usort ($enseignant, array('AnnuaireService', 'usort_nom'));
        return $enseignant;
    }


    /**
     * Retourne les �l�ves d'une classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $classe Id de la classe
     * @return array Tableau avec les �l�ves
     * @todo Ajouter les parents
     */
    public function getElevesInClasse ($classe, $options=array())
    {
        $eleves = array();
        $result = Kernel::getNodeChilds('BU_CLASSE', $classe);
        foreach ($result AS $key=>$value) {
            //print_r($value);
            if ($value['type']=='USER_ELE') {
                $nodeInfo = Kernel::getUserInfo ($value["type"], $value["id"]);
                //var_dump($nodeInfo);

                //$parents = Kernel::getNodeChilds ($value["type"], $value["id"]);
                //print_r($parents);
                $login = isset($nodeInfo['login']) ? $nodeInfo['login'] : '';
                $ele = array('type'=>$nodeInfo['type'], 'id'=>$nodeInfo['id'], 'login'=>$login, 'nom'=>$nodeInfo['nom'], 'prenom'=>$nodeInfo['prenom'], 'sexe'=>$nodeInfo['sexe']);
                //$enseignants[] = $result[$key];
                $eleves[] = $ele;
            }
        }
        usort ($eleves, array('AnnuaireService', 'usort_nom'));
        return $eleves;
    }


    /**
     * Retourne les agents de ville d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/02/13
     * @param integer $ville id de la ville
     * @param array $options Tableau d'options : [droit] pour n'avoir que les agents sur lesquels on a ce droit
     * @return array Tableau avec les agents
     */
    public function getAgentsInVille ($ville, $options=array())
    {
        $matrix = & enic::get('matrixCache');
        $agents = array();
        $result = Kernel::getNodeChilds('BU_VILLE', $ville);
        foreach ($result AS $key=>$value) {
            //print_r($value);
            if ($value['type']=='USER_VIL') {

                if (isset($options['droit']) && $options['droit']) {
                    $droit = $matrix->ville($ville)->_right->USER_VIL->$options['droit'];
                    //Kernel::myDebug("droit=".$droit);
                    if (!$droit) {
                        continue;
                    }
                }

                if ($nodeInfo = Kernel::getUserInfo ($value["type"], $value["id"])) {
                    //var_dump($nodeInfo);
                    $login = isset($nodeInfo['login']) ? $nodeInfo['login'] : '';
                    $age = array('type'=>$nodeInfo['type'], 'id'=>$nodeInfo['id'], 'login'=>$login, 'nom'=>$nodeInfo['nom'], 'prenom'=>$nodeInfo['prenom'], 'sexe'=>$nodeInfo['sexe']);
                    $agents[] = $age;
                }
            }
        }
        usort ($agents, array('AnnuaireService', 'usort_nom'));
        return $agents;
    }

    /**
     * Fonction de comparaison permettant de trier avec usort un tableau selon le nom des �l�ments
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @link http://www.php.net/usort
     * @param mixed $a Premier �l�ment
     * @param mixed $b Deuxi�me �l�ment
     * @return integer Valeur de comparaison : inf�rieur, �gal ou sup�rieur � z�ro suivant que le premier �l�ment est consid�r� comme plus petit, �gal ou plus grand que le second �l�ment
     */
    public function usort_nom ($a, $b)
    {
      $comp = strcmp($a["nom"], $b["nom"]);
        if ($comp == 0 && isset($a["prenom"]) && isset($b["prenom"]))
            $comp = strcmp($a["prenom"], $b["prenom"]);
        return $comp;
    }


    /**
     * Tous les �l�ves d'une classe, d'une �cole, d'une ville ou d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param string $type Type du parent o� sont rattach�s les �l�ves (BU_CLASSE, BU_ECOLE, BU_VILLE, BU_GRVILLE)
     * @param integer $id Id du parent
     * @return array Tableau contenant tous les �l�ves, tri�s alphab�tiquement
     */
    public function getEleves ($type, $id)
    {
        $dao = _dao("kernel|kernel_bu_ele");
        if ($type == 'BU_CLASSE')
            $res = $dao->getElevesInClasse($id);
        elseif ($type == 'BU_ECOLE')
            $res = $dao->getElevesInEcole($id);
        elseif ($type == 'BU_VILLE')
            $res = $dao->getElevesInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getElevesInGrville($id);
        //print_r($res);
        return $res;
    }

    /**
     * Tous le personnel �cole d'une classe, d'une �cole, d'une ville ou d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param string $type Type du parent o� sont rattach�s les personnels (BU_CLASSE, BU_ECOLE, BU_VILLE, BU_GRVILLE)
     * @param integer $id Id du parent
     * @return array Tableau contenant tout le personnel �cole, tri� alphab�tiquement
     */
    public function getPersonnel ($type, $id)
    {
        $dao = _dao("kernel|kernel_bu_personnel");
        if ($type == 'BU_CLASSE')
            $res = $dao->getPersonnelInClasse($id);
        elseif ($type == 'BU_ECOLE')
            $res = $dao->getPersonnelInEcole($id);
        elseif ($type == 'BU_VILLE')
            $res = $dao->getPersonnelInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getPersonnelInGrville($id);
        //print_r($res);
        return $res;
    }

    /**
     * Tous les parents ayant un enfant dans une classe, une �cole, une ville ou un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param string $type Type du parent o� sont rattach�s les parents (BU_CLASSE, BU_ECOLE, BU_VILLE, BU_GRVILLE)
     * @param integer $id Id du parent
     * @return array Tableau contenant tout les parents, tri�s alphab�tiquement
     */
    public function getParents ($type, $id)
    {
        $dao = _dao("kernel|kernel_bu_res");
        if ($type == 'BU_CLASSE')
            $res = $dao->getParentsInClasse($id);
        elseif ($type == 'BU_ECOLE')
            $res = $dao->getParentsInEcole($id);
        elseif ($type == 'BU_VILLE')
            $res = $dao->getParentsInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getParentsInGrville($id);
        //print_r($res);
        return $res;
    }


    /**
     * Tout le personnel ext�rieur d'une classe, d'une �cole, d'une ville ou d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/05/15
     * @param string $type Type du parent o� sont rattach�s les personnes (BU_CLASSE, BU_ECOLE, BU_VILLE, BU_GRVILLE)
     * @param integer $id Id du parent
     * @return array Tableau contenant les personnes, tri�es alphab�tiquement
     */
    public function getPersonnelExt ($type, $id)
    {
        $dao = _dao("kernel|kernel_ext_user");

        if ($type == 'BU_CLASSE')
            $res = $dao->getPersonnelExtInClasse($id);
        elseif ($type == 'BU_ECOLE')
            $res = $dao->getPersonnelExtInEcole($id);
        elseif ($type == 'BU_VILLE')
            $res = $dao->getPersonnelExtInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getPersonnelExtInGrville($id);
        elseif ($type == 'ROOT')
            $res = $dao->getPersonnelExtInAll();
        //print_r($res);
        return $res;
    }


    /**
     * Tout le personnel administratif d'une �cole, d'une ville ou d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/01/19
     * @param string $type Type du parent o� sont rattach�s les personnes (BU_ECOLE, BU_VILLE, BU_GRVILLE)
     * @param integer $id Id du parent
     * @return array Tableau contenant les personnes, tri�es alphab�tiquement
     */
    public function getPersonnelAdm ($type, $id)
    {
        $dao = _dao("kernel|kernel_bu_personnel");
        if ($type == 'BU_ECOLE')
            $res = $dao->getPersonnelAdmInEcole($id);
        elseif ($type == 'BU_VILLE')
            $res = $dao->getPersonnelAdmInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getPersonnelAdmInGrville($id);
        //print_r($res);
        return $res;
    }

    /**
     * Tout les agents de ville d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/11/06
     * @param string $type Type du parent o� sont rattach�s les personnes (BU_VILLE uniquement)
     * @param integer $id Id du parent
     * @return array Tableau contenant les personnes, tri�es alphab�tiquement
     */
    public function getPersonnelVil ($type, $id)
    {
        $dao = _dao("kernel|kernel_bu_personnel");
        if ($type == 'BU_VILLE')
            $res = $dao->getPersonnelVilInVille($id);
        elseif ($type == 'BU_GRVILLE')
            $res = $dao->getPersonnelVilInGrville($id);
        //print_r($res);
        return $res;
    }

    /**
     * Retourne les parents d'un �l�ve
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/23
     * @param integer $eleve Id de l'�l�ve
     * @return array Tableau avec les parents
     */
    public function getParentsFromEleve ($eleve)
    {
        $res = array();
        $parents = Kernel::getNodeChilds ('USER_ELE', $eleve);
        foreach ($parents as $parent) {
            //print_r($parent);
            $userInfo = Kernel::getUserInfo ($parent['type'], $parent['id']);
            //print_r($userInfo);
            $tmp = array_merge ($parent, $userInfo);
            $res[] = $tmp;
        }
        //print_r($res);
        return $res;
    }

    /**
     * Retourne les enfants d'un parent
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/19
     * @param integer $parent Id du parent
     * @return array Tableau avec les enfants
     */
    public function getEnfantsFromParent ($parent)
    {
        $res = array();
        $enfants = Kernel::getNodeParents ('USER_RES', $parent);
    //print_r($enfants);
        foreach ($enfants as $enfant) {
            if ($enfant['type'] != 'USER_ELE') continue;
            //print_r($parent);
            $userInfo = Kernel::getUserInfo ($enfant['type'], $enfant['id']);
            //print_r($userInfo);
            $tmp = array_merge ($enfant, $userInfo);
            $res[] = $tmp;
        }
        //print_r($res);
        return $res;
    }

        /**
     * Teste si l'usager courant peut effectuer une certaine opération dans l'annuaire
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/25
     * @param string $action Action pour laquelle on veut tester le droit
     * @return bool true s'il a le droit d'effectuer l'action, false sinon
     * @todo Tester si adulte plutôt que USER_ENS (utiliser fonction du kernel)
     */
    public function canMakeInAnnuaire ($action)
    {
        $can = false;
        switch ($action) {
            case "POPUP_CHECK_ALL" : // Cocher tous/aucun (version popup)
                            $can = (_currentUser()->getExtra('type') == "USER_ENS"
                                 || _currentUser()->getExtra('type') == "USER_VIL"
                                 || _currentUser()->getExtra('type') == "USER_ADM"
                                 || _currentUser()->getExtra('type') == "USER_EXT"
                                );
                            break;
        }
        return $can;
    }



    public function checkVisibility ($list)
    {
        reset($list);
        $visibles = array();
        foreach( $list AS $user ) {
            if( Kernel::getUserVisibility( $user['type'], $user['id'] ) )
                $visibles[] = $user;
        }
        return( $visibles );
    }


  /**
     * Renvoie l'entree de l'annuaire pour l'usager courant. Pour les parents, prends le home d'un des enfants. S'il n'y a pas d'enfant ou que le compte n'est rattache a rien, on l'envoie dans la 1e ville.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/20
     * @return array Tableau avec [type] et [id] du noeud (BU_CLASSE, BU_ECOLE, BU_VILLE, BU_GVILLE)
     */
  public function getAnnuaireHome ()
  {
    // Recuperation de ses blocs, comme dans le dashboard
    $nodes_all = Kernel::getNodeParents($this->user->type, $this->user->idEn);
        $nodes_all = Kernel::sortNodeList($nodes_all);
        //_dump($nodes_all);

    $home = null;

    foreach ($nodes_all as $node) {
      if ($node['type'] == 'BU_CLASSE' || $node['type'] == 'BU_ECOLE' || $node['type'] == 'BU_VILLE' || $node['type'] == 'BU_GVILLE') {
        $home = array('type'=>$node['type'], 'id'=>$node['id']);
        break;
      }
    }

    //_dump($home);

    if (!$home && Kernel::isParent()) {  // Cas du parent d'�l�ve
      $enfants = Kernel::getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
      while (list($k,$v) = each($enfants)) {
        if ($v["type"] != "USER_ELE") continue;
        // Pour chaque enfant...
        //print_r($v);
        if (is_array($v['link']->classe) && ($id=array_shift(array_keys($v['link']->classe))))
          $home = array('type'=>'BU_CLASSE', 'id'=>$id);
        elseif (is_array($v['link']->ecole) && ($id=array_shift(array_keys($v['link']->ecole))))
          $home = array('type'=>'BU_ECOLE', 'id'=>$id);
        elseif (is_array($v['link']->ville) && ($id=array_shift(array_keys($v['link']->ville))))
          $home = array('type'=>'BU_VILLE', 'id'=>$id);
        if($home) break;
      }
    }

    if ( !$home || Kernel::isAdmin() ) {  // Si rattache a rien, on l'envoie dans la 1e ville
      $sql = "SELECT MIN(id_vi) AS ville FROM kernel_bu_ville LIMIT 1";
        $v = _doQuery($sql);
      $home = array('type'=>'BU_VILLE', 'id'=>$v[0]->ville);
    }
    //print_r($home);
    return $home;
  }


  /**
     * Renvoie l'adresse d'une entite au format souhaite par l'API Google Maps. Correspond a un tableau avec [latitude] et [longitude]
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/09/04
     * @param string $node_type Type de l'entite. Implemente : ecole
     * @param object $node Entite en elle-meme (recordset)
     * @return string Adresse reformatee
     */
  public function googleMapsFormatAdresse ($node_type, $node)
  {
        //var_dump($node);
        $adr = '';
        switch ($node_type) {
            case 'ecole' :
                $adr .= ($node->num_rue) ? (($adr)?' ':'').$node->num_rue : '';
                $adr .= ($node->num_seq) ? (($adr)?' ':'').$node->num_seq : '';
                $adr .= ($node->adresse1) ? (($adr)?' ':'').$node->adresse1 : '';
                $adr .= ($node->adresse2) ? (($adr)?' ':'').$node->adresse2 : '';
                $adr .= ($node->code_postal) ? (($adr)?' ':'').$node->code_postal : '';
                $adr .= ($node->commune) ? (($adr)?' ':'').$node->commune : '';
                break;
        }
        //var_dump($adr);
        //die();
    return $adr;
  }




}


