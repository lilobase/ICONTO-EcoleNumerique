<?php
 class enicMenu extends enicTree {

     public function displayMain(){
        $html = '<li>'.$this->_name.'</li>';
        return $html;
     }

     public function displayIn(){
        $html = '<ul>';
        return $html;
     }

     public function displayOut(){
         $html = '</ul>';
        return $html;
     }

 }