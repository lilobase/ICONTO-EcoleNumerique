<?php

/**
 * Formatte une date ou une heure selon strftime
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2012/02/22
 * @see http://php.net/manual/fr/function.strftime.php
 * @param string $string Date ou heure
 * @param string $format Format demandé
 * @return string
 */

function smarty_modifier_strftime($string, $format)
{

    if (preg_match('/^[0-9]{8}$/', $string)) { // Format YYYYMMDD
        $ts = CopixDateTime::yyyymmddToTimestamp($string);
    } else {
        throw new CopixException(sprintf('Date %s mal formatee', $string));
    }

    return strftime($format, $ts);


}



