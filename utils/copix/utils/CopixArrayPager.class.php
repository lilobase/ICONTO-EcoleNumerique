<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: CopixArrayPager.class.php,v 1.4 2006-10-04 16:21:18 fmossmann Exp $

* @author   Bertrand Yan

*           see copix.aston.fr for other contributors.

* @copyright 2001-2005 CopixTeam

* @link      http://copix.org

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/

class CopixArrayPager extends CopixPager
{
    /**

     * Tableau des données à traiter

     *

     * Valeur par défaut : tableau vide

     * @var string $query

     * @see createSQL(), sql2array()

     */

    public $recordSet;



    public function CopixArrayPager($options)
    {
        $this-> recordSet = '';

        parent::CopixPager($options);

    }





    /**

     * Retourne le nombre d'enregistrement contenu dans le tableau des données

     *

     * @access private

     * @since 3.2

     */

    public function getNbRecord()
    {
        return count($this-> recordSet);

    } // end func getNbRecord







    /**

     * Retourne le tableau des données "découpé"

     *

     * @access private

     * @return array

     * @since 3.2

     */

    public function getRecords()
    {
        $aTmp = Array();



        for ($i = $this-> firstline; $i < ($this-> firstline + $this-> perPage); $i++) {

            $aTmp[$i] = $this-> recordSet[$i];



            if (!isSet($this-> recordSet[$i+1])) {

                break;

            }

        }



        return $aTmp;

    } // end func getRecords



    /**

     * Initialisation de la classe mode tableau

     *

     * @access private

     * @return void

     * @since 3.2

      */

    public function init()
    {
        //var_dump($this);
        //if (!is_array($this-> recordSet)) trigger_error('Propriété <b>recordSet</b> mal configurée <br>', E_USER_ERROR);
        if (!($this->recordSet)) trigger_error('Propriété <b>recordSet</b> mal configurée <br>', E_USER_ERROR);

    }



    /**

     * Termine l'appel à la classe

     *

     * @access public/private

     * @return void

     * @since 3.2

      */

    public function close()
    {
        unset($this-> recordSet);

        return true;

    }

}

