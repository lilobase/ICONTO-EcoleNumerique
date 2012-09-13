<?php
/*
    @file 		helper.php
    @desc		helping functions for main layout constructor
    @version 	1.0.0b
    @date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
    @author 	S.HOLTZ <sholtz@cap-tic.fr>

    Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<?php

function getTheme ($property, $mode = "STD")
{
    $jfile = file_get_contents(CopixUrl::getResourcePath ("theme.conf.json"));
    if (!$jfile) { 
        trigger_error('THEME : unable to find JSON configuration file', E_USER_ERROR);
        return false;
    }
    $jtheme = json_decode($jfile);
    if (!$jtheme) { 
        trigger_error('THEME : unable to parse JSON configuration file', E_USER_ERROR);
        return false;
    }

    switch ($property) {
        case 'dimensions':
            return $jtheme->dimensions->$mode;
            break;
        case 'zones':
            return $jtheme->layout->$mode;
            break;
        default:
            return $jtheme;
    }
}

function getZones ($position, $collapse = true, $dispmode = "STD")
{
    $module = CopixRequest::get('module');
    $user = _currentUser ();
    $userstatus = ($user->isConnected()) ? 'connected':'visitor';

    $layout = getTheme("zones", $dispmode);
    $jzones_default = (isset($layout->$userstatus->default->$position)) ? $layout->$userstatus->default->$position : array();
    $jzones_module = (isset($layout->$userstatus->$module->$position)) ? $layout->$userstatus->$module->$position : array();
    $jzones = array_merge((array)$jzones_default, (array)$jzones_module);

    if (empty($jzones)) {
        if ($collapse) {
            echo '<div class="collapse"></div>';
        } else {
            echo '<div class="filler"></div>';
        }
    } else {
        foreach ($jzones as $zone) {
            if ($zone->params != "") {
                $params = $zone->params;
                $params = unserialize($params);
                $zoneContent = CopixZone::process ($zone->supplier.'|'.$zone->zone, $params);
            } else {
                $zoneContent = CopixZone::process ($zone->supplier.'|'.$zone->zone);
            }
            if (!$zoneContent) {
                continue;
            }
            $id = ($position=='popup') ? 'id="'.$module.'-'.$zone->zone.'" ' : '';
            echo '<div '.$id.'class="'.$module.' '.$zone->zone.'">';
            echo $zoneContent;
            echo '</div>';
        }
    }
}


function inDashContext ()
{
    $module = CopixRequest::get ('module');
    $action = CopixRequest::get('action');
    $actiongroup = CopixRequest::get('group');
    $response = ($module != 'kernel' && $module != 'welcome')? true : false;
    $response = ($actiongroup == 'dashboard' && $action == 'modif')? true : $response;
    return $response;
}

function moduleContext($step='open', $title_page='', $titleContext='')
{
    $module = CopixRequest::get('module');
    $content = CopixZone::process ('kernel|moduleContext', array ('STEP'=>$step, 'MODULE'=>$module, 'TITLE_PAGE'=>$title_page, 'TITLE_CONTEXT'=>$titleContext));
    if (!$content) {
        trigger_error('Unable to process Module Context Frame', E_USER_WARNING);
    } else {
        echo $content;
    }
}

