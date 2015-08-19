<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../model/class.CV.php';

/**
 * Description of class
 *
 * @author Alex
 */
class CvDAO {
    private $wpdb;
    private $table;
    
    //definisco il costruttore
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'twn_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'cvs';
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
     * La funzione, dato un cv, se non esiste, lo salva nel database
     * 
     * @param CV $cv
     * @return type
     */
    public function saveCV(CV $cv){
        try{
           
            if($this->isCvAlreadyInDB($cv) == false){
                //il cv non esiste
                //imposto il timezone
                date_default_timezone_set('UTC');
                $timestamp = date('Y-m-d H:i:s', strtotime("now")); 
                $this->wpdb->insert(
                            $this->table,
                            array(
                                'data_inserimento' => $timestamp,
                                'nome' => $cv->getNome(),
                                'cognome' => $cv->getCognome(),
                                'email' => $cv->getEmail(),
                                'categoria' => $cv->getCategoria(),
                                'ruolo' => $cv->getRuolo(),
                                'regione' => $cv->getRegione(),
                                'provincia' => $cv->getProvincia(),
                                'cv' => $cv->getCv(),
                                'pubblicato' => $cv->getPubblicato()
                            ),
                            array('%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d')
                        );
            }
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    /**
     * La funzione controlla se un cv passato esiste già nel database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function isCvAlreadyInDB(CV $cv){
        try{
            $result = $this->wpdb->get_var(
                        $this->wpdb->prepare(
                                    "SELECT ID FROM ".$this->table." WHERE email = %s AND categoria = %d",
                                    addslashes($cv->getEmail()),
                                    addslashes($cv->getCategoria())
                                )                    
                    );
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
     * La funzione dato un determinato cv, restituisce il suo ID
     * 
     * @param CV $cv
     * @return type
     */
    public function getCvID(CV $cv){
        try{
            $result = $this->wpdb->get_var(
                        $this->wpdb->prepare(
                                    "SELECT ID FROM ".$this->table." WHERE email = %s AND categoria = %d",
                                    addslashes($cv->getEmail()),
                                    addslashes($cv->getCategoria())
                                )                    
                    );
            if($result != null){
                return stripslashes($result);
            }
            return false;
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    /**
     * La funzione data una serie di parametri effettua una query e restituisce dei risultati filtrati
     * 
     * @param type $parameters
     * @return boolean
     */
    public function getCVsByParameters($parameters){
        try{
            /* Questa è la funzione che gestisce la query principale di ricerca
             * Struttura di $parameters:
             * $parameters['categoria'] --> indica la categoria, parametro obbligatorio
             * $parameters['ruolo']
             * $parameters['regione']
             * $parameters['provincia']
             * $parameters['nome']
             * $parameters['cognome']
             * $parameters['email']
             * $parameters['pubblicato']             *             
             */
            
            $query = "SELECT * FROM ".$this->table." WHERE categoria = ".$parameters['categoria'];
            //ruolo
            if( isset($parameters['ruolo']) && ($parameters['ruolo'] != null && $parameters['ruolo']!= '')){
                $query.=" AND ruolo = ".$parameters['ruolo'];
            }
            //regione
             if( isset($parameters['regione']) && ($parameters['regione'] != null && $parameters['regione']!= '')){
                $query.=" AND regione = '".$parameters['regione']."'";
            }
            //provincia
             if( isset($parameters['provincia']) && ($parameters['provincia'] != null && $parameters['provincia']!= '')){
                $query.=" AND provincia = '".$parameters['provincia']."'";
            }
            //nome
             if( isset($parameters['nome']) && ($parameters['nome'] != null && $parameters['nome']!= '')){
                $query.=" AND nome = '".$parameters['nome']."'";
            }
            //cognome
             if( isset($parameters['cognome']) && ($parameters['cognome'] != null && $parameters['cognome']!= '')){
                $query.=" AND cognome = '".$parameters['cognome']."'";
            }
            //email
             if( isset($parameters['email']) && ($parameters['email'] != null && $parameters['email']!= '')){
                $query.=" AND email = '".$parameters['email']."'";
            }
            //pubblicato
             if( isset($parameters['pubblicato']) && ($parameters['pubblicato'] != null && $parameters['pubblicato']!= '')){
                $query.=" AND pubblicato = ".$parameters['pubblicato']."";
            }
            
            return $this->wpdb->get_results($query);
             
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna i campi di un cv esistente riconosciuto tramite l'ID
     * 
     * @param CV $cv
     * @param type $idCV
     * @return boolean
     */
    public function updateCV(CV $cv, $idCV){
        try{
            $this->wpdb->update(
                        $this->table,
                        array(
                            'nome' => addslashes($cv->getNome()),
                            'cognome' => addslashes($cv->getCognome()),
                            'email' => addslashes($cv->getEmail()),
                            'categoria' => addslashes($cv->getCategoria()),
                            'ruolo' => addslashes($cv->getRuolo()),
                            'regione' => addslashes($cv->getRegione()),
                            'provincia' => addslashes($cv->getProvincia()),
                            'pubblicato' => addslashes($cv->getPubblicato())
                        ),
                        array('ID' => $idCV),
                        array('%s', '%s', '%s', '%d', '%d', '%s', '%s', '%d'),
                        array('%d')
                    );
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione elimina un determinato cv passandogli l'ID
     * 
     * @param type $idCv
     * @return boolean
     */
    public function deleteCV($idCv){
        try{
            $this->wpdb->delete($this->table, array('ID' => $idCv));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }


}


?>