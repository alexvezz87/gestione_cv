<?php

/**
 * @package gestione_cv
 */
/*
Plugin Name: Gestione CV
Plugin URI: http://www.theworknote.com/
Description: Plugin che permette la gestione del sistema di inserimento/visualizzazione dei Curriculum Vitae degli utenti di The Work Note
Version: 1.0
Author: Alex Soluzioni Web
Author URI: http://www.alexsoluzioniweb.it/
License: GPLv2 or later
*/


//includo le librerie
require_once 'install_DB.php';


//creo il db al momento dell'attivazione
register_activation_hook(__FILE__,'install_DB');
function install_DB(){
    install_gestione_cv_DB();
}


//rimuovo il db quando disattivo il plugin
 register_deactivation_hook( __FILE__, 'remove_DB');
function remove_DB(){
    deInstall_gestione_cv_DB();
}


?>