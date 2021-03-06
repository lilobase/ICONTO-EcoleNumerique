<?php

class ZoneInstallModule extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $arModuleToInstall = CopixSession::get('arModuleToInstall','copix');
        $arInstalledModule = CopixSession::get('arInstalledModule','copix');
        $moduleName = array_pop($arModuleToInstall);
        $url = $this->getParam('url');
        if (($message = CopixModule::installModule($moduleName))===true) {
            $toReturn = _i18n('install.module.install').' '.$moduleName.' <img src="'._resource('img/tools/valid.png').'" />';
            if (count($arModuleToInstall)>0) {
                $toReturn .= _tag('copixzone',array ('id'=>uniqid(),'process'=>'admin|installmodule','url'=>$url,'auto'=>true,'ajax'=>true));
            } elseif($url) {
                $toReturn .= sprintf(
                    '<form action="%s" method="post"><input type="submit" value="%s"/></form>',
                    htmlspecialchars($url),
                    _i18n('copix:common.buttons.next')
                );
            } else {
                $toReturn .= "<script>$('back').setStyle('display','');</script>";
            }
            array_push($arInstalledModule,$moduleName);
        } else {
            array_push($arInstalledModule,$moduleName);
            $toReturn = _i18n('install.module.install').' '.$moduleName.' '._tag('popupinformation',array('img'=>_resource('img/tools/delete.png')),$message);
            $toReturn .= '<div class="errorMessage">'.$message.'</div>';
            if (count($arInstalledModule)>0) {
                CopixSession::set('arModuleToDelete',$arInstalledModule,'copix');
                CopixSession::set('arInstalledModule',null,'copix');
                CopixSession::set('arModuleToInstall',null,'copix');
                $toReturn .= _tag('copixzone',array ('id'=>uniqid(),'process'=>'admin|deletemodule','auto'=>true,'ajax'=>true));
            }
        }
        CopixSession::set('arModuleToInstall',$arModuleToInstall,'copix');
        CopixSession::set('arInstalledModule',$arInstalledModule,'copix');
        return true;
    }
}
