<?php

class ActionGroupIconito extends CopixActionGroup
{
    public function beforeAction()
    {
        //_currentUser()->assertCredential ('group:[current_user]');
    }

    public function processDefault()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        //$send = $serv->updateCron ();
        echo "<pre>";
        echo "Date d'installation : ".$serv->getInstallationDate()."\n";
        echo "Date de dernière mise à jour : ".$serv->getLastUpdateDate()."\n";
        echo "Révision SVN : ".$serv->getSvnRev()."\n";
        //echo "Consommation : ".$serv->getBytes()."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Villes : ".$serv->getNbObject("villes")."\n";
        echo "Nb Agents de Villes : ".$serv->getNbObject("agents")."\n";
        echo "Nb Ecoles : ".$serv->getNbObject("ecoles")."\n";
        echo "Nb Directeurs : ".$serv->getNbObject("directeurs")."\n";
        echo "Nb Classes : ".$serv->getNbObject("classes")."\n";
        echo "Nb Enseignants : ".$serv->getNbObject("enseignants")."\n";
        echo "Nb d'Élèves : ".$serv->getNbObject("eleves")."\n";
        echo "Nb de Responsables : ".$serv->getNbObject("responsables")."\n";
        echo "Nb de Personnes Externes : ".$serv->getNbObject("externes")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Blogs : ".$serv->getNbObject("blogs")."\n";
        echo "Nb Articles : ".$serv->getNbObject("articles")."\n";
        echo "Nb Catégories : ".$serv->getNbObject("categories")."\n";
        echo "Nb Commentaires : ".$serv->getNbObject("commentaires")."\n";
        echo "Nb Pages : ".$serv->getNbObject("pages")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Malles : ".$serv->getNbObject("malles")."\n";
        echo "Nb Fichiers : ".$serv->getNbObject("fichiers")."\n";
        echo "<pre>";
        echo "Nb Albums : ".$serv->getNbObject("albums")."\n";
        echo "Nb Photos : ".$serv->getNbObject("photos")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb de Minimails de : ".$serv->getNbObject("minimail_from")."\n";
        echo "Nb de Minimails à : ".$serv->getNbObject("minimail_to")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Agenda : ".$serv->getNbObject("agendas")."\n";
        echo "Nb Evenements : ".$serv->getNbObject("evenements")."\n";
        echo "Nb Leçons : ".$serv->getNbObject("lecons")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Forums : ".$serv->getNbObject("forums")."\n";
        echo "Nb Topics : ".$serv->getNbObject("topics")."\n";
        echo "Nb Messages : ".$serv->getNbObject("forums_messages")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Carnets : ".$serv->getNbObject("carnets")."\n";
        echo "Nb Messages : ".$serv->getNbObject("carnets_messages")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Quiz : ".$serv->getNbObject("quiz")."\n";
        echo "Nb Questions : ".$serv->getNbObject("questions")."\n";
        echo "Nb Réponses : ".$serv->getNbObject("reponses")."\n";
        echo "</pre>";
        echo "<pre>";
        echo "Nb Type de Téléprocédures : ".$serv->getNbObject("teleprocedures_type")."\n";
        echo "Nb Téléprocédures : ".$serv->getNbObject("teleprocedures")."\n";
        echo "</pre>";
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processStatus ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        echo "Ecole Numérique\n";
        echo $serv->getInstallationDate("TS")."\n";
        echo $serv->getLastUpdateDate("TS")."\n";
        echo $serv->getSvnRev()."\n";
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninCore ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Core\n";
            echo "graph_args --base 1000 --units-exponent 0\n";
            echo "graph_vlabel Version\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "revision.label Revision locale\n";
            echo "depot.label Revision distante\n";
            echo "bdd.label Mises a jours BDD\n";
        } else {
            echo "revision.value ".$serv->getSvnRev()."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninBu ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Stat BU\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "villes.label Villes\n";
            echo "agents.label Agents\n";
            echo "ecoles.label Ecoles\n";
            echo "directeurs.label Directeurs\n";
            echo "classes.label Classes\n";
            echo "enseignants.label Enseignants\n";
            echo "eleves.label Eleves\n";
            echo "responsables.label Responsables\n";
            echo "externes.label Externes\n";
        } else {
            echo "villes.value ".$serv->getNbObject("villes")."\n";
            echo "agents.value ".$serv->getNbObject("agents")."\n";
            echo "ecoles.value ".$serv->getNbObject("ecoles")."\n";
            echo "directeurs.value ".$serv->getNbObject("directeurs")."\n";
            echo "classes.value ".$serv->getNbObject("classes")."\n";
            echo "enseignants.value ".$serv->getNbObject("enseignants")."\n";
            echo "eleves.value ".$serv->getNbObject("eleves")."\n";
            echo "responsables.value ".$serv->getNbObject("responsables")."\n";
            echo "externes.value ".$serv->getNbObject("externes")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninBlogs ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Blogs\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "blogs.label Blogs\n";
            echo "articles.label Articles\n";
            echo "categories.label Categories\n";
            echo "commentaires.label Commentaires\n";
            echo "pages.label Pages\n";
        } else {
            echo "blogs.value ".$serv->getNbObject("blogs")."\n";
            echo "articles.value ".$serv->getNbObject("articles")."\n";
            echo "categories.value ".$serv->getNbObject("categories")."\n";
            echo "commentaires.value ".$serv->getNbObject("commentaires")."\n";
            echo "pages.value ".$serv->getNbObject("pages")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninMalles ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Malles\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "malles.label Malles\n";
            echo "fichiers.label Fichiers\n";
        } else {
            echo "malles.value ".$serv->getNbObject("malles")."\n";
            echo "fichiers.value ".$serv->getNbObject("fichiers")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninAlbums ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Albums\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "albums.label Albums\n";
            echo "photos.label Photos\n";
        } else {
            echo "albums.value ".$serv->getNbObject("albums")."\n";
            echo "photos.value ".$serv->getNbObject("photos")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninMinimails ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Minimails\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "from.label Minimails envoyes\n";
            echo "to.label Minails recus\n";
        } else {
            echo "from.value ".$serv->getNbObject("minimail_from")."\n";
            echo "to.value ".$serv->getNbObject("minimail_to")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninAgendas ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Agendas\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "agendas.label Agendas\n";
            echo "evenements.label Evenements\n";
            echo "lecons.label Lecons\n";
        } else {
            echo "agendas.value ".$serv->getNbObject("agendas")."\n";
            echo "evenements.value ".$serv->getNbObject("evenements")."\n";
            echo "lecons.value ".$serv->getNbObject("lecons")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninForums ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Forums\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "forums.label Forums\n";
            echo "topics.label Sujets\n";
            echo "forums_messages.label Messages\n";
        } else {
            echo "forums.value ".$serv->getNbObject("forums")."\n";
            echo "topics.value ".$serv->getNbObject("topics")."\n";
            echo "forums_messages.value ".$serv->getNbObject("forums_messages")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninCarnets ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Carnets\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "carnets.label Carnets\n";
            echo "carnets_messages.label Messages\n";
        } else {
            echo "carnets.value ".$serv->getNbObject("carnets")."\n";
            echo "carnets_messages.value ".$serv->getNbObject("carnets_messages")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninQuiz ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Quiz\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "quiz.label quiz\n";
            echo "questions.label Questions\n";
            echo "responses.label Reponses\n";
        } else {
            echo "quiz.value ".$serv->getNbObject("quiz")."\n";
            echo "questions.value ".$serv->getNbObject("questions")."\n";
            echo "responses.value ".$serv->getNbObject("reponses")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function processMuninTeleprocedures ()
    {
        $serv = CopixClassesFactory::create("stats|IconitoService");
        if (_request('config', 0)) {
            echo "graph_title Teleprocedures\n";
            echo "graph_args --base 1000 -l 0\n";
            echo "graph_vlabel Nb\n";
            echo "graph_scale yes\n";
            echo "graph_info This graph shows how CPU time is spent.\n";
            echo "graph_category Ecole Numerique\n";
            echo "graph_period minute\n";
            echo "teleprocedures_type.label Types de teleprocedures\n";
            echo "teleprocedures.label Teleprocedures\n";
        } else {
            echo "teleprocedures_type.value ".$serv->getNbObject("teleprocedures_type")."\n";
            echo "teleprocedures.value ".$serv->getNbObject("teleprocedures")."\n";
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }
}
