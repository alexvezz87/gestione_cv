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
require_once 'classi/classes.php';
require_once 'functions.php';

//indico la cartella dove è contenuto il plugin
require_once (dirname(__FILE__) . '/gestione_cv.php');


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


//inserisco gli shortcode

//stampo la pagina di inserimento cv
add_shortcode('printInsertCV', 'print_insert_cv');
function print_insert_cv(){
    
    $printer = new WriterCV();
    
    $printer->listenerCvForm();
    $printer->printInsertCvForm();    
}

//stampo la pagina di visualizzazione CVs per gli utenti
add_shortcode('printShowCVs', 'print_show_cvs');
function print_show_cvs(){
    include 'pages/visualizza_cvs.php';
}


//Aggiungo il menu
function add_admin_menu(){
    add_menu_page('Gestione CV', 'Gestione CV', 'administrator', 'gestione_cv', 'add_admin_page', plugins_url('images/icona_20x28.png', __FILE__), 9);
    add_submenu_page('gestione_cv', 'Gestione Ruoli', 'Gestione Ruoli', 'administrator', 'gestione_ruoli', 'add_pagina_gestione_ruoli');
    add_submenu_page('gestione_cv', 'Gestione Traduzioni', 'Gestione Traduzioni', 'administrator', 'gestione_traduzioni', 'add_pagina_gestione_traduzioni');
}

function add_admin_page(){
    include 'menu_pages/gestione_curriculum.php';
}

function add_pagina_gestione_ruoli(){
    include 'menu_pages/gestione_ruoli.php';
}


function add_pagina_gestione_traduzioni(){
    include 'menu_pages/gestione_traduzioni.php';
}

add_action('admin_menu', 'add_admin_menu');


//Aggiungo gli stili
add_action( 'admin_enqueue_scripts', 'register_admin_style' );
    
//richiamo lo stile
function register_admin_style() {
    wp_register_style( 'admin_style_css', plugins_url('gestione_cv/css/admin_style.css') );
    wp_enqueue_style('admin_style_css');
}

 add_action( 'wp_enqueue_scripts', 'register_style' );
 function register_style() {
        wp_register_style( 'style_css', plugins_url('gestione_cv/css/style.css') );
        wp_enqueue_style('style_css');
    }


?>