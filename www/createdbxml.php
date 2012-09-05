<?php


$conf_database = (isset($_GET['base']) && $_GET['base']) ? $_GET['base'] : 'BAC_EcoleNumerique2010';

$conf_table = isset($_GET['table']) ? $_GET['table'] : '';
$module = isset($_GET['module']) ? $_GET['module'] : '';

if (!$module)
  $module = 'kernel';

if( !$conf_table) {
    die( 'Utiliser le param&egrave;tre GET "table" pour sp&eacute;cifier le nom de la table');
}
if( !$module) {
    die( 'Utiliser le param&egrave;tre GET "module" pour sp&eacute;cifier le nom du module Copix');
}

$dbhost = "127.0.0.1";
$user = 'root';
$password = '';
$connexion = mysql_connect($dbhost,$user,$password);
$db = mysql_select_db ($conf_database, $connexion);


$sql = "SHOW COLUMNS FROM ".addslashes($conf_table);
$colonnes = mysql_query($sql, $connexion);

$table_name_abr = $conf_table;
if (substr($table_name_abr,0,7)=='module_')
    $table_name_abr = substr($table_name_abr,7);

$tab = '  ';

$res = '';
$dao = '';
$mcd = '';
$smarty = '';
$res .= xml_begin($conf_table, $table_name_abr, $tab);
while ($colonne = mysql_fetch_array($colonnes,MYSQL_ASSOC)) {
    //print_r( $colonne );
    $res .= xml_colonne( $colonne , $table_name_abr, $tab);
    $dao .= dao_fr_colonne ($module,$colonne,$table_name_abr);
    $mcd .= $colonne['Field']."\n";
    $smarty .= "{i18n key=".$module."|dao.".$table_name_abr.".fields.".$colonne['Field']." noEscape=1}\n";
}

$res .= xml_end($tab);

echo '<h3>'.$module.'/resources/'.$table_name_abr.'.dao.xml</h3>';
echo '<textarea style="width:99%;height:500px;font-size:0.9em;">'.$res.'</textarea>';

echo '<div style="float:left;width:80%;margin-right:1%;">';
echo '<h3>'.$module.'/resources/dao_fr.properties</h3>';
echo '<textarea style="width:100%;height:200px;">'.$dao.'</textarea>';

echo '<h3>Utilisation des i18n en Smarty</h3>';
echo '<textarea style="width:100%;height:200px;">'.$smarty.'</textarea>';

echo '</div>';

echo '<div style="float:left;width:19%;">';
echo '<h3>Champs pour MCD</h3>';
echo '<textarea style="width:100%;height:300px;">'.$mcd.'</textarea>';
echo '</div>';




mysql_close($connexion);


function xml_begin( $table_name, $table_name_abr, $tab )
{
    $name = $table_name_abr;

    $res = '';
    $res .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
    $res .= "<daodefinition>\n";
    $res .= $tab."<datasource>\n";
    $res .= $tab.$tab."<tables>\n";
    $res .= $tab.$tab.$tab."<table name=\"".$name."\" tablename=\"".$table_name."\" primary=\"yes\" />\n";
    $res .= $tab.$tab."</tables>\n";
    $res .= $tab."</datasource>\n";
    $res .= $tab."<properties>\n";
    return $res;
}

function xml_colonne( $col_infos, $table_name_abr, $tab)
{
    $res = '';
    // type="autoincrement" Extra=auto_increment
    $type = "!!!! ".strtok( $col_infos['Type'], "(" )." !!!!";

    //var_dump($col_infos);
    if( $col_infos['Extra'] == 'auto_increment' ) {
        $type = 'autoincrement';
    } else {
        switch( strtok( $col_infos['Type'], "(" ) ) {
            case 'int':
            case 'tinyint':
            case 'mediumint':
            case 'smallint':
                $type = "integer";
                break;
            case 'varchar':
            case 'text':
                $type = "string";
                break;
            case 'decimal':
            case 'float':
                $type = "float";
                break;
            case 'date':
                $type = "date";
                break;
            case 'datetime':
            case 'timestamp':
                $type = "datetime";
                break;
        }
    }

    $required = ($col_infos["Null"]=="NO") ? ' required="yes"' : ' required="no"';
    $captioni18n = ' captioni18n="dao.'.$table_name_abr.'.fields.'.$col_infos['Field'].'"';

    $res .= $tab.$tab."<property name=\"".$col_infos['Field']."\" fieldname=\"".$col_infos['Field']."\" pk=\"".($col_infos['Key']=="PRI"?"yes":"no")."\" type=\"".$type."\"".$required.$captioni18n." />\n";
    return $res;
}

function xml_end($tab)
{
    $res = '';
  $res .= $tab."</properties>\n";
    $res .= "\n";
    $res .= $tab."<methods>\n";
    $res .= $tab."</methods>\n";
    $res .= "\n";
  $res .= "</daodefinition>\n";
    return $res;
}


function dao_fr_colonne( $module, $col_infos, $table_name_abr)
{
    $res = '';
    $res .= "dao.".$table_name_abr.".fields.".$col_infos['Field']." = ".$col_infos['Field']."\n";
    return $res;
}
