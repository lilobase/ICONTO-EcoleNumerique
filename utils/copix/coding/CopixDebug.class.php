<?php
/**
 * @package  	copix
 * @subpackage	devtools
 * @author		Steevan BARBOYON, Guillaume Perréal
 * @copyright	CopixTeam
 * @link		http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Offre des possibilités pour débuguer
 *
 * @package		copix
 * @subpackage	devtools
 */
class CopixDebug
{
    /**
     * Section publique, pour le formatage de _getFormated
     */
    const FR_SECTION_PUBLIC = 0;

    /**
     * Section privée, pour le formatage de _getFormated
     */
    const FR_SECTION_PRIVATE = 1;

    /**
     * Section protégée, pour le formatage de _getFormated
     */
    const FR_SECTION_PROTECTED = 2;

    /**
     * Nom d'un fichier, pour le formatage de _getFormated
     */
    const FR_FILENAME = 3;

    /**
     * Mot clef, pour le formatage de _getFormated
     */
    const FR_KEYWORD = 4;

    /**
     * Commentaire, pour le formatage de _getFormated
     */
    const FR_COMMENT = 5;

    /**
     * Type de variable, pour le formatage de _getFormated
     */
    const FR_TYPE = 6;

    /**
     * Nom de constante, pour le formatage de _getFormated
     */
    const FR_CONST = 7;

    /**
     * Nom de variable, pour le formatage de _getFormated
     */
    const FR_VAR = 8;

    /**
     * Chaine de caractères, pour le formatage de _getFormated
     */
    const FR_STRING = 9;

    /**
     * Noms des éléments dans la déclaration, pour le formatage de _getFormated
     */
    const FR_DECLARATION = 10;

    /**
     * Value de variable / constante / propriété, pour le formatage de _getFormated
     */
    const FR_VALUE = 11;

    /**
     * Nom d'une fonction / méthode, pour le formatage de _getFormated
     */
    const FR_FUNCTIONNAME = 12;

    /**
     * Type de variable integer, pour le formatage de _getFormated
     */
    const FR_INT = 13;

    /**
     * Type de variable object, pour le formatage de _getFormated
     */
    const FR_OBJECT = 14;

    /**
     * Type de variable boolean, pour le formatage de _getFormated
     */
    const FR_BOOLEAN = 15;

    /**
     * Type de variable null, pour le formatage de _getFormated
     */
    const FR_NULL = 16;

    /**
     * Indique si la méthode appelée voulait formater le retour
     *
     * @var boolean
     */
    private static $_formatReturn = true;

    /**
     * Indique si la méthode appelée voulait un retour ou un affichage
     *
     * @var boolean
     */
    private static $_return = false;

    /**
     * Nombre d'espaces pour la declaration en cours
     *
     * @var int
     */
    private static $_declarationSpaces = 0;

    /**
     * Nombre d'espaces pour les dumps en cours
     *
     * @var int
     */
    private static $_dumpSpaces = 0;

    /**
     * Nombre d'espaces pour les sections en cours
     *
     * @var int
     */
    private static $_sectionSpaces = 0;

    /**
     * Indique dans quel niveau de dump on est
     *
     * @var int
     */
    private static $_dumpIndex = 0;

    /**
     * Retourne les espaces à afficher en fonction du formattage du retour demandé, et des espaces courants pour le dump
     *
     * @return string
     */
    private static function _getSpaces ($pNbrSpaces)
    {
        $str = (self::$_formatReturn) ? '&nbsp;' : ' ';
        $toReturn = '';
        for ($boucle = 0; $boucle < $pNbrSpaces; $boucle++) {
            $toReturn .= $str;
        }
        return $toReturn;
    }

    /**
     * Retourne une chaine contenant le retour à la ligne, en fonction du formattage du retour demandé
     *
     * @return string
     */
    private static function _getEndLine ()
    {
        return (self::$_formatReturn) ? '<br />' : "\n";
    }

