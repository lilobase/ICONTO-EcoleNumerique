<?php $_db_profiles = array (
  'petiteenfance' => 
  array (
    'driver' => 'pdo_mysql',
    'connectionString' => 'dbname=petiteenfance',
    'user' => 'root',
    'password' => NULL,
    'extra' => 
    array (
    ),
    'default' => true,
    'available' => true,
    'errorNotAvailable' => '',
  ),
	'viescolaire' => 
  array (
    'driver' => 'pdo_mysql',
    'connectionString' => 'dbname=viescolaire',
    'user' => 'root',
    'password' => NULL,
    'extra' => 
    array (
    ),
    'default' => false,
    'available' => true,
    'errorNotAvailable' => '',
  ),
);
// S'il faut, ajouter la ligne suivante a la findes connectionString :
// ;unix_socket=/var/mysql/mysql.sock
$_db_default_profile = 'petiteenfance'; ?>