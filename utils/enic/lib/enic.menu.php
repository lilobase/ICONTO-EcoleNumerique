<?php
 class enicMenu extends enicTree {

     public function displayMain(){
        $html = '<li>'.$this->_name.'</li>';
        return $html;
     }

     public function displayHeader(){
        $html = '<ul>';
        return $html;
     }

     public function displayFooter(){
         $html = '</ul>';
        return $html;
     }

 }