    /**
     * Commence une section, avec la possibilité de l'afficher / cacher
     *
     * @param string $pTitle
     * @param int $pType
     * @param boolean $pDefaultOpened
     * @return string
     */
    private static function _beginSection ($pTitle, $pType, $pDefaultOpened = true)
    {
        if (self::$_formatReturn) {
            $imgUp = _resource ('img/tools/moveup.png');
            $imgDown = _resource ('img/tools/movedown.png');
            $img = ($pDefaultOpened) ? $imgUp : $imgDown;
            $sectionId = uniqid ();
            $divDisplay = ($pDefaultOpened) ? "''" : "none";

            $toReturn = '<span style="cursor: pointer" onclick="';
            $toReturn .= 'var copixdebug_div = document.getElementById (\'div_' . $sectionId . '\');';
            $toReturn .= 'var copixdebug_img = document.getElementById (\'img_' . $sectionId . '\');';
            $toReturn .= 'if (copixdebug_div.style.display == \'\') {';
            $toReturn .= 'copixdebug_div.style.display = \'none\';';
            $toReturn .= 'copixdebug_img.src = \'' . $imgDown . '\';';
            $toReturn .= '} else {';
            $toReturn .= 'copixdebug_div.style.display = \'\';';
            $toReturn .= 'copixdebug_img.src = \'' . $imgUp . '\';';
            $toReturn .= '}';
            $toReturn .= '"><img id="img_' . $sectionId . '" src="' . $img . '" /> ';
            $toReturn .= self::_getFormated ($pTitle, $pType);
            $toReturn .= '</span><div style="display:' . $divDisplay . '" id="div_' . $sectionId . '">';
        } else {
            $toReturn = $pTitle;
        }

        return $toReturn;
    }

    /**
     * Termine la section en cours
     *
     * @return string
     */
    private static function _endSection ()
    {
        return (self::$_formatReturn) ? '</div>' : null;
    }

    /**
     * Retourne une chaine formatée entre $pBegin et $pEnd, si self::$_formatReturn est à true
     *
     * @param string $pStr Chaine à formater ou non
     * @param int $pType Type de formattage à effectuer, utiliser les constantes self::FR_
     * @return string
     */
    private static function _getFormated ($pStr, $pType)
    {
        if (!self::$_formatReturn) {
            return $pStr;
        }

        switch ($pType) {
            case self::FR_SECTION_PUBLIC : return '<b><font color="green">' . $pStr . '</font></b>'; break;
            case self::FR_SECTION_PRIVATE : return '<b><font color="red">' . $pStr . '</font></b>'; break;
            case self::FR_SECTION_PROTECTED : return '<b><font color="red">' . $pStr . '</font></b>'; break;
            case self::FR_FILENAME : return '<i>' . $pStr . '</i>'; break;
            case self::FR_KEYWORD : return  '<i>' . $pStr . '</i>'; break;
            case self::FR_COMMENT : return '<font color="#808080">' . $pStr . '</font>'; break;
            case self::FR_TYPE : return '<i>' . $pStr . '</i>'; break;
            case self::FR_CONST : return '<b>' . $pStr . '</b>'; break;
            case self::FR_VAR : return '<font color="#663300">' . $pStr . '</font>'; break;
            case self::FR_INT : return '<font color="green">' . $pStr . '</font>'; break;
            case self::FR_STRING : return '<font color="blue">\'' . htmlentities ($pStr, ENT_COMPAT, 'UTF-8') . '\'</font>'; break;
            case self::FR_DECLARATION : return '<b>' . $pStr . '</b>'; break;
            case self::FR_VALUE : return '<font color="green">' . htmlentities ($pStr, ENT_COMPAT, 'UTF-8') . '</font>'; break;
            case self::FR_FUNCTIONNAME : return '<b>' . $pStr . '</b>'; break;
            case self::FR_OBJECT : return '<font color="green">' . $pStr . '</font>'; break;
            case self::FR_BOOLEAN : return '<b>' . $pStr . '</b>'; break;
            case self::FR_NULL : return '<b>' . $pStr . '</b>'; break;
        }
    }

    /**
     * Retourne le _getFormated sur une valeur, en vérifiant le type (FR_STRING, FR_OBJECT, FR_INT, FR_VALUE)
     *
     * @param mixed $pValue Valeur de la variable à formater
     * @return string
     */
    private static function _getFormatedValue ($pValue)
    {
        $toReturn = null;

        if (is_object ($pValue) || is_array ($pValue)) {
            $toReturn = self::_dump ($pValue);
        } elseif (is_string ($pValue)) {
            $toReturn = self::_getFormated ($pValue, self::FR_STRING);
        } elseif (is_int ($pValue)) {
            $toReturn = self::_getFormated ($pValue, self::FR_INT);
        } elseif (is_bool ($pValue)) {
            $toReturn = self::_getFormated (($pValue) ? 'true' : 'false', self::FR_BOOLEAN);
        } elseif (is_null ($pValue)) {
            $toReturn = self::_getFormated ('null', self::FR_NULL);
        } else {
            $toReturn = self::_getFormated ($pValue, self::FR_VALUE);
        }

        return $toReturn;
    }

