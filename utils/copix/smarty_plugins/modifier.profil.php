<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


// Renvoie le type de profil complet d'un utilisateur
// Par exemple pour un "USER_EXT" en paramètre, il retournera "Personne extérieure"

function smarty_modifier_profil ($string)
{
    return Kernel::Code2Name ($string);
}


