<?php
/**
* @package   copix
* @subpackage copixtools
* @version   $Id: CopixPager.class.php,v 1.5 2006-10-23 08:27:32 cbeyer Exp $
* @author <o.veujoz@miasmatik.net>, Bertrand Yan
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class CopixPager
{
    /**
     * Nombre de r�sultat par page souhait�
     *
     * Valeur par d�faut : 10
     * @var int $perPage
     * @see calcul()
     */
   public $perPage;



    /**
     * Le nombre de liens par page souhait�
     *
     * 0 = tous les liens sur la m�me page
     * Valeur par d�faut : 10
     * @var int $delta
     * @see calcul(), parseLoop()
     */
   public $delta;



    /**
     * Le nom du fichier template contenant le code HTML du multipage
     *
     * Ce fichier doit obligatoirement se trouver dans le sous-r�pertoire "templates" de la classe
     * Valeur par d�faut : 'pager1.tpl'
     * @var string $template
     * @see parsetemplate()
     */
   public $template;



    /**
     * Permet de cr�er une URI personnalis�
     *
     * Utile notamment pour tout ce qui rel�ve de l'ordre de l'URL Rewritting.
     * Doit obligatoirement poss�der la variable PAGE.
     * Exemple : mapage-parametre-<#PAGE>.htm
     * Valeur par d�faut : chaine vide
     * @var string $tplUri
     * @see getLink()
     */
    public $tplUri;



    /**
     * Permet d'ajouter X colonnes pour la fusion de colonnes (colspan) dans le template
     *
     * Valeur par d�faut : 0
     * @var int $addToColspan
     * @see parseColspan()
     */
   public $addToColspan;



    /**
     * Libell� pour la page suivante
     *
     * Se configure dans le fichier 'config.class.php'
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $nextPage
     * @see parseNextPage()
     */
   public $nextPage;



    /**
     * Libell� pour la page pr�c�dente
     *
     * Se configure dans le fichier 'config.class.php'
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $previousPage
     * @see parsePreviousPage()
     */
   public $previousPage;



    /**
     * Libell� pour l'acc�s � la derni�re page
     *
     * Se configure dans le fichier 'config.class.php'
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $lastPage
     * @see parseLastPage()
     */
   public $lastPage;



    /**
     * Libell� pour l'acc�s � la premi�re page
     *
     * Se configure dans le fichier 'config.class.php'
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $firstPage
     * @see parseFirstPage()
     */
   public $firstPage;



    /**
     * Toujours afficher le multipage
     *
     * Sp�cifie s'il faut afficher le multipage lorsque ce dernier n'est pas n�cessaire (cas o� il y a moins d'enregistrements que
     * la valeur contenue dans $perPage)
     * Valeur par d�faut : true
     * @var bool $alwaysShow
     * @see setup()
     */
   public $alwaysShow;



    /**
     * S�parateur de page
     *
     * D�finit le s�parateur de pages pour les parties "loop".
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $separator
     * @see parseLoop()
     */
   public $separator;



    /**
     * Nom de colonne pour le mode "alphab�tique"
     *
     * Cette propri�t� permet de sp�cifier � la classe sur quelle colonne de la requ�te doit se jouer le tri.
     * Si cette propri�t� est pr�cis�e, la classe passe automatiquement en mode "index alphab�tique".  Pour rester en mode
     * num�rique, cette propri�t� doit rester vide.
     * @var string $alphaColumn
     * @see createSQL(), buildAlphaLikeClause(), calcul(), parseLoop()
     */
    public $alphaColumn;



    /**
     * Regroupement de caract�res
     *
     * Prise en compte de x lettres (ex : [A-Z])
     * Valeur par d�faut : 1
     * @var int $alphaEncaps
     * @see buildAlphaLikeClause(), calcul(), getAlphaLinkValue()
     */
    public $alphaEncaps;



    /**
     * Nom de la variable pass�e par l'url pour le num�ro de la page
     *
     * Cette propri�t� sert � renommer la variable sur le num�ro de page que vous passez par l'url. Ceci permet
     * d'�viter un potentiel conflit si vous disposez d�j� d'une variable de m�me nom dans votre url.
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $varUrl
     */
    public $varUrl;



    /**
     * Identifiant de d�but d'une variable template
     *
     * Sert � renommer les variables templates au bon vouloir du programmeur.
     * Cette propri�t� indique par quel code commence une variable template
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $tplVarBegin
     */
    public $tplVarBegin;



    /**
     * Identifiant de fin d'une variable template
     *
     * Sert � renommer les variables templates au bon vouloir du programmeur.
     * Cette propri�t� indique par quel code commence une variable template
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $tplVarBegin
     */
    public $tplVarEnd;



    /**
     * Chaine ajout�e avant le nom de la page courante
     *
     * Sert � mettre en valeur la page courante
     *
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $curPageSpanPre
     */
    public $curPageSpanPre;



    /**
     * Chaine ajout�e apr�s le nom de la page courante
     *
     * Sert � mettre en valeur la page courante
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $curPageSpanPost
     */
    public $curPageSpanPost;



    /**
     * Classe CSS pour les liens
     *
     * Se configure dans le fichier config.class.php
     * Se r�f�rer au fichier de configuration pour la valeur par d�faut
     * @var string $linkClass
     */
    public $linkClass;



    /**
     * Chemin d'acc�s complet aux templates
     *
     * @access public
     * @var string $linkClass
     * @since 3.1a
     */
    public $tplDir;



    /**
     * Mode dans lequel fonctionnera la classe Multipage
     *
     * Choix possibles : 'arbitraire' || 'alphabetique'
     * @access private
     * @var string $multipage_mode
     */
    public $multipage_mode;



    /**
     * Flag permettant de savoir si l'on est d�j� pass� dans la m�thode setup()
     *
     * Val par d�faut : FALSE
     * @access private
     * @var bool $_initialized
     */
    public $_initialized;



    /**
     * Indique si les �l�ments contenu dans l'url doivent �tre encod�s
     *
     * Valeur par d�faut : false
     * @access public
     * @var bool $urlEncode
     */
    public $encodeVarUrl;



    /**
     * Encode les entit�s HTML des libell�s du multipage
     *
     * @access public
     * @var bool $toHtmlEntities
     */
    public $toHtmlEntities;



    /**
     * Permet de switcher entre le mode "jumping/sliding"
     *
     * @access public;
     * @var string $display
     */
    public $display;



    /**
     * Chargement du drivers
     *
     * @param string $drivers 'DB'/'Array'
     * @access public/private
     * @return void
     * @since
      */
    function Load($options = Array()) {
        //$className = 'multipage_' . StrToLower($driver);
        //require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . StrToLower($driver) . '.class.php');
        //$object =& new $className($options);
        require_once (COPIX_UTILS_PATH.'CopixArrayPager.class.php');
        return new CopixArrayPager($options);
    }



   /**
     * Constructeur
     *
     * Instancie les propri�t�s avec leur valeurs par d�faut
     * @access public
     * @return void
     * @since 1.0
     */
   public function CopixPager ($options = array())
   {
        $this-> template       = '|pager.tpl';
        $this-> tplDir         = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this-> addToColspan   = 0;
        $this-> tplUri         = '';
        $this-> alphaColumn    = '';
        $this-> alphaEncaps    = 1;
        $this-> aAlpha         = Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $this-> tpl_data       = '';
        $this-> multipage_mode = 'arbitraire';
        $this-> _initialized   = FALSE;

        foreach ($options as $option => $value) {
            $this-> $option = $value;
        }
   } // end func Multipage



    /**
     * Index de d�part, index de fin du tableau $aAlpha
     *
     * Calcule les index de d�part et de fin pour le tableau $aAlpha en fonction de la page $page
     * @access private
     * @param int $page, page pour laquelle il faut calculer l'index (page courante par d�faut)
     * @return array 0 => index du d�but, 1 => index de fin
     * @since 3.1
     */
    public function getAlphaIndex($page = '')
    {
        if (empty($page)) $page = $this-> currentPage;

        $IndexStart = ($page * $this-> alphaEncaps) - $this-> alphaEncaps;
        $IndexEnd   = $IndexStart + $this-> alphaEncaps;

        return array($IndexStart, $IndexEnd);
    } // end func getAlphaIndex



   /**
     * Initialisation des propri�t�s n�cessaires au bon d�roulement du multipage
     *
     * Les propri�t�s g�n�r�es sont les suivantes :
     *     int  nbRecord, le nombre d'enregistrements contenus dans la requ�te, en tenant compte de la clause LIMIT
     *     int  nbLinks, contient le nombre de liens n�cessaire � l'affichage de tous les enregistrements
     *     int  first_pos, contient la position du lien � partir duquel il faudra commencer � afficher les liens vers les autres pages
     *     int  last_pos, contient la position du lien � partir duquel il faudra arr�ter d'afficher les liens vers les autres pages
     * @access private
     * @return void
     * @see linkCount()
     * @since 1.0
     */
   public function calcul()
   {
        // Nombre total d'enregistrements
        $this-> nbRecord = $this-> getNbRecord();

        // Nombre de liens n�cessaire � l'affichage de tous les enregistrements
        $this-> nbLinks = $this-> linkCount();

        // Si un petit malin cherche � modifier la variable "page" directement dans l'url
        if ($this-> currentPage > $this-> nbLinks) {
            $_GET[$this-> varUrl] = $this-> nbLinks;
            $this-> setRestriction();
        }

        // Affichage de tous les liens
        if ($this-> delta == 0) $this-> delta = $this-> nbLinks;

        // Suivant le mode d'affichage d�sir� (sliding||jumping), le calcul diff�re
      if ($this-> display == 'sliding') {

            // Position interm�diaire de la page en cours (affichage au milieu)
            $middlePos = floor($this-> delta / 2);

            if ($middlePos < $this-> nbLinks) {

                // Position � partir de laquelle on arr�te d'afficher les liens
                if ($this-> currentPage <= $middlePos) $this-> last_pos = abs($this-> currentPage - $middlePos) + $middlePos + ($this-> currentPage + 1);
                else                                   $this-> last_pos = $this-> currentPage + $middlePos;

                // Position � partir de laquelle on commence � afficher les liens
              if (($this-> currentPage + $middlePos) > $this-> nbLinks)   $this-> first_pos = $this-> currentPage - (($this->last_pos + $middlePos) - $this-> nbLinks);
                elseif ($this-> currentPage > $middlePos)                   $this-> first_pos = $this-> currentPage - $middlePos;
              else                                                        $this-> first_pos = 1;

            } else {
                // Il n'y a pas assez d'enregistrements pour afficher autant de liens que souhait� par la propri�t� delta
                $this-> first_pos = 0;
                $this-> last_pos  = $this-> nbLinks;
            }

        } else {
            // Mode "jumping"
            // Ok, je reconnais, pas la peine de me bl�mer, j'ai honteusement pomp� le code du Pager de Pear, vu mon incapacit� � trouver la bonne formule
            $start = ((($this-> currentPage + (($this-> delta - ($this-> currentPage % $this-> delta))) % $this-> delta) / $this-> delta) - 1) * $this-> delta + 1;
            $this-> first_pos  = max($start, 1);
            $this-> last_pos   = min($start + $this-> delta - 1, $this-> nbLinks);
        }

   } // end func calcul



   /**
     * Nombre de liens
     *
     * Compte le nombre de liens n�cessaire � l'affichage de tous les enregistrements en tenant compte du mode
     * de fonctionnement de la classe (alpha-num�rique / num�rique)
     * @access private
     * @return int nombre de liens
     * @since 3.0
     */
    public function linkCount()
    {
        if (!empty($this-> alphaColumn))             return ceil(count($this-> aAlpha) / $this-> alphaEncaps);
      else if ($this-> perPage < $this-> nbRecord) return ceil($this-> nbRecord / $this-> perPage);
        else return 1;
    } // end func linkCount



    /**
     *
     *
     * @access private
     * @return int
     * @since 2.0
     */
    public function getColspan()
    {
        return count($this-> aPage) + $this-> addToColspan;
    } // end func getColspan


    /**
     *
     * @access private
     * @return string
     * @since 2.0
     */
    public function getNextPage()
    {
        ($this-> currentPage < $this-> nbLinks) ? $link = '<a href="' . $this-> getLink($this-> currentPage + 1) . '" ' . $this-> linkClass . ' title="'.CopixI18N::get('copix:pager.messages.nextPage').'">'. $this-> nextPage .'</a>' : $link = '';
        return $link;
    } // end func getNextPage


   /**
     * Retourne le lien html pour acc�der � la page pr�c�dente
     *
     * Si la page courante est la premi�re page, cette m�thode retourne une cha�ne vide
     *
     * @access private
     * @return string
     * @since 3.1.a
     */
    public function getPreviousPage()
    {
        ($this-> currentPage != 1) ? $link = '<a href="' . $this-> getLink($this-> currentPage - 1) . '" ' . $this-> linkClass . ' title="'.CopixI18N::get('copix:pager.messages.previousPage').'">'. $this-> previousPage .'</a>' : $link = '';
        return $link;
    } // end func getPreviousPage



    /**
     * Retourne le lien html d'acc�s � la premi�re page du template
     */
    public function getFirstPage()
    {
        return ($this-> currentPage != 1) ? $link = '<a href="' . $this-> getLink(1) . '" ' . $this-> linkClass . ' title="'.CopixI18N::get('copix:pager.messages.firstPage').'">'. $this-> firstPage .'</a>' : $link = '';
    } // end func getFirstPage



    /**
     * Retourne le lien html pour acc�der � la derni�re page
     *
     * access private
     * return string
     */
    public function getLastPage()
    {
        return ($this-> currentPage != $this-> nbLinks) ? $link = '<a href="' . $this-> getLink($this-> nbLinks) .'" ' . $this-> linkClass . ' title="'.CopixI18N::get('copix:pager.messages.lastPage').'">'. $this-> lastPage .'</a>' : $link = '';
    } // end func getLastPage


    /**
     * Num�ro repr�sentant le d�but des enregistrements affich�s
     *
     * access private
     * return int
     */
    public function getFrom()
    {
        if ($this-> currentPage == 1) return 1;

        $reste = $this-> perPage * $this-> currentPage - $this-> nbRecord;
        ($reste <= 0) ? $r = ($this-> perPage * $this-> currentPage) - $this-> perPage : $r = $this-> nbRecord - ($this-> nbRecord - ($this-> perPage * $this-> currentPage - $this-> perPage));
        return $r + 1;
    } // end func getFrom



    /**
     * Num�ro repr�sentant la fin des enregistrements affich�s
     *
     * access private
     * return int
     */
    public function getTo()
    {
        if ($this-> currentPage == $this-> nbLinks) return $this-> nbRecord;

        return $this-> currentPage * $this-> perPage;
    } // end func getTo



    /**
     * Style du lien courant
     *
     * Retourne le lien courant sp�cifi� par $link_name
     * @access private
     * @return string
     */
    public function getCurSpanPage($link_name)
    {
        return $this-> curPageSpanPre . $link_name . $this-> curPageSpanPost;
    } // end func getCurSpanPage



    /**
     * Retourne dans un tableau tous les liens � afficher pour le multipage
     *
     * Retourne le lien courant sp�cifi� par $link_name
     * @access private
     * @param string $repeat Morceau du template contenant la zone � r�p�ter
     * @return string
     */
    public function getAllPage()
    {
        $z     = 0;
        $page  = '';
        $liens = '';

        While ($z < $this-> nbLinks) {
            $z++;

            if ((($z >= $this-> first_pos) && ($z <= $this-> last_pos)) || ($this-> delta == $this-> nbLinks) ) {

                ($this-> multipage_mode == 'alphabetique') ? $linkName = $this-> getAlphaLinkValue($z) : $linkName = $z;

                if ($z != 1 && $z != $this-> first_pos)   $page = $this-> separator;

                if ($z == $this-> currentPage) $page .= $this-> getCurSpanPage($linkName);
                else                       $page .= '<a href="'. $this-> getLink($z) . '" ' . $this-> linkClass . '>'. $linkName .'</a>';

                //$liens .= preg_replace($this-> getTplPattern('PAGE'), $page, $repeat);
                $liens .= $page;
                // Pour la m�thode getAll() & getColspan
                $this-> aPage[] = $page;

             // AJOUT DAVID DURET 2004-02-09 10:50
                $this-> result[] = array(
                   'PageNumber'   => $z,
                   'PageIsCurrent'   => ( $z == $this-> currentPage ) ? TRUE : FALSE,
                   'PageUri'      => $this-> getLink($z),
                   'PageLabel'      => $linkName,
                   'PageLink'      => ''
                );
         }
      }

        return $liens;
    } // end func getAllPage


    /**
     * Nom du lien en mode alpha-num�rique
     *
     * Retourne le nom du lien $index (num�ro de la page) lorsque la classe fonctionne en mode alphanum�rique.
     * Elle se base sur les propri�t�s $alphaEncaps et $aAlpha.
     * @access private
     * @return string nom du lien
     * @since 3.1
     */
    public function getAlphaLinkValue($index)
    {
        if ($this-> alphaEncaps == 1) return $this-> aAlpha[$index-1];

        $return     = '[';
        $aIndex     = $this-> getAlphaIndex($index);
        $IndexStart = $aIndex[0];
        $IndexEnd   = $aIndex[1];

        unset($aIndex);

        While ($IndexStart != $IndexEnd) {

            if (!IsSet($this-> aAlpha[$IndexStart])) break;

            $return .= $this-> aAlpha[$IndexStart];
            $IndexStart++;

            if ($IndexStart < $IndexEnd && IsSet($this-> aAlpha[$IndexStart])) $return .= '-';
        }

        $return .= ']';

        return $return;
    } // end func getAlphaLinkValue



    /**
     * Lien pour la page sp�cifi�e
     *
     * Retourne l'URI compl�te pour acc�der � la page $num_page. Le retour diff�re en fonction de la propri�t� $tplUri.
     * @param  int num_page, num�ro de la page pour laquelle il faut cr�er le lien
     * @access private
     * @return string URI d'acc�s � la page
     * @since 2.0
     */
    public function getLink($num_page)
    {
        if (!empty($this-> tplUri)) return preg_replace($this-> getTplPattern('PAGE'), $num_page, $this-> tplUri);
        else                        return $this-> page_file . $this-> varUrl . '=' . $num_page;
    } // end func getLink



    /**
     * Lit le fichier pass� en param�tre et retourne son contenu
     *
     * @param string $file le chemin d'acc�s complet au fichier (inclu le nom du fichier)
     * @access private
     * @return string $date le contenu du fichier
     * @exception bool false
     * @since 1.0
     */
   public function getFile($file)
   {
      if (!Is_File($file)) return false;

      $fp   = @fopen($file, 'r');
      $data = @fread($fp, filesize($file));
            @fclose($fp);

      return $data;
   } // end func getFile



    /**
     * G�n�re l'expression r�guli�re propre au nom de la variable $var_name
     *
     * La g�n�ration se base sur les propri�t�s "$tplVarBegin" & "$tplVarEnd" et sur la chaine pass�e en param�tre
     * @access private
     * @param string $var_name le nom de la variable du template
     * @return string pattern
     * @since 3.1a
     */
    public function getTplPattern($var_name)
    {
        return '/' . str_replace('/', '\/', preg_quote($this-> tplVarBegin)) . '(' . $this-> tplVarName[$var_name] .')' . str_replace('/', '\/', preg_quote($this-> tplVarEnd)) . '/';
    } // end func getTplPattern



    /**
     * G�n�re l'expression r�guli�re pour r�cuperer la zone � r�p�ter dans le template
     *
     * La g�n�ration se base sur les propri�t�s "$tplVarLoopBegin" & "$tplVarLoopEnd"
     * @access private
     * @return string pattern
     * @since 3.1a
     */
    public function getTplLoopPat()
    {
        return '/' . str_replace('/', '\/', preg_quote($this-> tplVarLoopBegin)) . '(.*)' . str_replace('/', '\/', preg_quote($this-> tplVarLoopEnd)) . '/';
    } // end func getTplLoopPat



    /**
     * Evalue la pr�sence d'une variable dans le fichier template
     *
     * La propri�t� $tpl_data doit exister avant l'appel � cette m�thode.
     * La chaine pass�e en param�tre correspond � l'indice du tableau tplVarName[] d�fini dans le fichier de configuration
     * @access private
     * @return int nombre d'occurence de $search dans $tpl_data
     * @see getTplPattern()
     */
    public function PregMatch($search)
    {
        return preg_match($this-> getTplPattern($search), $this-> tpl_data);
    } // end func PregMatch



    /**
     * Evalue la pr�sence d'une zone � r�peter dans le fichier template
     *
     * La propri�t� $tpl_data doit exister avant l'appel � cette m�thode.
     * @access private
     * @return int nombre d'occurence de la zone � r�peter dans $tpl_data
     * @see getTplLoopPat()
     */
    public function PregMatchLoop()
    {
        return preg_match($this-> getTplLoopPat(), $this-> tpl_data, $test);
    } // end func PregMatchLoop



    /**
     * Renvoie un tableau contenant toutes les propri�t�s de la classe
     *
     * Sert � ceux qui ne veulent pas passer par les templates de la classe
     * @return array
     * @access public
     */
    public function getAll()
    {
        $this-> setup();

        $array = Array(
            $this-> tplVarName['NBRECORD']      => $this-> nbRecord,
            $this-> tplVarName['NEXT_PAGE']     => $this-> getNextPage(),
            $this-> tplVarName['PREVIOUS_PAGE'] => $this-> getPreviousPage(),
            $this-> tplVarName['FIRST_PAGE']    => $this-> getFirstPage(),
            $this-> tplVarName['LAST_PAGE']     => $this-> getLastPage(),
            $this-> tplVarName['CURRENT_PAGE']  => $this-> currentPage,
            $this-> tplVarName['TOTAL_PAGE']    => $this-> nbLinks,
            $this-> tplVarName['LIMIT']         => $this-> perPage,
            $this-> tplVarName['FROM']          => $this-> getFrom(),
            $this-> tplVarName['TO']            => $this-> getTo()
        );

        if (!IsSet($this-> aPage)) $this-> getAllPage();

        // AJOUT DAVID DURET 2004-02-09 10:50
      $array['RawPages'] = $this-> result;

        $array[$this-> tplVarName['PAGE']] = $this-> aPage;

        // Le parsing de la variable colspan ne peut avoir lieu qu'apr�s l'appel � la m�thode getAllPage() (d� � l'instanciation de la propri�t� aPage)
        $array[$this-> tplVarName['COLSPAN']] = $this-> getColspan();

        return $array;
    } // end func getAll



   /**
     * Lance les fonctions pour parser le template
     *
     * Cherche et remplace dans le template les variables connues en lan�ant les traitements associ�s
     * @access private
     * @see getFile(), parseLoop(), parseColspan(), parseNbRecord(), parseNextPage(), parsePreviousPage(), parseFirstPage(), parseLastPage(), parseCurrentPage(), parseTotalPage()
     * @since 2.0
     */
   public function parsetemplate()
   {
      $tpl = new CopixTpl ();
      $tpl->assign ('LOOP'          , $this-> getAllPage());
      $tpl->assign ('COLSPAN'       , $this-> getColspan());
      $tpl->assign ('NBRECORD'      , $this-> nbRecord);
      $tpl->assign ('NEXT_PAGE'     , $this-> getNextPage());
      $tpl->assign ('PREVIOUS_PAGE' , $this-> getPreviousPage());
      $tpl->assign ('FIRST_PAGE'    , $this-> getFirstPage());
      $tpl->assign ('LAST_PAGE'     , $this-> getLastPage());
      $tpl->assign ('CURRENT_PAGE'  , $this-> currentPage);
      $tpl->assign ('TOTAL_PAGE'    , $this-> nbLinks);
      $tpl->assign ('LIMIT'         , $this-> perPage);
      $tpl->assign ('FROM'          , $this-> getFrom());
      $tpl->assign ('TO'            , $this-> getTo());
      $this-> tpl_data =  $tpl-> fetch ($this-> template);
   } // end func parsetemplate



   /**
     * Affichage du multipage
     *
     * Affiche directement en sortie le contenu du template apr�s traitement
     * @access public
     * @return void
     * @since 1.0
     */
   public function pMultipage()
   {
        $this-> setup();
      echo $this-> tpl_data;
   } // end func pMultipage



   /**
     * Renvoie le r�sultat du template une fois pars�
     *
     * @access public
     * @return string le template une fois pars�
     * @since 1.0
     */
   public function getMultipage()
   {
        // On balance les traitements
        $this-> setup();
      return $this-> tpl_data;
   } // end func getMultipage



   /**
     * Reconstruction de l'url de la page en cours
     *
     * @access private
     * @return string URL
     */
   public function buildUrl()
   {
      $params = $_GET;
        $uri    = basename($_SERVER['PHP_SELF']);

      foreach ($params as $key => $value) {
            if ($this-> encodeVarUrl === TRUE) $value = urlencode($value);

         if ($key != $this-> varUrl) $queryString[] = $key . '=' . $value;
      }

      if (IsSet($queryString) && count($queryString) > 0 ) $uri .= '?' . implode('&amp;', $queryString) . '&';
      else $uri .= '?';

        return $uri;
   } // end func buildUrl



   /**
     * Lit le fichier de configuration et en exploite le contenu
     *
     * @see buildUrl()
     * @return void
     * @access private
     * @since 1.0
     */
   function &config() {
        // Lecture du fichier de configuration
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CopixPager.config.php';

        require ($file);

        // Rappatriement des donn�es en provenance du fichier de configuration
        $this->setConfig($options['PARAMS']);
        $this->setConfig($options['PAGER']);
        $this->setConfig($options['ADVANCED']);
        //$this->setConfig($options['TPL_VAR_NAME'], 'tplVarName');

        // Passage en entit�s HTML
        if ($this-> toHtmlEntities === TRUE) $this-> encodeHtml();

      // Nom du fichier
      $this-> page_file = $this-> buildUrl();

        // Ajout de l'attribut html "class" pour les liens
        if (!empty($this-> linkClass)) $this-> linkClass = ' class="' . $this-> linkClass . '" ';

      // Initialisation des valeurs par d�faut :
      $this-> nbLinks = 1;

        if (!IsSet($_GET[$this-> varUrl]) || $_GET[$this-> varUrl] == 0){
            $_GET[$this-> varUrl] = 1;
        }

        return $options;

   } // end func config


    /**
     * Convertit les caract�res sp�ciaux en entit�s HTML
     *
     * Uniquement pour les variables pouvant contenir des libell�s
     * @access private
     * @return void
     * @since 3.2a
     */
    public function encodeHtml()
    {
        function easyEncode(&$var)
        {
            $var = htmlentities($var);
        }

        // Encodage des variables
        easyEncode($this-> nextPage);
        easyEncode($this-> previousPage);
        easyEncode($this-> lastPage);
        easyEncode($this-> firstPage);
    }



    /**
     * Instancie les propri�t�s contenu dans le param�tre $array
     *
     * @param array $array
     * @param string $put_in_array Permet d'ajouter les donn�es dans une propri�t� de type tableau qui prendra le contenu de cette variable comme nom du tableau
     * @return void
     */
    public function setConfig($array, $put_in_array = '')
    {
        if (!Empty($put_in_array) && !isSet($this-> $put_in_array)) $this-> $put_in_array = Array();

        foreach ((array)$array as $option => $value) {

            if (isSet($this-> $put_in_array)) {
                $test =& $this-> $put_in_array;
                if (Empty($test[$option])) $test[$option] = $value;
            } elseif (!IsSet($this-> $option)) $this-> $option = $value;
        }

        if (isSet($test)) unset($test);
    } // end func setConfig



    /**
     * Teste le param�trage de la classe.
     *
     * En cas d'erreur, arr�te le script
     * @access private
     * @return void
     */
    public function testParams()
    {
        if (!is_int($this-> perPage) || $this-> perPage <= 0) trigger_error('Propri�t� <b>perPage</b> mal configur�e <br>', E_USER_ERROR);
        if ((!is_int($this-> delta) || $this-> delta < 0)) trigger_error('Propri�t� <b>delta</b> mal configur�e <br>', E_USER_ERROR);
        //if (!empty($this-> template) && !is_file($this-> tplDir . $this-> template)) trigger_error('template <b>' . $this-> tplDir . $this-> template .'</b> introuvable <br>', E_USER_ERROR);
        if (!is_int($this-> addToColspan)) trigger_error('Propri�t� <b>addToColspan</b> mal configur�e <br>', E_USER_ERROR);
        if (!is_bool($this-> alwaysShow)) trigger_error('Propri�t� <b>alwaysShow</b> mal configur�e <br>', E_USER_ERROR);
        if (!is_string($this-> alphaColumn)) trigger_error('Propri�t� <b>alphaColumn</b> mal configur�e <br>', E_USER_ERROR);
        if (!is_int($this-> alphaEncaps) || $this-> alphaEncaps <= 0) trigger_error('Propri�t� <b>alphaEncaps</b> mal configur�e <br>', E_USER_ERROR);
        if (!empty($this-> tplUri) && (!is_string($this-> tplUri) || !preg_match($this-> getTplPattern('PAGE'), $this-> tplUri))) trigger_error('Propri�t� <b>tplUri</b> mal configur�e. V�rifiez la pr�sence de la variable PAGE telle qu\'elle est configur�e dans le fichier de configuration<br>', E_USER_ERROR);
        if (empty($this-> varUrl) || !is_string($this-> varUrl)) trigger_error('Propri�t� <b>varUrl</b> mal configur�e. V�rifiez son param�trage dans le fichier de configuration <br>', E_USER_ERROR);
        if (!is_bool($this-> toHtmlEntities)) trigger_error('Propri�t� <b>toHtmlEntities</b> mal configur�e <br>', E_USER_ERROR);
        if (!is_bool($this-> encodeVarUrl)) trigger_error('Propri�t� <b>encodeVarUrl</b> mal configur�e <br>', E_USER_ERROR);
        if ($this-> display != 'sliding' && $this-> display != 'jumping') trigger_error('Propri�t� <b>display</b> mal configur�e <br>', E_USER_ERROR);

    } // end func testParams



    /**
     * D�finit la propri�t� firstline & instancie correctement la valeur de varUrl
     *
     * @access private
     * @since 3.2
     * @return void
     */
    public function setRestriction()
    {
        ($_GET[$this-> varUrl] == '' || (int) $_GET[$this-> varUrl] < 0) ? $this-> currentPage = 1 : $this-> currentPage = $_GET[$this-> varUrl];

      if (($_GET[$this-> varUrl] == 1) || (!$_GET[$this-> varUrl])) $this-> firstline = 0;
      elseif ($_GET[$this-> varUrl] == 2)                           $this-> firstline = $this-> perPage;
      else                                                          $this-> firstline = $this-> perPage * $_GET[$this-> varUrl] - $this-> perPage;
    }



    /**
     * Param�tre la classe suivant le fichier "config.php".
     *
     * Cr�e la connexion � la base
     * Instancie la propri�t� $aSql
     * @access private
     * @return void
     * @since 1.0
     */
   public function setup()
   {
        if ( FALSE === $this-> _initialized ) {
            $this-> config();
            $this-> testParams();
            $this-> setRestriction();
            $this-> init();
            $this-> calcul();

            // Il est possible de ne pas avoir sp�cifi� de template si l'on passe par la m�thpode GetAll()
            if (!empty($this-> template)) {

                // La variable alwaysShow d�termine si l'on affiche ou pas le template
                // dans le cas o� il n'y a qu'une seule page de r�sultat
                if ($this-> alwaysShow === true) $this-> parsetemplate();
                elseif ($this-> nbLinks != 1)     $this-> parsetemplate();
            }

            $this-> data = $this-> getRecords();
            $this-> close();

            $this->_initialized = TRUE;
        }
   } // end func setup



    /**
     * Affiche les propri�t�s de l'objet Multipage
     *
     * @access private
     * @return void
     */
    public function viewObject()
    {
        print '<xmp>';
        print_r($this);
        print '</xmp>';
    } // end func viewObject

} // end class Multipage
