<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//includo i controller
//require_once plugin_dir_path().'classi/controller/class.CvController.php';
//require_once '../../controller/class.RuoloController.php';


/**
 * Description of class
 *
 * @author Alex
 */
class WriterCV {

    private $cvController;
    private $ruoloController;
    private $locatorController;
    
    function __construct() {
        $this->cvController = new CvController();
        $this->ruoloController = new RuoloContoller();
        $this->locatorController = new LocatorController();
    }
    
    
    //metodi
    function getCvController() {
        return $this->cvController;
    }

    function getRuoloController() {
        return $this->ruoloController;
    }

    function setCvController($cvController) {
        $this->cvController = $cvController;
    }

    function setRuoloController($ruoloController) {
        $this->ruoloController = $ruoloController;
    }

    
    function checkData($data){
        if(!(isset($data['nome']) && $data['nome'] != null && trim($data['nome']) != '')){               
            echo 'check nome: NON valido<br>';
            return false;
        }

        if(!(isset($data['cognome']) && $data['cognome'] != null && trim($data['cognome']) != '')){                
            echo 'check cognome: NON valido<br>';
            return false;
        }

        if(!(isset($data['email']) && $data['email'] != null && $data['email'] != '' && filter_var($data['email'], FILTER_VALIDATE_EMAIL) )){
            $check_1 = false;
           echo 'check NON email: valido<br>';
           return false;
        }
        if(!(isset($_FILES['carica-cv']) && $_FILES['carica-cv'] != null && $_FILES['carica-cv']['error']) == 0){
            $check_1 = false;
            print_r($_FILES['carica-cv']);
            echo '<br>check CV: NON valido<br>';
            return false;
        }
        
        return true;            
    }
    
    function checkFolder($folder){
        if(!file_exists($folder)){           
            //creo la cartella
           if(!wp_mkdir_p($folder)){               
               echo '<br>Errore nel creare la cartella';
           }
        }
    }
    
