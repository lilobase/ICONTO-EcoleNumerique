<?php

/**
 * Fonctions relatives au kernel et au module Forum
 *
 * @package Iconito
 * @subpackage	Forum
 */
class KernelForum
{
    /**
     * Création d'un forum
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/08
   * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
     * @return integer l'Id du forum créé ou NULL si erreur
     */
    public function create ($infos=array())
    {
        $daoForum = _dao("forum|forum_forums");
        $newForum = _record("forum|forum_forums");
        $newForum->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
        $newForum->date_creation = date("Y-m-d H:i:s");
        $daoForum->insert ($newForum);
        return ($newForum->id!==NULL) ? $newForum->id : NULL;
    }

    /**
     * Suppression d'un forum
     *
     * Supprime un forum, ses discussions etc.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/09
     * @param integer $id Id du forum à supprimer
     * @return boolean true si la suppression s'est bien passée, false sinon
     */
    public function delete ($idForum)
    {
        $daoForums = _dao("forum|forum_forums");
         $daoTopics = _dao("forum|forum_topics");
        $rForum = $daoForums->get($idForum);
        $res = false;
        if ($rForum) {
            $criteres = _daoSp();
            $criteres->addCondition('forum', '=', $idForum);
            $topics = $daoTopics->findBy($criteres);
      foreach ($topics as $topic) {
                $criteres = _daoSp ()->addCondition ('topic', '=', $topic->id);
                _dao ('module_forum_tracking')->deleteBy($criteres);
                _dao ('module_forum_messages')->deleteBy($criteres);
                $criteres = _daoSp ()->addCondition ('id', '=', $topic->id);
                _dao ('module_forum_topics')->deleteBy($criteres);
            }
            $daoForums->delete ($idForum);
            $res = true;
        }
    Kernel::unregisterModule("MOD_FORUM", $idForum);
        return $res;
    }

    /**
     * Statistiques d'un forum
     *
     * Renvoie des éléments chiffrés relatifs à un forum : nombre de discussions, nombre de messages...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id_forum Id du forum à analyser
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["Discussions"] ["Messages"]
     */
    public function getStats ($id_forum)
    {
        $daoForum = _dao("forum|forum_forums");
        $res = array();
        $infos = $daoForum->getNbTopicsInForum($id_forum);
        $res['nbTopics'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbTopics', array($infos[0]->nb)));
        $infos = $daoForum->getNbMessagesInForum($id_forum);
        $res['nbMessages'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbMessages', array($infos[0]->nb)));
        return $res;
    }


    /**
     * Statistiques du module forum
     *
     * Renvoie des éléments chiffrés relatifs aux forums et dédiés à un utilisateur système : nombre de discussions, nombre de messages...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/19
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbForums"] ["nbTopics"] ["nbMessages"]
     */
    public function getStatsRoot ()
    {
        $res = array();
        $sql = 'SELECT COUNT(F.id) AS nb FROM module_forum_forums F';
        $a = _doQuery($sql);
        $res['nbForums'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbForums', array($a[0]->nb)));
        $sql = 'SELECT COUNT(T.id) AS nb FROM module_forum_topics T';
        $a = _doQuery ($sql);
        $res['nbTopics'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbTopics', array($a[0]->nb)));
        $sql = 'SELECT COUNT(M.id) AS nb FROM module_forum_messages M';
        $a = _doQuery($sql);
        $res['nbMessages'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbMessages', array($a[0]->nb)));
        return $res;
    }

    public function getNotifications(&$module, &$lastvisit)
    {
        /*
        $new_topics = _dao('forum|forum_topics')->findBy(
            _daoSp()->addCondition('forum', '=', $module->module_id)->addCondition('date_creation', '>', $lastvisit->date)
        );
        */

        $new_replies = _dao('forum|forum_messages_topics')->findBy(
            _daoSp()->addCondition('forum', '=', $module->module_id)->addCondition('date', '>', $lastvisit->date)->addCondition('auteur', '!=', _currentUser()->getExtra("user_id"))
        );

        /*
        $module->notification_number = count($new_topics)+count($new_replies);
        $module->notification_message = (count($new_topics)?count($new_topics)." sujets":"").((count($new_topics)&&count($new_replies))?" et ":"").(count($new_replies)?count($new_replies)." messages":"");
        */

        $module->notification_number = count($new_replies);
        $module->notification_message = count($new_replies).(count($new_replies)>1?" nouveaux messages":" nouveau message");
        return true;
    }

}

