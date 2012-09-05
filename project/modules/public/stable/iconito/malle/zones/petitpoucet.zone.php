<?php

/**
 * Zone PetitPoucet, qui affiche le chemin depuis la racine jusqu'à un dossier
 *
 * @package Iconito
 * @subpackage	Malle
 */
class ZonePetitPoucet extends CopixZone
{
    /**
     * Affiche le chemin d'accès à un dossier, depuis la racine d'une malle
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @param integer $folder Id du dossier courant (0 si racine)
     * @param string $action Action (au sens Copix) des liens utilisés. Si vide=>"getMalle"
     * @param string $field (option) Nom du champ pour l'insertion d'un lien vers un doc, utilisé en mode Popup
     * @param string $format (option) Format de la zone de saisie (wiki, html, fckeditor...), utilisé en mode Popup
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();
        $daoFolders = _dao("malle|malle_folders");
        $res = array();
        $malle = ($this->getParam('malle')) ? $this->getParam('malle') : NULL;
        $folder = ($this->getParam('folder')) ? $this->getParam('folder') : NULL;
        $action = ($this->getParam('action')) ? $this->getParam('action') : 'getMalle';
        $field = ($this->getParam('field')) ? $this->getParam('field') : NULL;
        $format = ($this->getParam('format')) ? $this->getParam('format') : NULL;
        //Kernel::deb("format=$format");
        //print_r ("malle=$malle / folder=$folder");
        if ($folder) {
            $fusible 	= 99;
          $parent = -1;
            while ($fusible>0 && $parent!=0) {
                $rFolder = $daoFolders->get ($folder);
                if ($rFolder) {
                    //print_r($rFolder);
                    $linkParams = array("id"=>$malle, "folder"=>$rFolder->id);
                    if ($field)		$linkParams['field'] = $field;
                    if ($format)	$linkParams['format'] = $format;
                    $res[] = array("libelle"=>$rFolder->nom, "lien"=>CopixUrl::get ('malle||'.$action, $linkParams));
                    $parent = $rFolder->parent;
                    $folder = $parent;
                }
                $fusible--;
            }
        }
        $linkParams = array("id"=>$malle);
        if ($field)		$linkParams['field'] = $field;
        if ($format)	$linkParams['format'] = $format;
        $res[] = array("libelle"=>CopixI18N::get ('malle|malle.root'), "lien"=>CopixUrl::get ('malle||'.$action, $linkParams));
        $tpl->assign('petitpoucet', array_reverse($res));
    $toReturn = $tpl->fetch ('petitpoucet.tpl');
    return true;
    }

}
