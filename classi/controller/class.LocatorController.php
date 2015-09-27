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
        //print_r($province);
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
    
    public function getProvinceByAjaxCall($cod_regione, $mydb){
        $province = $this->DAO->getProvinceByAjaxCall($cod_regione, $mydb);
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
    
    public function getCodRegioneByProv($provincia){
        //Dato il non allineamento dei valori di provincia nelle tabelle di buddypress
        //devo fare un controllo se questi valori sono rappresentati in modo di SIGLA PROVINCIA o NOME COMPLETO
        
        //primo controllo su SIGLA
        $result = array();
        $temp = null;
        if($this->DAO->getCodRegioneBySiglaProv($provincia) != null){
            $temp = $this->DAO->getCodRegioneBySiglaProv($provincia);
        }
        else if($this->DAO->getCodRegioneByNomeProv($provincia)!= null){
            $temp = $this->DAO->getCodRegioneByNomeProv($provincia);
        }        
        
        if($temp != null){
            $result['cod_regione'] = $temp->cod_regione;
            $result['cod_provincia'] = $temp->cod_provincia;
        }
        
        return $result; 
    }

}
