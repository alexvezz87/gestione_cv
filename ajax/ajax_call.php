<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//da cambiare
$temp_path = "/in_elaborazione/TheWorkNote/wordpress/"; //LOCALE
//$temp_path = "/progetti/twn"; //STAGE



include $_SERVER['DOCUMENT_ROOT'].$temp_path.'/wp-load.php';

require_once '../classi/classes.php';


add_action( 'wp_ajax_my_action', 'twn_ajax_callback' );

twn_ajax_callback();

function twn_ajax_callback(){
    //chiamata per aggiornare dinamicamente i ruoli data una determinata categoria
    if(isset($_POST['id_categoria'])){
        $ruoloController = new RuoloContoller();

        $result = $ruoloController->getRuoliByCategory($_POST['id_categoria'], 1); //il valore 1 indica i ruoli pubblicati
        echo json_encode($result);    
    }


    //chiamata per ottenere la provincia 
    if(isset($_POST['id_regione'])){

        $locatorController = new LocatorController();
        $result = $locatorController->getProvince($_POST['id_regione']);      
        echo json_encode($result);
    }

}

?>

