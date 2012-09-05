<?php

$go             = new CopixAction ('Concerto', 'go');       // Affiche le formulaire de connexion et autosumbit.
$logout         = new CopixAction ('Concerto', 'logout');   // Retour à Iconito après déconnexion de l'Espace Famille
$return         = new CopixAction ('Concerto', 'logout');   // Retour à Iconito après déconnexion de l'Espace Famille
$init           = new CopixAction ('Concerto', 'init');     // Création des comptes parents

$default        = & $go;

