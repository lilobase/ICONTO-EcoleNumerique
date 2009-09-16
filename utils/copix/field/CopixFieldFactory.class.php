<?php

class CopixFieldFactory {
	
	static private $_arFields = array (
								);
	
	static public function get ($pType, $pParams, $pAssert = true) {
		if ($pType === null) {
			$pType = 'varchar';
		}
		if (isset (self::$_arFields[$pType])) {
			$class = self::$_arFields[$pType];
			return new $class ($pParams);
		} else {
				if (strpos ($pType, '|')) {
					return CopixClassesFactory::create ($pType, array ($pParams));
				} else {
					if (class_exists ('CopixField'.$pType)) {
						$class = 'CopixField'.$pType;
						return new $class ($pParams);
					} else {
						if ($pAssert) {
							throw new CopixException ('Ce type n\'existe pas ['.$pType.']');
						} else {
							return new CopixFieldVarchar ($pParams);
						}
					}
				}
				
		}
	}
	
}

?>