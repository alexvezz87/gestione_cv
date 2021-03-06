<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once '../model/class.Ruolo.php';

/**
 * Description of class
 *
 * @author Alex
 */
class RuoloDAO {
    //definisco gli attributi
    private $wpdb;
    private $table;
    
    //definisco il costruttore
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'twn_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'ruoli';
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
     * La funzione permette di salvare un ruolo non esistente all'interno del database
     * 
     * @param Ruolo $ruolo
     * @return boolean     
     */
    public function salvaRuolo(Ruolo $ruolo){
        try{
            //prima di salvare controllo se il ruolo è già presente          
            
            //il ruolo non esiste
            $this->wpdb->insert(
                $this->table,
                array(
                    'nome' => addslashes($ruolo->getNome()),
                    'categoria' => addslashes($ruolo->getCategoria()),
                    'pubblicato' => addslashes($ruolo->getPubblicato())
                ),
                array('%s', '%d', '%d')
            );

            //restituisco l'id della riga inserita
            return $this->wpdb->insert_id;                
           
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
   
    /**
     * La funzione restituisce vero o falso a seconda se il ruolo è presente nel DB
     * @param Ruolo $ruolo
     */
    public function isRuoloAlreadyInDB(Ruolo $ruolo){
        try{
            $result = $this->wpdb->get_var($this->wpdb->prepare(
                        "SELECT ID FROM ".$this->table." WHERE nome = %s AND categoria = %d",
                        addslashes($ruolo->getNome()),
                        addslashes($ruolo->getCategoria())                    
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
     * La funzione restiuisce un oggetto che contiene i ruoli ottenuti data una determinata categoria
     * 
     * @param type $categoria
     * @return type
     */
    public function getRuoliByCategoria($categoria){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE categoria = ".$categoria;
            return $this->wpdb->get_results($query); 
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function getRuoliPubblicatiByCategoria($categoria){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE categoria = ".$categoria. " AND pubblicato = 1";
            return $this->wpdb->get_results($query); 
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function getRuoliNonPubblicatiByCategoria($categoria){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE categoria = ".$categoria. " AND pubblicato = 0";
            return $this->wpdb->get_results($query); 
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function getRuoliNonPubblicati(){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE pubblicato = 0";
            return $this->wpdb->get_results($query); 
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function getUltimiRuoliApprovati(){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE pubblicato = 1 ORDER BY ID DESC LIMIT 10";
            return $this->wpdb->get_results($query); 
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    public function searchRuoli($fields){
        try{
            //preparo la query
            $query = "SELECT * FROM ".$this->table." WHERE 1 = 1";
            if(isset($fields['nome']) && $fields['nome'] != ''){
                $query .= " AND nome LIKE '%".$fields['nome']."%'";
            }
            if(isset($fields['categoria']) && $fields['categoria'] != ''){
                $query .= " AND categoria = ".$fields['categoria'];
            }
            if(isset($fields['pubblicato']) && $fields['pubblicato'] != -1){
                $query .= " AND pubblicato = ".$fields['pubblicato'];
            }
            
            return $this->wpdb->get_results($query); 
            
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    
    /**
     * Funzione che elimina un deterimanto ruolo dal database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function deleteRuolo($idRuolo){
        try{
            $this->wpdb->delete( $this->table, array( 'ID' => $idRuolo) );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione passato un ruolo controlla nel db se esiste e lo resituisce.
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function getRuolo(Ruolo $ruolo){
        if($this->isRuoloAlreadyInDB($ruolo)== true){
            try{
                $query = "SELECT * FROM ".$this->table." WHERE nome = ".$ruolo->getNome()." AND categoria = ".$ruolo->getCategoria();
                return $this->wpdb->get_row($query);
            }
            catch(Exception $ex){
                _e($ex);
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    public function getRuoloByID($idRuolo){
       
        try{
            $query = "SELECT * FROM ".$this->table." WHERE ID = ".$idRuolo;  
            return $this->wpdb->get_row($query);
        }
        catch(Exception $ex){
            _e($ex);
            return false;
        }
        
       
    }
    
    /**
     * La funzione dato un ruolo restituisce il suo ID
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function getIDRuolo(Ruolo $ruolo){
        try{
            $result = $this->wpdb->get_var(
                        $this->wpdb->prepare(
                                    "SELECT ID FROM ".$this->table." WHERE nome = %s AND categoria = %d",
                                    addslashes($ruolo->getNome()),
                                    addslashes($ruolo->getCategoria())
                                )
                    );
            if($result != null){
                return stripslashes($result);
            }
            return false;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getPubblicato(Ruolo $ruolo){
        try{
             $result = $this->wpdb->get_var(
                        $this->wpdb->prepare(
                                    "SELECT pubblicato FROM ".$this->table." WHERE nome = %s AND categoria = %d",
                                    addslashes($ruolo->getNome()),
                                    addslashes($ruolo->getCategoria())
                                )
                    );
            if($result != null){
                return stripslashes($result);
            }
            return false;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna un determinato ruolo nel database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function updateRuolo(Ruolo $ruolo, $idRuolo){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                        'nome' => addslashes($ruolo->getNome()),
                        'categoria' => addslashes($ruolo->getCategoria()),
                        'pubblicato' => addslashes($ruolo->getPubblicato())
                    ),
                    array('ID' => $idRuolo),
                    array('%s', '%d', '%d'),
                    array('%d')
            );
            
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
}

?>