<?php
/**
 * Actiongroup du module Visio
 *
 * @package	Iconito
 * @subpackage	Visio
 */

class ActionGroupDefault extends enicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        CopixHTMLHeader::addCSSLink (_resource("styles/module_visio.css"));
    }


    public function processDefault ()
    {
        $ppo = new CopixPPO();
        $ppo->visio = new CopixPPO();
        $ppo->error = "";

        $user_from = Kernel::getUserInfo("ME", 0);
        $ppo->login_to = $this->request('login', 'str');
        $ppo->login_from = $user_from['login'];

        $ppo->visio->red5 = (CopixConfig::exists ('default|conf_ModVisio_url')) ? CopixConfig::get ('default|conf_ModVisio_url') : '';

        $ppo->visio->secondsToWait = (CopixConfig::exists ('visio|conf_secondsToWait')) ? CopixConfig::get ('visio|conf_secondsToWait') : 30;
        $ppo->visio->secondsToRetry = (CopixConfig::exists ('visio|conf_secondsToRetry')) ? CopixConfig::get ('visio|conf_secondsToRetry') : 60;
        $ppo->visio->textColor = (CopixConfig::exists ('visio|conf_textColor')) ? CopixConfig::get ('visio|conf_textColor') : "#FF0000";
        $ppo->visio->textOverColor = (CopixConfig::exists ('visio|conf_textOverColor')) ? CopixConfig::get ('visio|conf_textOverColor') : "#00FF00";
        $ppo->visio->infoTextColor = (CopixConfig::exists ('visio|conf_infoTextColor')) ? CopixConfig::get ('visio|conf_infoTextColor') : "#0000FF";

        $ppo->visio->bandwidth = (CopixConfig::exists ('visio|conf_bandwidth')) ? CopixConfig::get ('visio|conf_bandwidth') : 0;
        $ppo->visio->videoQuality = (CopixConfig::exists ('visio|conf_videoQuality')) ? CopixConfig::get ('visio|conf_videoQuality') : 95;
        $ppo->visio->motionLevel = (CopixConfig::exists ('visio|conf_motionLevel')) ? CopixConfig::get ('visio|conf_motionLevel') : 60;
        $ppo->visio->motionTimeout = (CopixConfig::exists ('visio|conf_motionTimeout')) ? CopixConfig::get ('visio|conf_motionTimeout') : 1500;
        $ppo->visio->keyFrameInterval = (CopixConfig::exists ('visio|conf_keyFrameInterval')) ? CopixConfig::get ('visio|conf_keyFrameInterval') : 15;
        $ppo->visio->useEchoSuppression = (CopixConfig::exists ('visio|conf_useEchoSuppression')) ? CopixConfig::get ('visio|conf_useEchoSuppression') : 'on';
        $ppo->visio->bufferTime = (CopixConfig::exists ('visio|conf_bufferTime')) ? CopixConfig::get ('visio|conf_bufferTime') : 0;

        $ppo->visio->useSpeex = (CopixConfig::exists ('visio|conf_useSpeex')) ? CopixConfig::get ('visio|conf_useSpeex') : 'off';
        $ppo->visio->microSilenceLevel = (CopixConfig::exists ('visio|conf_microSilenceLevel')) ? CopixConfig::get ('visio|conf_microSilenceLevel') : 20;
        $ppo->visio->microSilenceTimeout = (CopixConfig::exists ('visio|conf_microSilenceTimeout')) ? CopixConfig::get ('visio|conf_microSilenceTimeout') : -1;
        $ppo->visio->microSetLoopBack = (CopixConfig::exists ('visio|conf_microSetLoopBack')) ? CopixConfig::get ('visio|conf_microSetLoopBack') : 'false';
        $ppo->visio->microGain = (CopixConfig::exists ('visio|conf_microGain')) ? CopixConfig::get ('visio|conf_microGain') : 50;
        $ppo->visio->speexEncodeQuality = (CopixConfig::exists ('visio|conf_speexEncodeQuality')) ? CopixConfig::get ('visio|conf_speexEncodeQuality') : 6;

        if($ppo->login_to) {
            $user_to = Kernel::getUserInfo("LOGIN", $ppo->login_to);
            if( $user_to ) {
                return _arPPO($ppo, 'visio.tpl');
            } else {
                $ppo->error = "Login inconnu";
            }
        }
        return _arPPO($ppo, 'default.tpl');
    }

}



