<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class enicHtml extends enicMod
{
    public function  startExec()
    {
        parent::startExec();
    }

    public function addImg($iPath)
    {
        return CopixUrl::getResource('images/'.$iPath);
    }

}