    /**
     * Retourne la déclaration d'un array en chaine (exemple : 'clef' => 'valeur', 2 => true)
     *
     * @param array $pArray
     * @return string
     */
    private static function _array_to_str ($pArray)
    {
        $toReturn = array ();
        foreach ($pArray as $key => $value) {
            $toReturn[] = self::_getFormatedValue ($key) . ' => ' . self::_getFormatedValue ($value);
        }
        return implode (', ', $toReturn);
    }

    /**
     * Retourne le type de la variable $pVar
     *
     * @param mixed $pVar
     * @return string
     */
    private static function _get_type ($pVar)
    {
        $toReturn = gettype ($pVar);
        if ($toReturn == 'string') {
            $toReturn = 'string (' . strlen ($pVar) . ')';
        } elseif (is_null ($pVar)) {
            $toReturn = null;
        } elseif (is_object ($pVar)) {
            $toReturn = get_class ($pVar);
        }

        return $toReturn;
    }

    /**
     * Effectue un dump sur une variable normale
     *
     * @param mixed $pVar Variable à dumper
     * @return string
     */
    private static function _var_dump ($pVar)
    {
        $endLine = self::_getEndLine ();
        $dump = array ();
        $dumpSpaces = self::_getSpaces (self::$_dumpSpaces);
        $declarationSpaces = self::_getSpaces (self::$_declarationSpaces);

        if (is_string ($pVar)) {
            $dump = self::_getFormated ('string (' . strlen ($pVar) . ')', self::FR_TYPE) . ' = ' . self::_getFormatedValue ($pVar);
        } elseif (is_null ($pVar)) {
            $dump = 'NULL';
        } else {
            $dump = self::_getFormated (gettype ($pVar), self::FR_TYPE) . ' = ' . self::_getFormatedValue ($pVar);
        }

        return $dump;
    }

    /**
     * Effectue un dump sur un array
     *
     * @param object $pArray Array à dumper
     * @return string
     */
    private static function _array_dump ($pArray, $pIsJustDeclaration)
    {
        if (!is_array ($pArray)) {
            return _i18n ('copix:copixdebug.error.invalidVarType', 'array');
        }

        $endLine = self::_getEndLine ();
        $dump = array ();
        $dumpSpaces = self::_getSpaces (self::$_dumpSpaces);
        $declarationSpaces = self::_getSpaces (self::$_declarationSpaces);

        $dump[0] = self::_getFormated ('array', self::FR_DECLARATION) . ' (';
        if ($pIsJustDeclaration) {
            return $dump[0] . '...)';
        }

        foreach ($pArray as $key => $value) {
            $index = count ($dump);
            $type = (is_string ($key)) ? self::FR_STRING : self::FR_INT;
            $dump[$index] = $dumpSpaces . self::_getFormated ($key, $type) . ' = ';
            $dump[$index] .= self::_getFormatedValue ($value);
        }
        $dump[] = $declarationSpaces . ')';

        return implode ($endLine, $dump);
    }

