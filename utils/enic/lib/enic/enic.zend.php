<?php



class enicZend
{
    /* Bind To Zend Feed */
    public function startExec()
    {
        set_include_path(ENIC_PATH.'lib');
        enic::zend_load('Feed/Reader');
    }

}