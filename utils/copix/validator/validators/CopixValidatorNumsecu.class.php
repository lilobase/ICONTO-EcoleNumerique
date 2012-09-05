<?php
/**
 * @package		copix
 * @subpackage	validator
 * @author		Brice Favre
 * @copyright	CopixTeam
 * @link		http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Validation d'un numéro de téléphone
 * @package		copix
 * @subpackage	validator
 */
class CopixValidatorNumsecu extends CopixAbstractValidator
{
    /**
     * Methode qui fait les tests sur la $pValue
     *
     * @param mixed $pValue La valeur
     */
    public function _validate ($pValue)
    {
        // Récupération
        $gender = $this->getParam ('gender', null);
        if ($pValue == '') {
            return true;
        }
        try {
            CopixFormatter::getNumeroSecuriteSociale (substr ($pValue, 0, -2), substr ($pValue, -2));
        } catch (CopixException $e) {
            return $e->getMessage ();
        }

        if ($gender !== null){
            if (! preg_match ('/^'.$gender.'/', $pValue)) {
                return _i18n ('copix:copixformatter.error.incorrectdatasecu');
            }
        }
        return true;

    }
}
