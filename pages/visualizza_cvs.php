<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//istanzio l'oggetto che stampa
$printer = new WriterCV();
$locatorController = new LocatorController();
$cvController = new CvController();

//ottengo due info fondamentali per avere il comportamento corretto 
//1. ID UTENTE 
//2. CATEGORIA COMMERCIALE
//2. PROVINCIA

$user_ID = get_current_user_id(); //0 in caso di utente non registrato
if($user_ID != 0){
    //utente registrato
    $categoria = getIdCategoriaByUser($user_ID);
    $provincia = getValueProvinciaByUser($user_ID);   
    

    if($categoria != null && $provincia != null){
        //si procede all'elaborazione della provincia
       $location = $locatorController->getCodRegioneByProv($provincia);
       if(count($location) > 0){
           //ho ottenuto la location dell'utente
           //location['cod_regione'] --> REGIONE
           //location['cod_provincia'] --> PROVINCIA
           
           
           //I parametri indispensabili sono quelli ottenuti
           //Ho 3 query da fare.
           //1. Query specifica di Location --> regione e provincia
           //2. Query specifica di Location --> solo regione
           //3. Query non specifica di Location
           //Al termine mergiare i risultati ottenuti 
           
           
           $param_1 = array();
           $param_1['categoria'] = $categoria;
           $param_1['regione'] = $location['cod_regione'];
           $param_1['provincia'] = $location['cod_provincia'];
           $param_1['pubblicato'] = 1;
           $param_1['ordine'] = 'provincia, ruolo';
           $result_1 = $cvController->getCVsByParameters($param_1);
           
           echo '<h4>Categoria + Regione + Provincia</h4>';
           echo '<pre>';
           print_r($result_1);
           echo '</pre>';
           
           $param_2 = array();
           $param_2['categoria'] = $categoria;
           $param_2['regione'] = $location['cod_regione'];  
           $param_2['pubblicato'] = 1;
           $param_2['ordine'] = 'ruolo';
           $result_2 = $cvController->getCVsByParameters($param_2);
           
           echo '<h4>Categoria + Regione </h4>';
           echo '<pre>';
           print_r($result_2);
           echo '</pre>';
           
           $param_3 = array();
           $param_3['categoria'] = $categoria; 
           $param_3['pubblicato'] = 1;
           $param_3['ordine'] = 'ruolo';
           $result_3 = $cvController->getCVsByParameters($param_3);           
           echo '<h4>Categoria</h4>';
           echo '<pre>';
           print_r($result_3);
           echo '</pre>';
           
           $result = array_unique(array_merge($result_1, $result_2, $result_3), SORT_REGULAR);
          
           echo '<h4>Merge</h4>';
           echo '<pre>';
           if(count($result > 0)){
               print_r($result);
           }
           else{
               
               echo 'non ci sono curriculum da visualizzare';
           }
           
           echo '</pre>';
           
           
           
       }
       else{
           //non ho ottenuto la location dell'utente, questo vuol dire che ha scritto male la provincia o non compare nella tabella delle province
           //Bisogna invitare l'utente a selezionare una provincia esistente e aggiornare il suo campo provincia nella famiglia Indirizzo.
           echo 'non riconosco la tua provincia';
       }
        
        
        
    }
    else if($provincia == null){
        //Invito all'utente di compilare i campi indirizzo
        echo 'non hai compilato i campi indirizzo';
    }

}
else {
    //utente non registrato

    echo 'utente non registrato!';

} 

?>
