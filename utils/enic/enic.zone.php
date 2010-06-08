<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
abstract class enicZone extends CopixZone {

    protected $user;
    protected $matrix;
    protected $menu;
    protected $options;


    public function __construct(){
        //load enic classes
        $this->user     =& enic::get('user');
        $this->options  =& enic::get('options');

        //load matrice & cache
        enic::to_load('cache');
        enic::to_load('matrix');
        $this->matrix   =& enic::get('matrixCache');

        $this->menu     =& enic::get('menu');
        $this->model    =& enic::get('model');
    }

}
?>
