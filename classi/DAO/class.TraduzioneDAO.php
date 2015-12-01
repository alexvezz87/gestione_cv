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
class TraduzioneDAO {
    //definisco gli attributi
    private $wpdb;
    private $table;
    
    //definisco il costruttore
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'twn_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'traduzioni';
    }
    
    
    //definisco i metodi
    function getWpdb() {
        return $this->wpdb;
    }

    function getTable() {
        return $this->table;
    }

    function setWpdb($wpdb) {
        $this->wpdb = $wpdb;
    }

    function setTable($table) {
        $this->table = $table;
    }

    /**
     * Funzione che salva una traduzione nel db
     * @param Traduzione $traduzione
     * @return type
     */
    public function salvaTraduzione(Traduzione $traduzione){
        
        try{
            echo $traduzione->getCod();
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'cod' => addslashes($traduzione->getCod()),
                        'lan' => addslashes($traduzione->getLan()),
                        'text' => addslashes($traduzione->getText())
                    ),
                    array('%s', '%s', '%s')
                );
            
                echo $this->wpdb->insert_id;
            
                return $this->wpdb->insert_id;
            
        } catch (Exception $ex) {
            _e($ex);           
            return -1;
        }
    }
    
    /**
     * Funzione che controlla se una traduzione è già presente nel database
     * @param Traduzione $traduzione
     * @return boolean
     */
    public function isTraduzioneAlreadyInDB(Traduzione $traduzione){
        try{
            $result = $this->wpdb->get_var($this->wpdb->prepare(
                        "SELECT ID FROM ".$this->table." WHERE cod = %s AND lan = %s ",
                        addslashes($traduzione->getCod()),
                        addslashes($traduzione->getLan())
                    ));
            if($result != null){
                return true;
            }
            return false;
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    /**
     * Funzione che restituisce una traduzione passato codice e lingua
     * @param type $cod
     * @return type
     */
    public function getTraduzione($cod){
        try{
            $query = "SELECT * FROM ".$this->table." WHERE cod = '".$cod."' ";            
            return $this->wpdb->get_row($query);
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
        
    }
    
    /**
     * Funzione che aggiorna una traduzione nel db
     * @param Traduzione $traduzione
     * @return boolean
     */
    public function updateTraduzione(Traduzione $traduzione){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                      'text' => $traduzione->getText()  
                    ),
                    array('cod' => $traduzione->getCod()),
                    array('%s'),
                    array('%s')
                );
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
}
