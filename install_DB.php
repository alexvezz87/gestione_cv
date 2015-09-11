<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

function install_gestione_cv_DB(){
    //funzione che installa le tabelle nel DB
    
    try{
        global $wpdb;
        $charset_collate = "";
        //prefisso
        $wpdb->prefix = 'twn_';
        if (!empty ($wpdb->charset)){
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty ($wpdb->collate)){
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        
        //richiamo le funzioni di installazione tabelle
        install_twn_ruoli($wpdb, $charset_collate);
        install_twn_cv($wpdb, $charset_collate);
        return true;        
        
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
    
}    
    //installo la tabella dei ruoli
    function install_twn_ruoli($wpdb, $charset_collate){
        $table = $wpdb->prefix.'ruoli';
        $query = "CREATE TABLE IF NOT EXISTS $table (
                 ID INT NOT NULL auto_increment PRIMARY KEY,                
                 nome VARCHAR(100) NOT NULL,                
                 categoria INT,                
                 pubblicato INT                 
                 );{$charset_collate}";        
        try{
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($query);   
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }         
        
    }
    
    //installo la tabella dei cv
    function install_twn_cv($wpdb, $charset_collate){
        $table = $wpdb->prefix.'cvs';
        $query = "CREATE TABLE IF NOT EXISTS $table (
                 ID INT NOT NULL auto_increment PRIMARY KEY,
                 data_inserimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                 nome VARCHAR(100) NOT NULL,
                 cognome VARCHAR(100) NOT NULL,
                 email VARCHAR(100) NOT NULL,
                 categoria INT,
                 ruolo INT,
                 regione VARCHAR(2), 
                 provincia VARCHAR(3),
                 cv TEXT NOT NULL,
                 pubblicato INT                 
                 );{$charset_collate}";
        try{
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($query);   
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }         
        
    }
    
    function deInstall_gestione_cv_DB(){
        global $wpdb;
        $wpdb->prefix = 'twn_';
        
        dropTableRuoli($wpdb);
        dropTableCV($wpdb);
        
        return true;
    }
    
    function dropTableRuoli($wpdb){
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."ruoli;";
            $wpdb->query($query);
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    
    function dropTableCV($wpdb){
        try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."cvs;";
            $wpdb->query($query);
            return true;
        }
        catch(Exception $e){
            _e($e);
            return false;
        }
    }
    



?>