<?php
/**
 * Magicmail - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Magicmail
 * @version     $Id: magicmail.actiongroup.php,v 1.10 2006-11-14 00:02:19 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupMagicmail extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }

   /**
   * Configuration de MagicMail
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/08/09
   */
    public function getMagicMail ()
    {
        $id = _request("id");

        if( Kernel::getLevel( "MOD_MAGICMAIL", $id ) < PROFILE_CCV_ADMIN ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('magicmail.message.title'));

        $dao = CopixDAOFactory::create("magicmail|magicmail");
        $magic_result = $dao->get($id);

        $tplForm = new CopixTpl ();
        $tplForm->assign ('id', $id);
        $tplForm->assign ('infos', $magic_result);
        // $tplForm->assign ('magicmail_mail', CopixConfig::get ('magicmail|magicmail_mail'));

        if( (_request("return") ) )
            $tplForm->assign ('return', _request("return"));

        CopixHTMLHeader::addCSSLink (_resource("styles/module_prefs.css"));

        $tplForm->assign ('msg', array(
            'type'  => 'ok',
            'image_url' => _resource('img/prefs/smiley_black.png'),
            'image_alt' => 'Ok !',
            'value' => CopixI18N::get ('prefs|prefs.msg.prefsrecorded')
        ) );


        $result = $tplForm->fetch("login_form.tpl");
        $tpl->assign ("MAIN", $result);

        $menu = array();
        $tpl->assign ('MENU', $menu );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


   /**
   * Publication automatique par mail
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/07/28
   */
    public function doMailPublish ()
    {
                if( _request("key") ) {
                    if( ereg( "^([0-9]{8}-[0-9]{6})-([a-zA-Z0-9]+)-([a-fA-F0-9]{32})$", _request("key"), $regs ) ) {

                        $key   = _request("key");
                        $login = $regs[2];

                        $dao = CopixDAOFactory::create("magicmail|magicmail");
                        $mymagicmail = $dao->getByLogin($login);

                        $error = false;
                        if( sizeof($mymagicmail) ) {
                            $nodes = Kernel::getModParent( "MOD_MAGICMAIL", $mymagicmail[0]->magicmail_id );
                            if( sizeof($nodes) ) {
                             $node_type = $nodes[0]->node_type;
                             $node_id   = $nodes[0]->node_id;
                            } else $error = true;
                        } else $error = true;

                        if( !$error ) {
                            $file = @fopen( CopixConfig::get ('magicmail|magicmail_url').'data/'.$key.'/'.'info.txt', 'r' );
                            if ($file) {
                                $reply = '';
                                while (!feof($file)) {
                                    $reply.= fgets($file, 4096);
                                } // while (!feof($file))
                                fclose($file);

                                // <-- DEBUT TRAITEMENT -->

                                //////////////////////////

                                $blog_service = & CopixClassesFactory::Create ('blog|kernelblog');
                                $album_classes = & CopixClassesFactory::Create ('album|album');
                                $album_service = & CopixClassesFactory::Create ('album|kernelalbum');
                                $classeur_service = & CopixClassesFactory::Create ('classeur|kernelclasseur');

                                /*
                                $groupe_service = & CopixClassesFactory::Create ('groupe|groupeservice');
                                $blog = $groupe_service->getGroupeBlog( $grp );
                                $album = $groupe_service->getGroupeAlbum( $grp );
                                */

                                $blog  = null;
                                $album = null;

                                $mods = Kernel::getModEnabled( $node_type, $node_id );
                                foreach( $mods AS $mod ) {
                                    switch( $mod->module_type ) {
                                        case 'MOD_CLASSEUR':
                                            $classeur = $mod->module_id;
                                            break;
                                        case 'MOD_ALBUM':
                                            $album = $mod->module_id;
                                            break;
                                        case 'MOD_BLOG':
                                            $blog = $mod->module_id;
                                            break;
                                    }
                                }

                                /*
                                if( $blog == null ) {
                                    echo "-ERR No Blog\n";
                                    return new CopixActionReturn (COPIX_AR_NONE, 0);
                                }
                                if( $album == null ) {
                                    echo "-ERR No Album\n";
                                    return new CopixActionReturn (COPIX_AR_NONE, 0);
                                }
                                */

                                // echo "<br \>Blog: ".$blog." - Album: ".$album."<br \>\n";

                            //////////////////////////

                                    $blog_article = array();
                                    $album_photos  = array();
                                    $audio_files  = array();

                                    while (ereg ("^(SUBJECT|IMAGE|AUDIO|VIDEO-FLV|BODY) ?([^\n]+)\n(.*)$", $reply, $regs)) {
                                        // echo "<li>".$regs[0]."<br/>\n<li>".$regs[1]."<br/>\n<li>".$regs[2]."<br/>\n<li>".$regs[3];
                                        switch ($regs[1]) {
                                            case 'SUBJECT':
                                                $blog_article['title'] = $regs[2];
                                                break;
                                            case 'IMAGE':
                                                if( ereg ("^([0-9a-fA-F]+) ([0-9]+) (.*)$", $regs[2], $image_data) ) {

                                                    $photo_file = fopen( CopixConfig::get ('magicmail|magicmail_url').'data/'.$key.'/'.$image_data[1], 'r' );
                                                    $photo_data = '';
                                                    if ($file) {
                                                        while (!feof($photo_file)) {
                                                            $photo_data.= fgets($photo_file, 4096);
                                                        } // while (!feof($file))
                                                        fclose($photo_file);
                                                    }

                                                    $album_photos[] = array(
                                                        'file' => $image_data[3],
                                                        'title' => $image_data[3],
                                                        'data' => $photo_data
                                                    );
                                                }
                                                break;
                                            case 'AUDIO':
                                                if( ereg ("^([0-9a-fA-F]+) ([0-9]+) (.*)$", $regs[2], $audio_data) ) {

                                                    $audio_files[] = array(
                                                        'file' => CopixConfig::get ('magicmail|magicmail_url').'data/'.$key.'/'.$audio_data[1],
                                                        'title' => $audio_data[3]
                                                    );
                                                }
                                                break;
                                            case 'VIDEO-FLV':
                                                if( ereg ("^([0-9a-fA-F]+) ([0-9]+) (.*)$", $regs[2], $video_flv_data) ) {

                                                    $video_flv_files[] = array(
                                                        'file' => CopixConfig::get ('magicmail|magicmail_url').'data/'.$key.'/'.$video_flv_data[1],
                                                        'title' => $video_flv_data[3]
                                                    );
                                                }
                                                break;
                                            case 'BODY':
                                                $blog_article['body'] = $regs[3];
                                                break;
                                        }
                                        $reply = $regs[3];
                                    }

                                    //////////////////////////
                                    $images = array();

                                    if( $classeur != null ) {
                                        if($album_photos) foreach( $album_photos AS $album_photo ) {
                                            $album_photo_retour = $classeur_service->publish( $classeur, $album_photo );
                                            $images[] = $album_photo_retour;
                                        }
                                    } else
                                    if( $album != null ) {

                                        if($album_photos) foreach( $album_photos AS $album_photo ) {
                                            $album_photo_retour = $album_service->publish( $album, $album_photo );
                                            $images[] = $album_photo_retour;
                                        }
                                    }

                                    if( $blog != null ) {
                                        if( ! $blog_article['title'] || trim($blog_article['title'])=='' ) {
                                            $date = date('d/m/Y \� H\hi');
                                            $blog_article['title'] = 'Article du '.$date;
                                        }

                                        if(isset($audio_files)) foreach( $audio_files AS $audio_file ) {
                                            $blog_article['body'] .= "\n".'[['.$audio_file['file']."|mp3]]\n";
                                        }

                                        if(isset($video_flv_files)) foreach( $video_flv_files AS $video_flv_file ) {
                                            $blog_article['body'] .= "\n".'[['.$video_flv_file['file'].".flv|flv]]\n";
                                        }

                                        if(isset($images)) foreach( $images AS $image ) {
// TODO
                                            if( $classeur != null ) {
                                            $blog_article['body'] .= "\n".'[(('.CopixUrl::get().'static/classeur/'.$image['album_id'].'-'.$image['album_key'].'/'.
                                            $image['photo_id'].'-'.$image['photo_key'].'_240.'.$image['photo_ext'].'|'.
                                            $image['title'].'|))|'.CopixUrl::get().'static/classeur/'.$image['album_id'].'-'.$image['album_key'].'/'.
                                            $image['photo_id'].'-'.$image['photo_key'].'.'.$image['photo_ext'].']'."\n";
                                            } else {
                                            $blog_article['body'] .= "\n".'[(('.CopixUrl::get().'static/album/'.$image['album_id'].'_'.$image['album_key'].'/'.
                                            $image['photo_id'].'_'.$image['photo_key'].'_240.'.$image['photo_ext'].'|'.
                                            $image['title'].'|))|'.CopixUrl::get().'static/album/'.$image['album_id'].'_'.$image['album_key'].'/'.
                                            $image['photo_id'].'_'.$image['photo_key'].'.'.$image['photo_ext'].']'."\n";
                                            }
                                        }

                                        $blog_retour = $blog_service->publish( $blog, $blog_article );
                                        print_r( $blog_retour );
                                    }
                                    // <-- FIN TRAITEMENT -->

                                echo "+OK\n";
                                // print_r( $images );

                                // echo htmlentities($reply);
                            } // if ($file)
                            else echo "-ERR No info (file not found)\n";
                        } // if( $key==$source['key'] )
                        else echo "-ERR Bad key\n";
                    } // if( ereg( "^([0-9]{8}-[0-9]{4})-([a-zA-Z0-9]+)-([a-fA-F0-9]{32})$") )
                    else echo "-ERR Bad key (ereg not match)\n";
                } // if( _request("key") )
                else echo "-ERR Bad key (not set)\n";

        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

    public function doCreateMail()
    {
        $id = _request("id");

        $dao = CopixDAOFactory::create("magicmail|magicmail");
        $mymagicmail = $dao->get($id);

        // $newlogin = substr( md5(microtime()), 0, 10 );

        $url = CopixConfig::get ('magicmail|magicmail_url');
        $url.= 'register.php?action=add&url='.urlencode(CopixUrl::get()).'&id='.urlencode($id);
        if( $mymagicmail && trim($mymagicmail->magicmail_login.'@'.$mymagicmail->magicmail_domain)!='' )
            $url.= '&login='.urlencode(trim($mymagicmail->magicmail_login));

        $file = @fopen( $url, 'r' );
        if ($file) {
            $reply = '';
            while (!feof($file)) {
                $reply.= fgets($file, 4096);
            } // while (!feof($file))
            fclose($file);

            $return = '';
            if( ereg ("^\+OK (.+)@(.+)$", $reply, $data) ) {

                if( ! $mymagicmail ) {
                    $mymagicmail = CopixDAOFactory::createRecord("magicmail|magicmail");
                    $mymagicmail->magicmail_id    = $id;
                    $mymagicmail->magicmail_login = $data[1];
                    $mymagicmail->magicmail_domain = $data[2];
                    $dao->insert( $mymagicmail );
            } else {
//					echo( '<pre>'.print_r($mymagicmail,true).'</pre>');
                    $mymagicmail->magicmail_login = $data[1];
                    $mymagicmail->magicmail_domain = $data[2];
//					echo( '<pre>'.print_r($mymagicmail,true).'</pre>');
                    $dao->update( $mymagicmail );
//					echo( '<pre>'.print_r($mymagicmail,true).'</pre>');
//					die('Gloups!');
                }

                $return = 'ok';
            } else {
                $return = 'error';
            }

        }

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('magicmail|default|go', array('id'=>_request("id"),'return'=>$return) ) );
    }

    public function doDeleteMail()
    {
        $id = _request("id");

        $dao = CopixDAOFactory::create("magicmail|magicmail");
        $mymagicmail = $dao->get($id);

        $url = CopixConfig::get ('magicmail|magicmail_url');
        $url.= 'register.php?action=del&url='.urlencode(CopixUrl::get()).'&id='.urlencode($id);
        if( $mymagicmail && trim($mymagicmail->magicmail_login)!='' )
            $url.= '&login='.urlencode(trim($mymagicmail->magicmail_login));

        $file = @fopen( $url, 'r' );
        if ($file) {
            $reply = '';
            while (!feof($file)) {
                $reply.= fgets($file, 4096);
            } // while (!feof($file))
            fclose($file);
        }

        $mymagicmail->magicmail_login = '';
        $mymagicmail->magicmail_domain = '';
        $dao->update( $mymagicmail );

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('magicmail|default|go', array('id'=>$id) ) );
    }

}
