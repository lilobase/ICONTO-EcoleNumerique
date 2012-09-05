<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class enicCss extends enicMod
{
    public function startExec()
    {
        return true;
    }

    public function addFile($iPath)
    {
        CopixHTMLHeader::addCSSLink (_resource($iPath));


    }

    public function file($iPath)
    {
        $this->addFile($iPath);
    }

}
