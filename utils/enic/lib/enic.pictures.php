<?php

class enicPictures extends enicMod{

    public function  __construct() {
        parent::__construct();
    }

    

}

$ImageNews = $_FILES['ImageNews']['name'];

$ExtensionPresumee = explode('.', $ImageNews);
$ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee) - 1]);
if ($ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg') {
    $ImageNews = getimagesize($_FILES['ImageNews']['tmp_name']);
    if ($ImageNews['mime'] == $ListeExtension[$ExtensionPresumee] || $ImageNews['mime'] == $ListeExtensionIE[$ExtensionPresumee]) {

        $ImageChoisie = imagecreatefromjpeg($_FILES['ImageNews']['tmp_name']);
        $TailleImageChoisie = getimagesize($_FILES['ImageNews']['tmp_name']);
        $NouvelleLargeur = 350; //Largeur choisie à 350px mais modifiable

        $Reduction = ( ($NouvelleLargeur * 100) / $TailleImageChoisie[0] );
        $NouvelleHauteur = ( ($TailleImageChoisie[1] * $Reduction) / 100 );

        $NouvelleImage = imagecreatetruecolor($NouvelleLargeur, $NouvelleHauteur) or die("Erreur");

        imagecopyresampled($NouvelleImage, $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0], $TailleImageChoisie[1]);
        imagedestroy($ImageChoisie);
        $NomImageChoisie = explode('.', $ImageNews);
        $NomImageExploitable = time();

        imagejpeg($NouvelleImage, 'imagesnews/' . $NomImageExploitable . '.' . $ExtensionPresumee, 100);
        $LienImageNews = 'imagesnews/' . $NomImageExploitable . '.' . $ExtensionPresumee;

        $sql = 'INSERT INTO votre_table VALUES ("", "' . $TitreNews . '", "' . $ContenuNews . '", "' . $LienImageNews . '", "' . time() . '")';
        $res = mysql_query($sql) or die(mysql_error());
        if ($res) {
            echo 'La news a bien été insérée';
        }
    } else {
        echo 'Le type MIME de l\'image n\'est pas bon';
    }
} else {
    echo 'L\'extension choisie pour l\'image est incorrecte';
}
