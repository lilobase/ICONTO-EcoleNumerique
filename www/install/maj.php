<?php

$dossierInstall=installDir();
$paramFile=$dossierInstall."/.majParam";
$svnCmd="svn"; // --config-dir /var/lib/wwwrun/.subversion

/**
 ** Authentification du user
 **/
$md5='';
if (empty($_POST['MD5']) && !empty($_POST['adminPw']))
    $md5 = md5($_POST['adminPw']);
elseif (isset($_POST['MD5']))
    $md5 = $_POST['MD5'];

if ($md5 != md5DB()) {
    echo "<form method='POST' action=''>
        <input type='hidden' name='MD5' value=''/>
        <input type='password' name='adminPw' value='Mot de passe admin'/>
        <input type='submit' value='V&eacute;rifier'/>
        </form>";
    die("Merci de vous authentifier !");
}

//echo "Vous êtes authentifié!<br/>";

/**
 ** Vérification des paramètres SVN
 **/
if (isset($_POST["param"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];
    file_put_contents($paramFile, "$login@$password");
    chmod($paramFile, 0600);
}
if (!file_exists($paramFile)) {
    echo "Pour faire fonctionner ce script de mise &agrave; jour, veuillez contr&ocirc;ler ces points :";
    echo "<ul>";
    echo "<li>L'utilisateur ex&eacute;cutant le serveur web doit avoir les droits d'&eacute;criture sur l'installation</li>";
    echo "<li>L'utilisateur ex&eacute;cutant le serveur web doit pouvoir &eacute;xecuter la commande <i>svn</i></li>";
    echo "<li>L'utilisateur ex&eacute;cutant le serveur web doit pouvoir &eacute;xecuter la commande <i>mysqldump</i></li>";
    echo "</ul>";
    echo "<form method='POST' action=''>
          <input type='hidden' value='1' name='param'/>
          <input type='hidden' value='$md5' name='MD5'/>
          <input type='text' value='Login SVN' name='login'/>
          <input type='text' value='Password SVN' name='password'/>
          <input type='submit' value='Enregistrer'/></form>";
    die();
} else {
    list($loginSVN,$passwordSVN) = preg_split("/@/", file_get_contents($paramFile));
}

/**
 ** On décide de faire la mise à jour
 **/
if (isset($_POST['do'])) {
    echo "Mise à jour en cours...<br/>";
    exec("$svnCmd --username=$loginSVN --password=$passwordSVN up $dossierInstall", $svnUp, $error);
    //preg_replace("/$dossierInstall/", "//", $svnUp);
    echo "<pre>";
    foreach ($svnUp as $line) {
        echo preg_replace("/".$dossierInstall."/", "//", $line)."\n";
    }
    echo "</pre>";
    echo "Mise à jour terminée !<br/>";
    echo "Vérification<br/>";
}

/**
 ** Vérifier s'il existe une mise à jour
 **/
exec("$svnCmd info $dossierInstall", $workingCopy, $error);
$urlKey = key(preg_grep("/^URL/", $workingCopy));
list(, $WC_URL) = preg_split('/: /',$workingCopy[$urlKey]);
$revKey = key(preg_grep("/^R.vision/", $workingCopy));
list(, $WC_REV) = preg_split('/: /',$workingCopy[$revKey]);
//$dateKey = key(preg_grep("/^Date/", $workingCopy));
//list(, $WC_DATE) = preg_split('/: /',$workingCopy[$dateKey]);
exec("$svnCmd --username=$loginSVN --password=$passwordSVN info $WC_URL", $REPO, $error);
list(, $REPO_REV) = preg_split('/: /',$REPO[4]);

if (!isset($REPO_REV))
    die ("ERREUR : Impossible de joindre le dépôt SVN");

if ($REPO_REV > $WC_REV) {
        echo "Une mise &agrave; jour est disponible (#$WC_REV -> #$REPO_REV)<br/>";
        exec("$svnCmd --username=$loginSVN --password=$passwordSVN status -uq $dossierInstall", $svnStatus, $error);
        array_pop($svnStatus);
        sort($svnStatus);
        $db=0;
        echo "Liste des fichiers concern&eacute;s par la mise &agrave; jour :<br/>";
        echo "<pre>";
        foreach ($svnStatus as $file) {
        //echo str_replace("$dossierInstall/","",$file)."\n";
        $line = str_replace("$dossierInstall/","",$file);
        preg_match('/(.{7})(.{7})(.{6})(.*)/', $line, $lineDetail);
        list (, $local, $remote, $rev, $file) = $lineDetail;
        echo "<form method='post' action='' ><input type='hidden' name='MD5' value='$md5'/>";
        echo "  ".preg_replace('/ +/', "  ", $local).preg_replace('/ +/', "  ", $remote).preg_replace('/ +/', "  ", $rev);
        echo "<input type='hidden' name='detailSVN' value='$file'/>$file&nbsp;<input type='submit' value='Voir'/></form>";
        }
        echo "</pre>";
        if (db_check($svnStatus))
                echo "La mise &agrave; jour n&eacute;cessite une mise &agrave; jour de la base de donn&eacute;e.<br/>";
        echo "<form method='POST' action=''>
        <input type='hidden' name='MD5' value='$md5'/>
        <input type='hidden' name='do'  value='1'/>
        <input type='submit' value='Effectuer la mise &agrave; jour'/>
        </form>";
} else {
        echo "Votre installation est &agrave; jour (REV #$WC_REV)";
}

/**
 ** Voir la diffrence
 **/
if (isset($_POST['detailSVN'])) {
    exec("$svnCmd --username=$loginSVN --password=$passwordSVN diff $dossierInstall/".$_POST['detailSVN'], $svnDiff, $error);
    echo "<div style='border: 1px solid gray;'>";
    echo "<pre>";
    foreach ($svnDiff as $diff) {
        echo "$diff\n";
    }
    echo "</pre>";
    echo "</div>";
}

/**
 ** Fonction qui retourne les élements de connexion à la base de données.
 **/
function accessDB()
{
    $installDir = installDir();

    $configDB=file($installDir."/var/config/db_profiles.conf.php");
    $csKey = key(preg_grep("/'connectionString'/", $configDB));
    $userKey = key(preg_grep("/'user'/", $configDB));
    $pwdKey = key(preg_grep("/'password'/", $configDB));

    preg_match("/ => '(.*);(.*)',/", $configDB[$csKey], $result);
if (count($result) > 0) {
    array_shift($result);
    $key = key(preg_grep('/host/', $result));
    preg_match("/host=(.*)/", $result[$key], $tmp);
    $host = $tmp[1];
    $key = key(preg_grep('/dbname/', $result));
    preg_match("/dbname=(.*)/", $result[$key], $tmp);
    $db = $tmp[1];
} else {
        preg_match("/'dbname=(.*)'/", $configDB[$csKey], $tmp);
        $host = "localhost";
        $db = $tmp[1];
}

    preg_match("/ => '(.*)',/", $configDB[$userKey], $result);
    $user = $result[1];

    preg_match("/ => '(.*)',/", $configDB[$pwdKey], $result);
    $passwd = $result[1];

    return array($host, $db, $user, $passwd);
}

/**
 ** Fonction qui effectue le backep
 **/
function backupDB($backupDir)
{
    $installDir = installDir();
    $backupFile = $db.'_'.date('Ymd-His').'.gz';
    $dump = "mysqldump --add-drop-table -h $host -u $user ";
    if (!empty($passwd))
        $dump .= "-p$passwd ";
    $dump .= "$db | gzip >$backupDir/$backupFile";

    exec($dump, $output, $error);

    return $backupFile;
}

function md5DB()
{
    list ($host, $db, $user, $passwd) = accessDB();
    $link = mysql_connect($host, $user, $passwd) or die("Pas de connexion à la base");
    mysql_select_db($db);
    $res = mysql_query("SELECT `password_dbuser` FROM `dbuser` WHERE `login_dbuser` = 'admin'");
    $md5 = mysql_fetch_row($res);
    mysql_close($link);
    return $md5[0];
}

function db_check($fileUpgrade)
{
    return false;
}

function installDir()
{
    return realpath(dirname(realpath($_SERVER['SCRIPT_FILENAME'])).'/../../');
}

function debug($var)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

