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

        //load enic classes
        $this->user     =& enic::get('user');
        $this->matrix   =& enic::get('matrix');
        $this->menu     =& enic::get('menu');
        $this->model    =& enic::get('model');



    }

}

