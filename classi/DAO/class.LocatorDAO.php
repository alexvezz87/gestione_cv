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
class LocatorDAO {
    private $wpdb;
    private $regione;
    private $provincia;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'twn_';
        $this->wpdb = $wpdb;
        $this->regione = $wpdb->prefix.'regioni';
        $this->provincia = $wpdb->prefix.'province';
    }
    
    //metodi
    

    public function getRegioni(){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->regione." ORDER BY regione ASC";           
            
            return $this->wpdb->get_results($query);
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function getProvince($cod_regione){
        try{
            $query = "SELECT * FROM ".$this->provincia." WHERE cod_regione = '".$cod_regione."' ORDER BY provincia ASC";
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }

    
}
