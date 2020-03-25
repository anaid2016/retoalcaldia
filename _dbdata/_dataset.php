<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of _dataset
 *
 * @author ANAID
 */
class _dataset {
     
    protected function db_PDOconnet() {

        $username = "root";
        $password = "";
        $servername = 'localhost';
        $dbname = "indicadores_ciudad";
        $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        return $dbh;
    }
    
    
    public function _setsistemas() {

        try {
            $dbh2 = $this->db_PDOconnet();
            $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        $_arraydata=array();

        $sql = "SELECT * FROM cv_sistemas ";
        $stmt = $dbh2->prepare($sql);
        $stmt->execute();
        $valores = $stmt->fetchAll();

        return $valores;
    }
    
    
    public function _standardRevision(){
        
        
        try {
            $dbh2 = $this->db_PDOconnet();
            $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        $sql = "SELECT
            cv_grupo.id_grupo,
            cv_grupo.nombre_grupo,
            cv_indicadores.id_indicador,
            cv_indicadores.nombre_indicador,
            cv_indicadores.valor_maximo_reporte,
            cv_indicadores.valor_minimo_reporte,
            min(cv_valor_indicador.fecha) as fechamin,
            max(cv_valor_indicador.fecha) as fechamax,
            max(cv_valor_indicador.valor) as maximo_valor_fechas,
            min(cv_valor_indicador.valor) as minimo_valor_fechas,
            datahoy.valor
                FROM
                cv_valor_indicador
                LEFT JOIN cv_indicadores ON cv_indicadores.id_indicador = cv_valor_indicador.id_indicador
                LEFT JOIN cv_grupo ON cv_grupo.id_grupo = cv_indicadores.id_grupo 
                LEFT JOIN 
                (
                        SELECT cv_valor_indicador.id_indicador,cv_valor_indicador.valor FROM cv_valor_indicador  WHERE cv_valor_indicador.fecha = '2020-03-25'
                ) AS datahoy ON datahoy.id_indicador=cv_indicadores.id_indicador
          WHERE
	fecha BETWEEN '2020-02-20' 
	AND '2020-03-25' GROUP BY cv_indicadores.id_indicador  ORDER BY cv_grupo.id_grupo";
        
        $stmt = $dbh2->prepare($sql);
         
        $stmt->execute();
        $valores = $stmt->fetchAll();
        return $valores;
    }
    

    public function _setValorIndicador($fecha_inicial,$fecha_final,$id_indicador=null,$id_grupo=null) {

        try {
            $dbh2 = $this->db_PDOconnet();
            $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $sql = "SELECT
	cv_grupo.id_grupo,
	cv_grupo.nombre_grupo,
	cv_indicadores.id_indicador,
	cv_indicadores.nombre_indicador,
	cv_indicadores.valor_maximo_reporte,
	cv_indicadores.valor_minimo_reporte,
	cv_valor_indicador.fecha,
	cv_valor_indicador.valor,
        media.mediavalue
        FROM
	cv_valor_indicador
	LEFT JOIN cv_indicadores ON cv_indicadores.id_indicador = cv_valor_indicador.id_indicador
	LEFT JOIN cv_grupo ON cv_grupo.id_grupo = cv_indicadores.id_grupo 
        LEFT JOIN (
            SELECT avg(cv_valor_indicador.valor) as mediavalue,cv_valor_indicador.id_indicador FROM  cv_valor_indicador
            WHERE
            fecha BETWEEN :fecha_inicial 
            AND :fecha_final GROUP BY cv_valor_indicador.id_indicador
        ) as media on media.id_indicador = cv_valor_indicador.id_indicador
        WHERE
	fecha BETWEEN :fecha_inicial 
	AND :fecha_final ";
        
        if(!is_null($id_indicador)){
            $sql .= "and cv_indicadores.id_indicador = :indicador ";
        }
        
        if(!is_null($id_grupo)){
            $sql .= "and cv_grupo.id_grupo = :idgrupo ";
        }
        
        
        
        $stmt = $dbh2->prepare($sql);
        $stmt->bindValue(':fecha_inicial', $fecha_inicial);
        $stmt->bindValue(':fecha_final', $fecha_final);
        
        if(!is_null($id_indicador)){
            $stmt->bindValue(':indicador', $id_indicador);
        }
        
        if(!is_null($id_grupo)){
            $stmt->bindValue(':idgrupo', $id_grupo);
        }
        
        $stmt->execute();
        $valores = $stmt->fetchAll();
        return $valores;
    }
    
}
