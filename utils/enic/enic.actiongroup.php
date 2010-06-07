<?php

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */

class enicActionGroup extends CopixActionGroup {

    protected $user;
    protected $matrix;
    protected $menu;

    public function __construct(){
        //test the user connexion
	_currentUser()->assertCredential ('group:[current_user]');

        //load enic classes
        $this->user     =& enic::get('user');

enic::to_load('cache');
enic::to_load('matrix');

        $this->matrix   =& enic::get('matrixCache');

        $this->menu     =& enic::get('menu');
        $this->model    =& enic::get('model');

    }

}