    /**
     * Effectue un dump sur un objet
     *
     * @param object $pObject Objet à dumper
     * @return string
     * @todo Cas des propriétés protégées et privées statiques non géré
     */
    private static function _object_dump ($pObject, $pIsJustDeclaration)
    {
        return var_export ($pObject, true);

        if (!is_object ($pObject)) {
            return _i18n ('copix:copixdebug.error.invalidVarType', 'object');
        }

        $config = CopixConfig::instance ();
        $sectionSpaces = self::_getSpaces (self::$_sectionSpaces);
        $dumpSpaces = self::_getSpaces (self::$_dumpSpaces);
        $dump = array ();
        $class = get_class ($pObject);
        $reflection = new ReflectionClass ($class);
        $parent = $reflection->getParentClass ();
        $endLine = self::_getEndLine ();

        // ------------------------------------------------------------------
        // recherche du nom de la classe, de l'extends et du fichier déclarant la classe
        // ------------------------------------------------------------------

        $file = str_replace ('\\', '/', $reflection->getFileName ());
        $arFileDirs = explode ('/', $file);
        $countFileDirs = count ($arFileDirs);
        if ($countFileDirs > 4) {
             $fileDir =
                 '(...)/' .
                 $arFileDirs[$countFileDirs - 4] . '/' .
                 $arFileDirs[$countFileDirs - 3] . '/' .
                 $arFileDirs[$countFileDirs - 2] . '/' .
                 $arFileDirs[$countFileDirs - 1];
        } else {
            $fileDir = $file;
        }
        $declaration = ($reflection->isFinal ()) ? self::_getFormated ('final', self::FR_KEYWORD) . ' ' : null;
        $declaration .= ($reflection->isAbstract ()) ? self::_getFormated ('abstract', self::FR_KEYWORD) . ' ' : null;
        $declaration .= ($reflection->isInterface ()) ? self::_getFormated ('interface', self::FR_KEYWORD) . ' ' : self::_getFormated ('object', self::FR_KEYWORD) . ' ';
        $declaration .= self::_getFormated ($class, self::FR_DECLARATION);
        if ($parent) {
            $declaration .= ' ' . self::_getFormated ('extends', self::FR_KEYWORD) . ' ' . self::_getFormated ($parent->name, self::FR_DECLARATION);
        }
        $interfaces = $reflection->getInterfaces ();
        if (count ($interfaces) > 0) {
            $declaration .= ' ' . self::_getFormated ('implements', self::FR_KEYWORD) . ' ' . self::_getFormated (implode (', ', array_keys ($interfaces)), self::FR_DECLARATION);
        }
        $dump[0] = $declaration . ' (' . self::_getFormated ($fileDir, self::FR_FILENAME) . ')';

        if ($pIsJustDeclaration) {
            return $dump[0] . ' (...)';
        }

        // ------------------------------------------------------------------
        // recherche des constantes
        // ------------------------------------------------------------------

        $constantes = $reflection->getConstants ();
        ksort ($constantes);

        if (count ($constantes) > 0) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.constants', count ($constantes)),
                self::FR_SECTION_PUBLIC,
                $config->copixdebug_showConstantes
            );
            foreach ($constantes as $constName => $constValue) {
                $constValueStr = self::_getFormatedValue ($constValue);
                $constTypeStr = (is_null ($constValue)) ? null : self::_getFormated (self::_get_type ($constValue), self::FR_TYPE);
                $constNameStr = self::_getFormated ($constName, self::FR_VAR);

                $dump[] = $dumpSpaces . trim ($constTypeStr . ' ' . $constNameStr) . ' = ' . $constValueStr;
            }
            $dump[count ($dump) - 1] .= self::_endSection ();
        }

        // ------------------------------------------------------------------
        // recherche des propriétés
        // ------------------------------------------------------------------

        // getProperties renvoie uniquement les propriétés déclarées dans la classe, et pas celles "ajoutées" après instanciation
        $properties = $reflection->getProperties ();
        $newProperties = array ();

        // on recherche les propriétés ajoutées "après instanciation"
        foreach ($pObject as $objectProperty => $value) {
            $isNew = true;
            foreach ($properties as $property) {
                if ($objectProperty == $property->name) {
                    $isNew = false;
                    break;
                }
            }
            if ($isNew) {
                $newProperties[$objectProperty] = $value;
            }
        }
        ksort ($newProperties);

        // tri des méthodes selon leur accessibilité (public, protected et private)
        $arPropPrivateSort = array ();
        $arPropProtectedSort = array ();
        $arPropPublicSort = array ();
        foreach ($properties as $propIndex => $propReflec) {
            if ($propReflec->isPrivate ()) {
                $arPropPrivateSort[] = $propReflec->name;
            } elseif ($propReflec->isProtected ()) {
                $arPropProtectedSort[] = $propReflec->name;
            } else {
                $arPropPublicSort[] = $propReflec->name;
            }
        }
        sort ($arPropPrivateSort);
        sort ($arPropProtectedSort);
        sort ($arPropPublicSort);

        // propriétés "ajoutées", qui ne sont pas déclarées dans la classe
        if (count ($newProperties)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.notDeclaredProperties', count ($newProperties)),
                self::FR_SECTION_PUBLIC,
                $config->copixdebug_showNotDeclaredProperties
            );

            foreach ($newProperties as $propName => $propValue) {
                $propNameStr = self::_getFormated ('$' . $propName, self::FR_VAR);
                $propType = self::_get_type ($propValue);
                $propTypeStr = (is_null ($propType)) ? null : self::_getFormated ($propType, self::FR_TYPE);
                $propValueStr = self::_getFormatedValue ($propValue);

                $index = count ($dump);
                $dump[$index] = $dumpSpaces . trim ($propTypeStr . ' ' . $propNameStr);
                if (!is_object ($propValue)) {
                    $dump[$index] .= ' = ' . $propValueStr;
                }
            }

            $dump[] = self::_endSection ();
        }

        // propriétés publiques
        if (count ($arPropPublicSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.publicProperties', count ($arPropPublicSort)),
                self::FR_SECTION_PUBLIC,
                $config->copixdebug_showPublicProperties
            );
            foreach ($arPropPublicSort as $propName) {
                if (($phpdoc = $reflection->getProperty ($propName)->getDocComment ()) !== false) {
                    $phpdocParsed = CopixPHPDoc::parse ($phpdoc);
                    if (isset ($phpdocParsed['comment'])) {
                        foreach ($phpdocParsed['comment'] as $comment) {
                            $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                        }
                    }
                }
                $propNameStr = self::_getFormated ('$' . $propName, self::FR_VAR);
                // getStaticProperties renvoie un tableau dont la clef est difficilement utilisable (caractères spéciaux ajoutés suivant l'accès)
                $isStatic = false;

                // si la propriété n'est pas statique, et n'est pas nulle (si elle est nulle, isset renverra false)
                if (isset ($pObject->$propName)) {
                    $propValue = $pObject->$propName;
                // propriété statique, ou de valeur nulle
                } else {
                    // try pour le cas la propriété n'est pas statique mais nulle, getStaticPropertyValue retourne une exception
                    try {
                        $propValue = $reflection->getStaticPropertyValue ($propName);

                        // si on arrive ici, c'est que la priopriété était bien statique
                        $isStatic = true;
                    } catch (Exception $e) {
                        // propriété non statique, mais de valeur nulle
                        $propValue = null;
                    }
                }
                $propValueStr = self::_getFormatedValue ($propValue);
                $propType = self::_get_type ($propValue);
                $propTypeStr = (is_null ($propType)) ? null : self::_getFormated ($propType, self::FR_TYPE);
                $accessStr = ($isStatic) ? self::_getFormated ('static', self::FR_KEYWORD) . ' ': null;
                $index = count ($dump);
                $dump[$index] = $dumpSpaces . trim ($propTypeStr . ' ' . $accessStr . $propNameStr);
                if (!is_object ($propValue)) {
                    $dump[$index] .= ' = ' . $propValueStr;
                }
            }
            $dump[] = self::_endSection ();
        }

        // propriétés protégées
        if (count ($arPropProtectedSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.protectedProperties', count ($arPropProtectedSort)),
                self::FR_SECTION_PROTECTED,
                $config->copixdebug_showProtectedProperties
            );
            foreach ($arPropProtectedSort as $propName) {
                if (($phpdoc = $reflection->getProperty ($propName)->getDocComment ()) !== false) {
                    $phpdocParsed = CopixPHPDoc::parse ($phpdoc);
                    if (isset ($phpdocParsed['comment'])) {
                        foreach ($phpdocParsed['comment'] as $comment) {
                            $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                        }
                    }
                }
                $dump[] = $dumpSpaces . self::_getFormated ('$' . $propName, self::FR_VAR);
            }
            $dump[] = self::_endSection ();
        }

        // propriétés privées
        if (count ($arPropPrivateSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.privateProperties', count ($arPropPrivateSort)),
                self::FR_SECTION_PRIVATE,
                $config->copixdebug_showPrivateProperties
            );
            foreach ($arPropPrivateSort as $propName) {
                if (($phpdoc = $reflection->getProperty ($propName)->getDocComment ()) !== false) {
                    $phpdocParsed = CopixPHPDoc::parse ($phpdoc);
                    if (isset ($phpdocParsed['comment'])) {
                        foreach ($phpdocParsed['comment'] as $comment) {
                            $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                        }
                    }
                }
                $dump[] = $dumpSpaces . self::_getFormated ('$' . $propName, self::FR_VAR);
            }
            $dump[] = self::_endSection ();
        }

        // ------------------------------------------------------------------
        // recherche des méthodes
        // ------------------------------------------------------------------

        $methods = $reflection->getMethods ();
        $arMethodPrivateSort = array ();
        $arMethodProtectedSort = array ();
        $arMethodPublicSort = array ();
        $arMethodInfos = array ();
        foreach ($methods as $methodIndex => $reflectMethod) {
            if (($phpdoc = $reflectMethod->getDocComment ()) !== false) {
                $parse = CopixPHPDoc::parse ($phpdoc);
                $arMethodInfos[$reflectMethod->name]['comment'] = $parse;
            }
            $methodParamsType = array ();

            // recherche de l'accès à la méthode (private, protected, public)
            if ($reflectMethod->isPrivate ()) {
                $arMethod = &$arMethodPrivateSort;
            } elseif ($reflectMethod->isProtected ()) {
                $arMethod = &$arMethodProtectedSort;
            } else {
                $arMethod = &$arMethodPublicSort;
            }

            // recherche des paramètres d'appels de la méthode
            $parameters = $reflectMethod->getParameters ();
            $nbrRequiredParams = $reflectMethod->getNumberOfRequiredParameters ();
            $requiredParams = array_slice ($parameters, 0, $nbrRequiredParams);
            $optionalParams = array_slice ($parameters, $nbrRequiredParams);

            $arMethodInfos[$reflectMethod->name]['call'] = self::_getFormated ($reflectMethod->name, self::FR_FUNCTIONNAME) . ' (';
            $isFirst = true;
            $endStrCall = null;
            for ($boucle = 0; $boucle < $reflectMethod->getNumberOfParameters (); $boucle++) {
                $name = $parameters[$boucle]->name;
                $nameStr = self::_getFormated ('$' . $name, self::FR_VAR);
                $commentParams = (isset ($arMethodInfos[$reflectMethod->name]['comment']['param'][$boucle])) ? $arMethodInfos[$reflectMethod->name]['comment']['param'][$boucle] : null;
                $type = (isset ($commentParams['type'])) ? $commentParams['type'] : null;
                $typeStr = (!is_null ($type)) ? self::_getFormated ($type, self::FR_TYPE) . ' ' : null;

                // si c'est un paramètre obligatoire
                if ($boucle < $nbrRequiredParams) {
                    $arMethodInfos[$reflectMethod->name]['call'] .= ($isFirst) ? $typeStr . $nameStr : ', ' . $typeStr . $nameStr;

                // si c'est un paramètre facultatif
                } else {
                    $value = $parameters[$boucle]->getDefaultValue ();
                    if (is_null ($type)) {
                        $type = gettype ($value);
                        $typeStr = self::_getFormated ($type, self::FR_TYPE) . ' ';
                    }
                    $valueStr = ' = ' . self::_getFormatedValue ($value);
                    $arMethodInfos[$reflectMethod->name]['call'] .= ($isFirst) ? '[' . $typeStr . $nameStr . $valueStr : ', [' . $typeStr . $nameStr . $valueStr;
                    $endStrCall .= ']';
                }
                $isFirst = false;
            }
            $arMethodInfos[$reflectMethod->name]['call'] .= $endStrCall . ')';

            $arMethodInfos[$reflectMethod->name]['isStatic'] = $reflectMethod->isStatic ();
            $arMethod[] = $reflectMethod->name;
        }
        sort ($arMethodPrivateSort);
        sort ($arMethodProtectedSort);
        sort ($arMethodPublicSort);

        // méthodes publiques
        if (count ($arMethodPublicSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.publicMethods', count ($arMethodPublicSort)),
                self::FR_SECTION_PUBLIC,
                $config->copixdebug_showPublicMethods
            );
            foreach ($arMethodPublicSort as $methodName) {
                if (isset ($arMethodInfos[$methodName]['comment']['comment'])) {
                    foreach ($arMethodInfos[$methodName]['comment']['comment'] as $comment) {
                        $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                    }
                }
                $static = ($arMethodInfos[$methodName]['isStatic']) ? self::_getFormated ('static', self::FR_KEYWORD) . ' ' : null;
                $dump[] = $dumpSpaces . $static . $arMethodInfos[$methodName]['call'];
            }
            $dump[] = self::_endSection ();
        }

        // méthodes protégées
        if (count ($arMethodProtectedSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.protectedMethods', count ($arMethodProtectedSort)),
                self::FR_SECTION_PROTECTED,
                $config->copixdebug_showProtectedMethods
            );
            foreach ($arMethodProtectedSort as $methodName) {
                if (isset ($arMethodInfos[$methodName]['comment']['comment'])) {
                    foreach ($arMethodInfos[$methodName]['comment']['comment'] as $comment) {
                        $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                    }
                }
                $static = ($arMethodInfos[$methodName]['isStatic']) ? self::_getFormated ('static', self::FR_KEYWORD) . ' ' : null;
                $dump[] = $dumpSpaces . $static . $arMethodInfos[$methodName]['call'];
            }
            $dump[] = self::_endSection ();
        }

        // méthodes privées
        if (count ($arMethodPrivateSort)) {
            $dump[] = $sectionSpaces . self::_beginSection (
                _i18n ('copix:copixdebug.section.privateMethods', count ($arMethodPrivateSort)),
                self::FR_SECTION_PRIVATE,
                $config->copixdebug_showPrivateMethods
            );
            foreach ($arMethodPrivateSort as $methodName) {
                if (isset ($arMethodInfos[$methodName]['comment']['comment'])) {
                foreach ($arMethodInfos[$methodName]['comment']['comment'] as $comment) {
                        $dump[] = $dumpSpaces . self::_getFormated ('// ' . $comment, self::FR_COMMENT);
                    }
                }
                $static = ($arMethodInfos[$methodName]['isStatic']) ? self::_getFormated ('static', self::FR_KEYWORD) . ' ' : null;
                $dump[] = $dumpSpaces . $static . $arMethodInfos[$methodName]['call'];
            }
            $dump[] = self::_endSection ();
        }

        return implode ($endLine, $dump);
    }

    /**
     * Log un var_dump de $pVar en type 'debug', en supprimant l'overload de xdebug si il est installé.
     *
     * @param mixed $pVar variabl à logguer
     */
    public static function log ($pVar)
    {
        // On ne veut pas formater l'affichage
        if (extension_loaded ('Xdebug')) {
            ini_set ('xdebug.overload_var_dump', 0);
        }
        _log (self::var_dump ($pVar, true, false), 'debug', CopixLog::INFORMATION);
    }

    /**
     * Affiche un contenu dans un div et avec des infos sur le fichier appelant
     *
     * @param string $pContent Contenu à afficher.
     */
    private static function _outputFormatted (&$pContent)
    {
        if (! headers_sent ()){
            @header ("Content-Type: text/html;charset=UTF-8");
        }
        echo '<div style="border: solid #CC0000 1px; color: black; padding: 5px; text-align: left; cursor: default; align: left; background-color: white; whitespace: preserve; overflow: auto">';
        $caller = self::getCaller ();
        list ($prefix, $file) = CopixFile::getCopixPathPrefix ($caller['file']);
        if ($prefix) {
            $file = $prefix . DIRECTORY_SEPARATOR . $file;
        }
        echo '<div style="width:100%; background-color:#CC0000; color: white; padding-top: 5px; padding-bottom: 5px; margin-bottom: 5px; font-weight: bold; text-align: center;">';
        printf ("From %s, line %d :", htmlentities ($file), $caller['line']);
        echo '</div>';
        echo '<pre>';
        echo $pContent;
        echo '</pre>';
        echo '</div>';
    }

    /**
     * Effectue un dump avec la bonne méthode
     *
     * @param mixed $pVar Variable
     * @return string
     */
    private static function _dump ($pVar)
    {
        self::$_dumpIndex++;
        if (self::$_dumpIndex > 0) {
            self::$_declarationSpaces += 4;
            self::$_sectionSpaces += 4;
        }
        self::$_dumpSpaces += 4;

        $isJustDeclaration = (self::$_dumpIndex) >= CopixConfig::instance ()->copixdebug_maxDumpLevels;
        if (is_object ($pVar)) {
            $toReturn = self::_object_dump ($pVar, $isJustDeclaration);
        } elseif (is_array ($pVar)) {
            $toReturn = self::_array_dump ($pVar, $isJustDeclaration);
        } else {
            $toReturn = self::_var_dump ($pVar, $isJustDeclaration);
        }

        self::$_dumpIndex--;
        self::$_declarationSpaces -= 4;
        self::$_sectionSpaces -= 4;
        self::$_dumpSpaces -= 4;

        return $toReturn;
    }

    /**
     * Affiche un var_dump
     *
     * @param mixed $pVar Variable
     * @param boolean $pReturn true : renvoie le résultat sous forme de chaine, false : affiche le résultat avec echo
     * @param boolean $pFormatReturn Formater le résultat retourné, ou retourner un texte brut
     * @return string
     */
    public static function var_dump ($pVar, $pReturn = false, $pFormatReturn = true)
    {
        self::$_return = $pReturn;
        self::$_formatReturn = $pFormatReturn;
        // on met -1 car self::_dump fera un +1, donc mettra l'index à 0
        self::$_dumpIndex = -1;

        $dump = self::_dump ($pVar);
        if ($pReturn) {
            return $dump;
        } else {
            self::_outputFormatted ($dump);
        }
    }

    /**
     * Affiche un debug_backtrace en formattant le retour
     *
     * @param int $pLevel Niveau d'appelant du contexte recherché, 1 = appelant direct
     * @param array $pIgnorePaths Fichiers et chemins du debug_backtrace à ignorer
     * @param boolean $pReturn true : renvoie le résultat sous forme de chaine, false : affiche le résultat avec echo
     * @param boolean $pFormatReturn Formater le résultat retourné, ou retourner un texte brut
     * @return string
     */
    public static function debug_backtrace ($pLevel = 0, $pIgnorePaths = null, $pReturn = false, $pFormatReturn = true)
    {
        $debug = self::_filtered_debug_backtrace ($pIgnorePaths, $pLevel);

        // si on veut retourner le contenu
        if ($pReturn) {
            return $debug;

        // si on veut afficher le contenu directement
        } else {

            // si on ne veut pas formater l'affichage
            if (!$pFormatReturn) {
                echo $debug;

            // si on veut formater l'affichage
            } else {
                echo '<table class="CopixTable">';
                echo '<tr>';
                //echo '<th>' . _i18n ('copix:copixdebug.th.class') . '</th>';
                echo '<th>' . _i18n ('copix:copixdebug.th.functions') . '</th>';
                echo '<th>' . _i18n ('copix:copixdebug.th.args') . '</th>';
                echo '</tr>';

                $alternate = '';
                foreach ($debug as $index => $infos) {
                    echo '<tr ' . $alternate . ' valign="top">';
                    echo '<td><span title="' . htmlspecialchars ($infos['file'] . ':' . $infos['line']) . '">';
                    if (isset ($infos['class'])) {
                        echo $infos['class'] . '::';
                    }
                    echo '<strong>' . $infos['function'] . ' ()</strong>';
                    echo '</span></td>';
                    echo '<td style="max-height: 200">';
                    self::var_dump ($infos['args']);
                    echo '</td>';
                    echo '</tr>';

                    $alternate = ($alternate == '') ? 'class="alternate"' : '';
                }
                echo '</table><br />';
            }
        }
    }

    /**
     * Retourne les informations de contexte de l'un des appelants
     *
     * @param int $pLevel Niveau d'appelant du contexte recherché, 1 = appelant direct
     * @param array $pIgnorePaths Fichiers et chemins du debug_backtrace à ignorer
     * @return array Niveau d'appelant voulu
     */
    public static function getCaller ($pLevel = 0, $pIgnorePaths = null)
    {
        $backtrace = self::debug_backtrace ($pLevel + 1, $pIgnorePaths, true, false);
        return reset ($backtrace);
    }

    /**
     * Retourne le debug_backtrace purgé des références à certains fichiers (et/ou chemins).
     *
     * @param array $pIgnorePaths Chemins à ignorer
     * @param integer $pLevel Nombre de niveau d'appels à ignorer.
     * @return array debug_backtrace
     */
    private static function _filtered_debug_backtrace ($pIgnorePaths = null, $pLevel = 0)
    {
        static $recurse = false;
        if ($recurse) return array ();
        $recurse = true;
        try {

            // Chemins à ignorer
            $ignorePaths = array (
                __FILE__,
                COPIX_CORE_PATH . 'shortcuts.lib.php',
                COPIX_TEMP_PATH
            );
            if (is_array ($pIgnorePaths)) {
                $ignorePaths = array_merge ($ignorePaths, $pIgnorePaths);
            }

            // Construit la regex pour vérifier les chemins
            $regex = array ();
            foreach ($ignorePaths as $path) {
                $regex[] = preg_quote (CopixConfig::getRealPath ($path), '/');
            }
            $pathRegex = '/^(' . implode ('|', $regex) . ')/i';

            // Filtre la pile d'appel
            $backtrace = array_slice (debug_backtrace (), $pLevel + 2);
            foreach ($backtrace as $k => $step) {
                if (isset ($step['file']) && preg_match ($pathRegex, $step['file'])) {
                    unset ($backtrace[$k]);
                }
            }

            $recurse = false;
            return array_values ($backtrace);

        } catch (Exception $e) {
            $recurse = false;
            throw $e;
        }
    }
}
