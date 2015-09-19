<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author Alex
 */
class LocatorController {
    private $DAO;
    
    function __construct() {
        $this->DAO = new LocatorDAO();
    }
    
    
    public function getRegioni(){
        $regioni = $this->DAO->getRegioni();
        
        if(count($regioni) > 0){
            $result = array();
            foreach($regioni as $regione){
                $temp = array();
                $temp['cod'] = $regione->cod_regione;
                $temp['nome'] = $regione->regione;
                array_push($result, $temp);
            }
            
            return $result;        
        }
        return false;
        
    }
    
    public function getProvince($cod_regione){
        $province = $this->DAO->getProvince($cod_regione);
        if(count($province) > 0){
            $result = array();
            foreach($province as $provincia){
                $temp = array();
                $temp['cod'] = $provincia->cod_provincia;
                $temp['nome'] = $provincia->provincia;
                $temp['sigla'] = $provincia->sigla;
                
                array_push($result, $temp);
            }
            
            return $result;
        }
        return false;
    }
    
    public function getRegioneById($id){
        return $this->DAO->getRegioneById($id);        
    }
    
    public function getProvinciaById($id){
        return $this->DAO->getProvinciaById($id);
    }

}
