<?php

class DAOGroupe
{
    /**
     * Liste de groupes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/08
     * @param integer $forum Id du forum
     * @return array Tableau avec les groupes
     */
    public function getListPublic ($offset, $count, $kw=null)
    {
        $criteres = _daoSp ();

        $criteres->addCondition ('is_open', '=', 1);

    if(CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance'))) {
          $criteres->addCondition ('id', '!=', $groupeAssistance);
    }


        // D�coupage du pattern
        if ($kw) {
          $testpattern=str_replace(array(" ","%20"), "%20", $kw);
          $temp = split ("%20", $testpattern);
            $criteres->startGroup ();
            foreach ($temp as $word) {
               if ($word != "") {
                    $criteres->addCondition ('titre', 'LIKE', "%$word%", 'or');
                    $criteres->addCondition ('description', 'LIKE', "%$word%", 'or');
                }
          }
            $criteres->endGroup ();
        }

        if ($offset)
            $criteres->setOffset ($offset);
        if ($count)
        $criteres->setCount ($count);
        $criteres->orderBy (array ('date_creation', 'desc'));
        $list = _ioDao ('groupe|groupe')->findBy ($criteres);

                //search tags by Id or Name
        $listTag = new CopixDAORecordIterator (_doQuery ('SELECT g.id AS id, g.titre AS titre, g.description AS description, g.is_open AS is_open, g.createur AS createur, g.date_creation AS date_creation FROM module_groupe_groupe AS g JOIN module_tags_groups AS tg ON tg.id_group = g.id JOIN module_tags AS t ON tg.id_tag = t.id WHERE g.is_open=1 AND t.name LIKE "%'.$kw.'%" OR t.id = '.(int)$kw), $this->getDAOId ());

                //merge records
                $listGroupFinal = array();
                foreach($list as $l)
                    $listGroupFinal[] = $l;
                foreach($listTag as $l)
                    $listGroupFinal[] = $l;
                $list = $listGroupFinal;

        $arGroupes = array();
                $groupListId = array();
        foreach ($list as $groupe) {
                    if(in_array($groupe->id, $groupListId))
                            continue;

                    $groupListId[] = $groupe->id;
            $parent = Kernel::getNodeParents ("CLUB", $groupe->id );
            $ok = true;
            if (Kernel::getKernelLimits('ville')) {
                if ($parent) {
                    $ville = GroupeService::getGroupeVille($groupe->id, $parent);
                    if (!in_array($ville, Kernel::getKernelLimits('ville_as_array')))
                        $ok = false;
                } else
                    $ok = false;
            }

            if ($ok) {
                $groupe->parent = $parent;
                $arGroupes[] = $groupe;
            }
        }

        $results = $arGroupes;
        return $results;
    }

}


