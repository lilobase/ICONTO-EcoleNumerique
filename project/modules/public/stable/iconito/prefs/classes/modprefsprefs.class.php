<?php
require_once (COPIX_UTILS_PATH.'CopixUtils.lib.php');

class ModPrefsPrefs
{
    public function getPrefs ( $data=null )
    {
        $toReturn = array();

        $toReturn['name'] = CopixI18N::get ('prefs.string.generalprefs');
        $toReturn['form'][] = array(
            'type'=>'titre',
            'text'=>CopixI18N::get ('prefs.config.passwd.title'),
            'expl'=>CopixI18N::get ('prefs.config.passwd.expl'),
        );
        $toReturn['form'][] = array(
            'code'=>'passwd1',
            'type'=>'password',
            'text'=>CopixI18N::get ('prefs.config.passwd1.input'),
            'value'=>''
        );
        $toReturn['form'][] = array(
            'code'=>'passwd2',
            'type'=>'password',
            'text'=>CopixI18N::get ('prefs.config.passwd2.input'),
            'value'=>''
        );

        if( CopixConfig::exists('|can_pref_assistance_animtice') && CopixConfig::get('|can_pref_assistance_animtice') ) {
        $toReturn['form'][] = array(
            'type'=>'titre',
            'text'=>CopixI18N::get ('prefs.config.assistance.title'),
            'expl'=>CopixI18N::get ('prefs.config.assistance.expl'),
        );
        $toReturn['form'][] = array(
            'code'=>'assistance',
            'type'=>'checkbox',
            'text'=>CopixI18N::get ('prefs.config.assistance'),
            'value'=>(isset($data['assistance'])&&$data['assistance'])?true:false );
        }

        if( CopixConfig::exists('|can_pref_assistance_ien') && CopixConfig::get('|can_pref_assistance_ien') ) {
        $user_infos = Kernel::getUserInfo();
        if($user_infos['type']=='USER_ENS') {
            $toReturn['form'][] = array(
                'type'=>'titre',
                'text'=>CopixI18N::get ('prefs.config.ien.title'),
                'expl'=>CopixI18N::get ('prefs.config.ien.expl'),
            );
            $toReturn['form'][] = array(
                'code'=>'assistance_ien',
                'type'=>'checkbox',
                'text'=>CopixI18N::get ('prefs.config.ien'),
                'value'=>(isset($data['assistance_ien'])&&$data['assistance_ien'])?true:false
            );
        }
        }

        $toReturn['form'][] = array(
            'type'=>'titre',
            'text'=>CopixI18N::get ('prefs.config.avatar.title'),
            'expl'=>CopixI18N::get ('prefs.config.avatar.expl'),
        );

        $avatar = Prefs::get('prefs', 'avatar');
        if( $avatar ) {
            $avatar_url = 'static/prefs/avatar/'.$avatar;

            $toReturn['form'][] = array(
                'type'=>'image',
                'text'=>CopixI18N::get ('prefs.config.avatar.image'),
                'value'=>$avatar_url,
            );
        }
        $toReturn['form'][] = array(
            'code'=>'avatar_upload',
            'type'=>'upload',
            'text'=>CopixI18N::get ('prefs.config.avatar.upload'),
            'value'=>(isset($data['nom'])?$data['nom']:_currentUser()->getExtra('nom'))
        );
        if( $avatar ) {
            $toReturn['form'][] = array(
                'code'=>'avatar_delete',
                'type'=>'checkbox',
                'text'=>CopixI18N::get ('prefs.config.avatar.delete'),
                'value'=>false,
            );
        }

        /////////////////////////// ALERTE MAIL ///////////////////////////
        $toReturn['form'][] = array(
            'type'=>'titre',
            'text'=>CopixI18N::get ('prefs.config.mail.title'),
            'expl'=>CopixI18N::get ('prefs.config.mail.expl'),
        );

        /*
        $toReturn['form'][] = array(
                'code'=>'alerte_mail_active',
                'type'=>'checkbox',
                'text'=>CopixI18N::get ('prefs.config.mail.alerte_mail_active'), // 'Souhaitez-vous être alerté par un email à chaque fois que vous recevez un minimail ?',
                'value'=>($data['alerte_mail_active']?true:false) );
        */
        $toReturn['form'][] = array(
                'code'=>'alerte_mail_email',
                'type'=>'string',
                'text'=>CopixI18N::get ('prefs.config.mail.alerte_mail_email'), // 'Si oui, saisissez votre email',
                'value'=>(isset($data['alerte_mail_email'])?$data['alerte_mail_email']:'') );

        return( $toReturn );
    }