    function uploadCvOnFileSystem($file, $folder){
        //print_r($file);
        if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name'])){
            if(move_uploaded_file($file['tmp_name'],$folder.'/'.$file['name'])){
                return true;
            }
        }
        return false;
    } 
    
    
    public function listenerCvForm(){
        
        //se è stato inviato un cv attivo l'ascoltatore
        if(isset($_POST['invia-cv'])){
            //controllo lato server dei campi obbligatori: Nome, Cognome, Email, Categoria, CV            
            if($this->checkData($_POST)){
                
               //print_r($_POST);
//                print_r($_FILES);
                
                //impongo un path dove salvare il cv
                $folder = 'upload_CV';                
                //controllo se il path esiste
                $this->checkFolder($folder);
                //carico nel filesystem
                $check_upload = $this->uploadCvOnFileSystem($_FILES['carica-cv'], $folder);              
                
                if($check_upload){
                    //se l'upload è avvenuto correttamente, salvo i dati nel database
                    $cv = new CV();
                    //campi obbligatori
                    $cv->setCategoria(strip_tags($_POST['categoria']));
                    $cv->setCognome(strip_tags(trim($_POST['cognome'])));
                    $cv->setCv($folder.'/'.$_FILES['carica-cv']['name']);
                    $cv->setEmail(strip_tags(trim($_POST['email'])));
                    $cv->setNome(strip_tags(trim($_POST['nome'])));
                    //campi non obbligatori
                    if(isset($_POST['ruolo']) && $_POST['ruolo']!=''){
                        $cv->setRuolo($_POST['ruolo']);
                    }
                    
                    //se l'utente inserisce un ruolo che non è stato confermato dall'amministratore
                    //il processo di associazione di quel ruolo al cv avviene con un precedente inserimento del ruolo indicato nel db
                    //il ruolo verrà aggiunto nel db e sarà associato all'attuale cv
                   
                    if(isset($_POST['altro-ruolo']) && $_POST['altro-ruolo'] != ''){
                        $ruolo = new Ruolo();
                        $ruolo->setCategoria(strip_tags($_POST['categoria']));
                        $ruolo->setNome(strip_tags(trim($_POST['altro-ruolo'])));
                        //imposto il ruolo come non ancora pubblicato
                        $ruolo->setPubblicato(0);                       
                        //salvo il ruolo nel db e ottengo l'id appena salvato
                        $idNuovoRuolo = $this->ruoloController->saveRuolo($ruolo);                       
                        
                        if(!(isset($_POST['ruolo']) && $_POST['ruolo'] != '' )){                           
                            //se non ho un ruolo già salvato dal campo $_POST['ruolo'] (ha la precedenza su questo), lo salvo
                            $cv->setRuolo($idNuovoRuolo);
                        }
                        
                    }
                    
                    if(isset($_POST['regione'])){
                        $cv->setRegione($_POST['regione']);
                    }
                    
                    if(isset($_POST['provincia'])){
                        $cv->setProvincia($_POST['provincia']);
                    }
                    
                    //eseguo il controllo se pubblicare o meno il cv 
                    //ciò è dato dalla pubblicazione del RUOLO. Se il Ruolo è pubblicato, pubblico anche il CV
                    if($this->ruoloController->isRuoloPubblicato($cv->getRuolo())){
                        $cv->setPubblicato(1);
                    }
                    else{
                        $cv->setPubblicato(0);
                    }
                    
                    //salvo il cv nel db
                    if($this->cvController->saveCV($cv)){
                        echo '<br>CV salvato!';
                    }
                    else{
                        echo '<br>CV già presente nel DB!';
                    }
                    
                    
                }
                
                
                
               
            }
            else{
                echo 'KO!';
            }            
            
        }
       
        
    }
    
    public function printInsertCvForm(){
        
?>
        <h2>Form di Inserimento</h2>
        <form id="inserimento-cv" name="inserimento-cv" action="<?php echo curPageURL() ?>" method="POST" enctype="multipart/form-data">
            <div class="field">
                <label for="nome">Nome* </label>
                <input type="text" id="nome" value="" name="nome" required/>
            </div>
            <div class="field">
                <label for="cognome">Cognome* </label>
                <input type="text" id="cognome" value="" name="cognome" required/>
            </div>
            <div class="field">
                <label for="email">Email* </label>
                <input type="email" id="email" name="email" value="" required/>
            </div>
            <div class="field">
                <label for="select-categoria">Categoria occupazionale* </label>
                <?php echo getSelectCategoriaCommerciale();  ?>
            </div>
            <div id="contenitore-selettore-ruoli" class="field"></div>
            <div class="field">
                <label for="altro-ruolo">Inserisci un ruolo, se non presente</label>
                <input type="text" id="altro-ruolo" name="altro-ruolo" value="" />
            </div>
            <div class="field doppio">
                <?php echo $this->printRegioniProvince(); ?>
            </div>
            <div class="field">
                <label for="carica-cv">Carica il CV* </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
                <input id="carica-cv" name="carica-cv" type="file" required>
            </div>
            <div class="submit-button">
                <input type="submit" name="invia-cv" value="Invia Curriculum" />
            </div>
        </form>
    <?php   
        //stampo le call Ajax delle select5
        $this->printAjaxCallRegioniProvince();
        $this->printAjaxCallRuoli();        
   
    }    
    
  
       
    function printRegioniProvince(){
        
        $html = 'Dove cerchi occupazione ?'
                . '<div id="container-regione">'
                . '<label for="regione">Regione</label>'
                . '<select id="regione" name="regione">'
                . '<option value=""></option>';

        //ottengo le regioni
        $regioni = $this->locatorController->getRegioni();        
        foreach($regioni as $regione){
            $html.= '<option value="'.$regione['cod'].'">'.$regione['nome'].'</option>';
        }
                        
        $html.= '</select></div>';
        
        //ottengo le province mediante chiamata ajax
        $html.= '<label for="provincia">Provincia</label>'
                . '<div id="container-province"></div>';
        
        return $html;        
    }
    
    
    function printAjaxCallRuoli(){
?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var idCat = jQuery('#select-categoria').val();
                jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                
    
                jQuery(document).on('change', '#select-categoria',function(){
                    idCat = jQuery('#select-categoria').val();                    
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                });
                
                
            });
            
            function printRuoli(data){
                jQuery(function(){
                    //pulisco
                    jQuery('#contenitore-selettore-ruoli').html("");
                    
                    var html = '<label for="ruolo">Seleziona Ruolo</label>';
                    if(data.length > 0){
                        html += '<select id="ruolo" name="ruolo">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            html += '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                        }
                        html+= '</select>';
                    }
                    else{
                        html += '<br>Ruoli non acora disponibili per questa categoria';
                    }
                    
                    jQuery('#contenitore-selettore-ruoli').append(html);
                });
            }
            
        </script>
<?php        
    }
    
    
    function printAjaxCallRegioniProvince(){
 ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var idRegione = jQuery('#regione').val();
                 //pulisco
                    jQuery('#container-province').html("");
                if(idRegione !== ''){
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                }
    
                jQuery(document).on('change', '#regione',function(){
                    idRegione = jQuery('#regione').val();   
                     //pulisco
                    jQuery('#container-province').html("");
                    if(idRegione !== ''){
                        jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                    }
                });
                
                
            });
            
            function printProvince(data){
                jQuery(function(){                   
                     
                    var html = ""; 
                    if(data.length > 0){
                        html += '<select id="provincia" name="provincia">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            html += '<option value="'+data[i].cod+'">'+data[i].nome+'</option>';
                        }
                        html+= '</select>';
                    }
                    else{
                        
                    }
                    
                    jQuery('#container-province').append(html);
                });
            }
            
        </script>
<?php
    }

    
}
