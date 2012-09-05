<?php


function iconito_multimedia ($contents, $attr)
{
    $mode='auto';
    $cnt=count($contents);
    if($cnt > 2) $cnt=2;
    //print_r($contents);
    switch($cnt){
        case 2:
            $mode=$contents[1];
        case 1:
        default:
            $file = rawurldecode($contents[0]);
            if ($mode == 'download') {
              $path = COPIX_WWW_PATH.substr($file, strpos($file, 'static'));
                if (file_exists ($path) || fopen($path,'r')) {
                    $size = @filesize($path);
                    $size = ($size) ? ' - '.KernelMalle::human_file_size($size) : '';
                    $point = strrpos ($file, ".");
                    if ($point !== false)
                        $ext = substr($file,$point+1);
                    $ext = MalleService::getTypeInfos ($ext);
                    $pos = strrpos($file, '/');
                    if ($pos === false)	{ $name = $file; $href=$name; } else								{ $name = substr($file,$pos+1); $href=substr($file,0,$pos+1).rawurlencode($name); }
                    if (strlen($name)>35) $name=substr($name,0,35).'...';
//        print_r();
                    $ret = '<div class="file_dl"><a href="'.($href).'" title="'.htmlentities($file).'"><img src="'._resource ('img/malle/'.$ext['type_icon32']).'" width="32" height="32" border="0" title="'.htmlentities($ext['type_text']).'" alt="'.htmlentities($ext['type_text']).'" /><div class="name">'.$name.'</div></a><div class="desc">'.$ext['type_text'].''.$size.'</div></div>';
                } else {
                    $ret = '<div>Fichier '.$file.' introuvable</div>';
                }
            } elseif ($mode == 'view') {
                $point = strrpos ($file, ".");
                 if ($point !== false)
                    $ext = substr($file,$point+1);
                //print_r("ext=$ext");
                switch (strtolower($ext)) {
                    case "jpg" :
                    case "jpeg" :
                    case "gif" :
                    case "png" :
                    case "bmp" :
                        $link = array($file,'image');
                        break;
                    case "mp3" :
                        $link = array($file,'mp3');
                        break;
                    case "wmv" :
                    case "mpg" :
                    case "mpeg" :
                    case "avi" :
                        $link = array($file,'wmv');
                        break;
                    case "mov" :
                    case "mp4" :
                    case "m4a" :
                        $link = array($file,'mov');
                        break;
                    case "amr" :
                        $link = array($file,'amr');
                        break;
                    case "flv" :
                        $link = array($file,'flv');
                        break;
                    default :
                        $path = $_SERVER['PHP_SELF'];
                        $pos = strrpos($path, "/");
                        if ($pos !== false) {
                            $abspath = substr($path,0,$pos+1);
                        }
                        if (substr($file,0,strlen($abspath))==$abspath) $file = substr($file,strlen($abspath));
                        $link = array($file,'download');
                }
                $ret = iconito_multimedia ($link,NULL);

            } elseif ($mode == 'mp3') {
                $width = 200;
                $height = 20;
                $ret = '<div><object type="application/x-shockwave-flash" data="'.CopixUrl::getRequestedScriptPath().'dewplayer/dewplayer.swf?son='.$file.'" width="'.$width.'" height="'.$height.'"> <param name="movie" value="'.CopixUrl::getRequestedScriptPath().'dewplayer/dewplayer.swf?son='.$file.'" /><img src="'._resource ('images/music.png').'" width="16" height="16" border="0" title="MP3" alt="MP3" /></object></div>';
            } elseif ($mode == 'wmv') {
                $id = "media-Player".md5(mt_rand());
                $width = 480;
                $height = 385;
                $ret = '<div><object id="'.$id.'" width="'.$width.'" height="'.$height.'"
      classid="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
      codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
      standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
      <param name="fileName" value="'.$file.'">
      <param name="animationatStart" value="true">
      <param name="transparentatStart" value="true">
      <param name="autoStart" value="false">
      <param name="showControls" value="true">
      <param name="loop" value="false">
      <embed type="application/x-mplayer2"
        pluginspage="http://microsoft.com/windows/mediaplayer/fr/download/"
        id="'.$id.'" name="'.$id.'" displaysize="4" autosize="-1"
        bgcolor="darkblue" showcontrols="true" showtracker="-1"
        showdisplay="0" showstatusbar="-1" videoborder3d="-1" width="'.$width.'" height="'.$height.'"
        src="'.$file.'" autostart="0" designtimesp="5311" loop="false">
      </embed>
      <img src="'._resource ('images/film.png').'" width="16" height="16" border="0" title="vidéo" alt="vidéo" />
      </object></div>';
            } elseif ($mode == 'mov' || $mode == 'amr' || $mode == 'mp4' || $mode == 'm4a') {
                $width = 480;
                $height = ($mode == 'amr') ? 20 : 385;
        //$id = "media-Player".md5(mt_rand());
                $ret = '<div><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'.$width.'"
        height="'.$height.'" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
        <param name="src" value="'.$file.'">
        <param name="autoplay" value="false">
        <param name="controller" value="true">
        <param name="loop" value="false">
        <embed src="'.$file.'" width="'.$width.'" height="'.$height.'" autoplay="false"
        controller="true" loop="false" pluginspage="http://www.apple.com/quicktime/download/">
        </embed>
        <img src="'._resource ('images/film.png').'" width="16" height="16" border="0" title="vidéo" alt="vidéo" />
        </object></div>';
            } elseif ($mode == 'image') {
                $ret = '<div><img src="'.$file.'" alt="'.htmlentities($file).'" title="" /></div>';
            } elseif ($mode == 'flv') {
                $rand = md5(mt_rand());
                $width = 480;
                $height = 385;
                $ret = '
<p id="player-'.$rand.'"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</p>
<script type="text/javascript">
        var FU = { movie:"'.CopixUrl::getRequestedScriptPath().'flvplayer/flvplayer.swf",width:"'.$width.'",height:"'.$height.'",majorversion:"7",build:"0",bgcolor:"#FFFFFF",
                                flashvars:"file='.(!ereg("^https?://",$file)?CopixUrl::get():"").$file.'&showdigits=true&autostart=false" };
        UFO.create(FU, "player-'.$rand.'");
</script>';
            } elseif ($mode == 'youtube') {
                if (ereg("^([a-zA-Z0-9_-]+)$", $file))
                    $id = $file;
                elseif (ereg("v=([a-zA-Z0-9_-]+)", $file, $regs))
                    $id = $regs[1];
                if ($id)
                    $ret = '<div><object width="425" height="350" type="application/x-shockwave-flash" data="http://www.youtube.com/v/'.$id.'"><param name="movie" value="http://www.youtube.com/v/'.$id.'" /><param name="wmode" value="transparent" /><img src="'._resource ('images/film.png').'" width="16" height="16" border="0" title="vidéo" alt="vidéo" /></object></div>';
                else
                    $ret = '<div>Problème de paramètre</div>';
            } elseif ($mode == 'googlevideo') {
                if (ereg("^([0-9-]+)$", $file))
                    $id = $contents[0];
                elseif (ereg("docid=([0-9-]+)", $file, $regs))
                    $id = $regs[1];
                if ($id)
                    $ret = '<div><object width="400" height="326" type="application/x-shockwave-flash" data="http://video.google.com/googleplayer.swf?docId='.$id.'"><param name="movie" value="http://video.google.com/googleplayer.swf?docId='.$id.'" /><param name="allowScriptAccess" value="sameDomain" /><param name="quality" value="best" /><param name="scale" value="noScale" /><param name="wmode" value="transparent" /><param name="salign" value="TL" /><param name="FlashVars" value="playerMode=embedded" /><img src="'._resource ('images/film.png').'" width="16" height="16" border="0" title="vidéo" alt="vidéo" /></object></div>';
                else
                    $ret = '<div>Problème de paramètre</div>';
            } else {
                //$ret = '<div>Mode indéfini</div>';
              $ret = iconito_multimedia (array($file,'download'),NULL);
            }
    }
    return $ret;
}