    public function checkPrefs( $module, $data )
    {
        define('AVATAR_MAX_BYTES', 100000);
        define('AVATAR_MAX_PIXELS', 100);

        $error = array();

        if( ($data['passwd1']!='' || $data['passwd2']!='') ) {
            if( $data['passwd1']!=$data['passwd2'] ) {
                $error['passwd2'] = CopixI18N::get ('prefs.config.passwd.error.notsame');
                $error['passwd1'] = " ";
            }
            if( strlen($data['passwd1'])<5 )
                $error['passwd1'] = CopixI18N::get ('prefs.config.passwd.error.tooshort');
        }

        if( isset($_FILES) && $_FILES['prefs_avatar_upload']['name'] ) {
            if( !in_array($_FILES['prefs_avatar_upload']['type'], array('image/jpeg','image/gif','image/png')) ) {
                $error['avatar_upload'] = CopixI18N::get ('prefs.config.avatar.error_format');
            } elseif( $_FILES['prefs_avatar_upload']['size']>AVATAR_MAX_BYTES ) {
                $error['avatar_upload'] = CopixI18N::get ('prefs.config.avatar.error_bytes');
            } elseif( (list($width, $height, $type, $attr) = getimagesize($_FILES['prefs_avatar_upload']['tmp_name'])) && (max($width,$height)>AVATAR_MAX_PIXELS) ) {
                $error['avatar_upload'] = CopixI18N::get ('prefs.config.avatar.error_pixels');
            } elseif( $_FILES['prefs_avatar_upload']['error']!=0 ) {
                $error['avatar_upload'] = CopixI18N::get ('prefs.config.avatar.error_inconnue');
            }
        }

        $data['alerte_mail_email'] = trim($data['alerte_mail_email']);
        if( isset($data['alerte_mail_email']) && $data['alerte_mail_email']!='' ) {
            // if( !ereg( "@", $data['alerte_mail_email'] ) )
            if( !validateEMail($data['alerte_mail_email']) )
                $error['alerte_mail_email'] = CopixI18N::get ('prefs.config.mail.bad_email');
        }

        return( $error );
    }

    public function setPrefs( $module, $data )
    {
        // Traiter passwd1 et passwd2
        if( ($data['passwd1']==$data['passwd2']) && strlen($data['passwd1'])>0 ) {
            $bu = Kernel::getSessionBU();
            $dao = _dao('kernel|kernel_copixuser');
            $user = $dao->get( $bu['user_id'] );
            $user->password_dbuser = md5($data['passwd1']);
            $dao->update( $user );
        }
        unset( $data['passwd1'] );
        unset( $data['passwd2'] );


        // Traiter l'effacement d'un avatar
        if( isset($data['avatar_delete']) && $data['avatar_delete'] == '1' ) {
            $path2data  = realpath("static");
            $path2prefs = $path2data."/prefs";
            $path2avatars = $path2prefs."/avatar";

            if( $avatar_old = Prefs::get( 'prefs', 'avatar' ) ) {
                @unlink( $path2avatars.'/'.$avatar_old );
                Prefs::del( 'prefs', 'avatar' );
            }

        }



        // Traiter l'ajout d'un avatar
        if( ereg( "^image/(.+)$", $_FILES['prefs_avatar_upload']['type'], $regs ) ) {
            if( in_array($regs[1], array('jpeg','gif','png')) ) {

                $path2data  = realpath("static");
                $path2prefs = $path2data."/prefs";
                // if( !is_dir($path2prefs) ) mkdir($path2prefs);
                $path2avatars = $path2prefs."/avatar";
                // if( !is_dir($path2avatars) ) mkdir($path2avatars);

                if( $avatar_old = Prefs::get( 'prefs', 'avatar' ) ) {
                    @unlink( $path2avatars.'/'.$avatar_old );
                }

                $avatar_file  = $path2avatars."/"._currentUser()->getLogin().'.'.$regs[1];
                move_uploaded_file ( $_FILES['prefs_avatar_upload']['tmp_name'], $avatar_file );

                $data['avatar'] = _currentUser()->getLogin().'.'.$regs[1];
            }
        }

        if( !isset($data['assistance']) ) $data['assistance']=0;
        if( !isset($data['assistance_ien']) ) $data['assistance_ien']=0;

        /*
        if( !isset($data['alerte_mail_active']) ) $data['alerte_mail_active']=0;
        */

        // Enregistrement du reste dans la base principale
        $pref_service = & CopixClassesFactory::Create ('prefs|prefs');
        $pref_service->setPrefs( $module, $data );
    }

}

