<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//istanzio l'oggetto che stampa
$printer = new WriterCV();
$locatorController = new LocatorController();
$cvController = new CvController();
$languageController = new LanguageController();

//ottengo due info fondamentali per avere il comportamento corretto 
//1. ID UTENTE 
//2. CATEGORIA COMMERCIALE
//2. PROVINCIA

$user_ID = get_current_user_id(); //0 in caso di utente non registrato


//LISTENER per aggiornare la provincia
if(isset($_POST['aggiorna-provincia'])){
    //aggiorno i campi di provincia
    //ottengo il nome della pronvincia
    $provincia = $locatorController->getProvinciaById($_POST['provincia']);
    //aggiorno   
    if(updateBuddypressProvincia($_POST['id-utente'], $provincia->sigla) == true){
        echo '<div class="ok">Provincia aggiornata con successo!</div>';
    }
    else{
        echo '<div class="ko">Sono stati riscontrati dei problemi nell\'aggiornare la provincia.</div>';
    }
}



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

?>
            <div class="pre-form">
                <h2><?php echo $languageController->getTranslation('find-cv-title')  ?></h2>
                <p><?php echo $languageController->getTranslation('find-cv-text') ?></p>
                <h4><?php echo $languageController->getTranslation('how-it-works-title') ?></h4>
                <p><?php echo $languageController->getTranslation('how-it-works-text') ?></p>
                
            </div>
<?php
            $printer->printUserSearchBox($categoria);
           
           
            if(!isset($_POST['ricerca-cv'])){
           
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

                $param_2 = array();
                $param_2['categoria'] = $categoria;
                $param_2['regione'] = $location['cod_regione'];  
                $param_2['pubblicato'] = 1;
                $param_2['ordine'] = 'ruolo';
                $result_2 = $cvController->getCVsByParameters($param_2);

                $param_3 = array();
                $param_3['categoria'] = $categoria; 
                $param_3['pubblicato'] = 1;
                $param_3['ordine'] = 'ruolo';
                $result_3 = $cvController->getCVsByParameters($param_3);           

                $result = array_unique(array_merge($result_1, $result_2, $result_3), SORT_REGULAR);

                if(count($result > 0)){
                    $printer->printUserCVs($result);
                }
                else{
                    echo $languageController->getTranslation('no-curriculum-to-show');
                }           
           
            }
           
           
        }
        else{
           //non ho ottenuto la location dell'utente, questo vuol dire che ha scritto male la provincia o non compare nella tabella delle province
           //Bisogna invitare l'utente a selezionare una provincia esistente e aggiornare il suo campo provincia nella famiglia Indirizzo.
           echo $languageController->getTranslation('no-identification-province');
          
           echo '<form action="'.curPageURL().'" method="POST">';
           echo '<input type="hidden" name="id-utente" value="'.$user_ID.'" />';
           echo $printer->printRegioniProvince($languageController->getTranslation('select-region-province'));
           $printer->printAjaxCallRegioniProvince();
           echo '<input type="submit" name="aggiorna-provincia" value="'.$languageController->getTranslation('update-province').'">';
           echo '</form>';
        }        
        
        
    }
    else if($provincia == null){
        //Invito all'utente di compilare i campi indirizzo
        $current_user = wp_get_current_user();
        echo $languageController->getTranslation('profile-not-complete');
        echo '<a class="modifica-profilo" href="'.get_home_url().'/members/'.$current_user->user_login.'/profile/edit/group/2/">'.$languageController->getTranslation('complete-address').'</a>';
    }

}
else {
    //utente non registrato

    echo $languageController->getTranslation('user-not-registered');

} 

?>

<script type="text/javascript">
    jQuery(document).ready(function($){
       if($('#motore-ricerca').size() > 0){
           $('#motore-ricerca').hide();
       } 
    });
</script>