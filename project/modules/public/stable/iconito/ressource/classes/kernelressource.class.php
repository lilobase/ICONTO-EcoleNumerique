<?php


class KernelRessource
{
    /*
        Renvoie différentes infos chiffrées d'un annuaire de ressources, dans un tableau
    */
    public function getStats ($id_ressource)
    {
        $dao = CopixDAOFactory::create("ressource|ressource_annuaires");
        $res = array();
        $infos = $dao->getNbRessourcesInAnnuaire($id_ressource);
        $res["Ressources"] = $infos[0]->nb;
        return $res;
    }

}

