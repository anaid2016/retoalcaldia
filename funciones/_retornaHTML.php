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

require_once '_dbdata/_dataset.php';

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
    
    
    public function _htmlIndicadoresGrupos_Dashboard(){
        
        $html='';
        $_setdata = new _dataset();
        $valores = $_setdata->_standardRevision();
        $_grupoactual = 0;

        foreach($valores  as $clave){
            
            if($_grupoactual != $clave['id_grupo']){
                
                if($_grupoactual!='0'){
                    $html.='<div style="clear: both">&nbsp;</div>';
                }
                
                $html .= '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">';
                $html .= '<h1 class="h2">'.$clave['nombre_grupo'].'</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                              <div class="btn-group mr-2">
                                <button class="btn btn-sm btn-outline-secondary">...</button>
                              </div>
                              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                <span data-feather="calendar"></span>
                                    Esta Semana
                              </button>
                            </div>
                          </div>';
                
                
            }
            
            //Evaluando Indicador =====================================================================================
            if($clave['valor']>$clave['valor_maximo_reporte']){
                $_colorbackground = 'Tomato';
            }else if($clave['valor']==$clave['valor_maximo_reporte']){
                $_colorbackground = 'Violet';
            }else{
                $_colorbackground = 'white';
            }
            
            
            
            $html .= '<div class="caja-dashboard" style="background-color:'.$_colorbackground.'">';
                $html .= '<table border="0">';
                    $html .= '<tr>'
                            . '<td colspan="2" style="text-align:center;font-weight:bolder">'.$clave['nombre_indicador'].'</td>'
                            . '</tr>'
                            . '<tr><td colspan="2">&nbsp;</td></tr>'
                            . '<tr>'
                            . '<td>Fechas:</td>'
                            . '<td>'.$clave['fechamin'].' a '.$clave['fechamax'].'<td>'
                            . '</tr>'
                            . '<tr>'
                            . '<td>Ultimo valor Registrado: </td>'
                            . '<td>'.$clave['valor'].'<td>'
                            . '</tr>'
                            . '<tr>'
                            . '<td>Valor Maximo/Minimo Reportado: </td>'
                            . '<td>'.$clave['maximo_valor_fechas']." / ".$clave['minimo_valor_fechas'].'<td>'
                            . '</tr>'
                            . '<tr>'
                            . '<td>Limites Establecidos: </td>'
                            . '<td>'.$clave['valor_maximo_reporte']." / ".$clave['valor_minimo_reporte'].'<td>'
                            . '</tr>'
                            . '</table>';
                    
                $html .= '<div style="text-align:right;padding:3px 1px;"><a href="pages/grafica.php?setgrafica='.$clave['id_indicador'].'" class="btn btn-sm btn-outline-secondary">Ver Gráficos</a></div>';    
                
            $html.='</div>';
           $_grupoactual = $clave['id_grupo'];
        }

        return $html;

//          <canvas class="my-4" id="myChart" width="900" height="380"></canvas>
        
    }
    
}
