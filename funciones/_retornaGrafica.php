<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of _retornaHTML
 *
 * @author ANAID
 */

require_once '../_dbdata/_dataset.php';

class _retornaHTML {
 
    
    public function _htmlSistemas(){
        
        $html='';
        $_setdata = new _dataset();
        $sistemas = $_setdata->_setsistemas();
        
       foreach($sistemas  as $clave){
          $html .= '<li class="nav-item">
                <a class="nav-link" href="pages/visual.php?setval="'.$clave['id_sistema'].'>
                  <span data-feather="file"></span>
                  '.$clave['nombre_sistema'].'
                </a>
              </li>';
       }
        
        return $html;
    }
    
    
   
    
}
