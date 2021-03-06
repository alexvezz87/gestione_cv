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
    private $languageController;
    
    function __construct() {
        $this->cvController = new CvController();
        $this->ruoloController = new RuoloContoller();
        $this->locatorController = new LocatorController();
        $this->languageController = new LanguageController();
    }
    function getLocatorController() {
        return $this->locatorController;
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
            $_SESSION['errorEntry']='Nome inserito non valido';
            return false;
        }

        if(!(isset($data['cognome']) && $data['cognome'] != null && trim($data['cognome']) != '')){                
            $_SESSION['errorEntry']='Cognome inserito non valido';
            return false;
        }

        if(!(isset($data['email']) && $data['email'] != null && $data['email'] != '' && filter_var($data['email'], FILTER_VALIDATE_EMAIL) )){
            $check_1 = false;
            $_SESSION['errorEntry']='Email inserita non valida';
            return false;
        }
        if(!(isset($_FILES['carica-cv']) && $_FILES['carica-cv'] != null && $_FILES['carica-cv']['error']) == 0){
            $check_1 = false;
            //print_r($_FILES['carica-cv']);
            $_SESSION['errorEntry']='File caricato non valido';
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
    
    function uploadCvOnFileSystem($file, $folder, $newNameFile){
        //print_r($file);
       
        if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name'])){
            if(move_uploaded_file($file['tmp_name'], $folder.'/'.$newNameFile.'.'.$extension)){
                return true;
            }
        }
        return false;
    } 
      
    
    /**
     * Funzione che ascolta il form di inserimento Curriculum
     */
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
                
                /*MODIFICA PER CAMBIARE IL NOME DEL FILE SALVATO */
                $nameFile = str_replace('.', '-', strip_tags(trim($_POST['email'])));
                $nameFile .= '-'.strip_tags($_POST['categoria']);               
                 //ottengo l'estensione del file
                $temp = explode('.', $_FILES['carica-cv']['name']);
                $extension = $temp[count($temp)-1];
                $nameFile.='.'.$extension;                
                
                /*FINE MODIFICA */
                
                $check_upload = $this->uploadCvOnFileSystem($_FILES['carica-cv'], $folder, $nameFile);              
                
                if($check_upload){
                    //se l'upload è avvenuto correttamente, salvo i dati nel database
                    $cv = new CV();
                    //campi obbligatori
                    $cv->setCategoria(strip_tags($_POST['categoria']));
                    $cv->setCognome(strip_tags(trim($_POST['cognome'])));
                    $cv->setCv($folder.'/'.$nameFile);
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
                    
                    if($cv->getRuolo()!= null){
                        //se il ruolo è stato inserito allora bisogna che questo venga controllato
                        //se il ruolo esiste ed è pubblicato allora il cv può essere pubblicato
                        //se il ruolo non è presente oppure non è ancora stato approvato, il cv non può essere pubblicato
                    
                        if($this->ruoloController->isRuoloPubblicato($cv->getRuolo())){
                            //AGGIUNTA --> Il CV viene pubblicato anche se non è associato al ruolo.
                            $cv->setPubblicato(1);
                        }
                        else{
                            $cv->setPubblicato(0);
                        }
                    }
                    else{
                        //se il ruolo non è presente nel cv inviato, allora questo può essere pubblicato tranquillamente.
                        $cv->setPubblicato(1);
                    }                    
                    
                    //salvo il cv nel db
                    $valueSaveCV = $this->cvController->saveCV($cv);
                    if($valueSaveCV == 1){
                        echo '<div class="ok">Il Curriculum è stato caricato correttamente nel sistema!</div>';
                    }
                    else if($valueSaveCV == 2) {
                        echo '<div class="ok">Il Curriculum per la categoria occupazionale indicata è già presente nel sistema. Abbiamo provveduto ad aggiornare con l\'ultimo Curriculum fornito.</div>';
                    }
                    else{
                        echo '<div class="ko">Sono sopraggiunti errori nel caricare il Curriculum nel sistema.</div>';
                    }
                    
                    
                }
               
            }
            else{
                echo '<div class="ko">Abbiamo riscontrato un errore nel caricamento per il seguente motivo:';
                if(isset($_SESSION['errorEntry'])){
                    echo $_SESSION['errorEntry'];
                }
                echo '</div>';
            }            
            
        }
       
        
    }
    
    public function printInsertCvForm(){
        
?>
        <!-- Testo pre form -->
        <div class="container-pre-form">
            <div class="pre-form container-1024">
                <h2 class="col-xs-12"><?php echo $this->languageController->getTranslation('pre-form-upload-title') ?></h2>
                <p class="col-xs-12">
                    <?php echo $this->languageController->getTranslation('pre-form-upload-text') ?>
                </p>
                <div class="clear"></div>
            </div>    
        </div>
        <!-- fine Testo pre form -->
        
        <div class="container-title">
            <div class="container-1024">
                <h2 class="col-xs-12"><?php echo $this->languageController->getTranslation('sendCvTitle') ?></h2>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="container-form">
            <div class="container-1024">
                <form id="inserimento-cv" name="inserimento-cv" action="<?php echo curPageURL() ?>" method="POST" enctype="multipart/form-data">
                    <div class="field col-xs-12 col-sm-6">                
                        <input type="text" id="nome" value="" name="nome" placeholder="<?php echo $this->languageController->getTranslation('name') ?>" required/>
                    </div>
                    <div class="field col-xs-12 col-sm-6">               
                        <input type="text" id="cognome" value="" name="cognome" placeholder="<?php echo $this->languageController->getTranslation('surname') ?>" required/>
                    </div>
                    <div class="field col-xs-12">                
                        <input type="email" id="email" name="email" value="" placeholder="<?php echo $this->languageController->getTranslation('email') ?>" required/>
                    </div>
                    <div class="field col-xs-12 col-sm-4">
                        <label for="select-categoria"><?php echo $this->languageController->getTranslation('category') ?>* </label>
                        <?php echo getSelectCategoriaCommerciale();  ?>
                    </div>
                    <div id="contenitore-selettore-ruoli" class="field col-xs-12 col-sm-8"></div>
                    <div class="field add-ruolo col-xs-12">                
                        <input type="text" id="altro-ruolo" name="altro-ruolo" placeholder="<?php echo $this->languageController->getTranslation('add-role') ?>" value="" />
                    </div>
                    <div class="clear"></div>
                    <div class="doppio clear">
                        <?php echo $this->printRegioniProvince($this->languageController->getTranslation('occupation')); ?>
                    </div>
                    <div class="clear"></div>
                    <div class="field clear col-xs-12 col-sm-8 invia-curriculum">
                        <div class="image-curriculum"></div>
                        <label for="carica-cv"><?php echo $this->languageController->getTranslation('upload-cv') ?>* </label>
                        
                            <input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
                            <input id="carica-cv" name="carica-cv" type="file" required>
                           
                    </div>
                    
                    <div class="field col-xs-12 col-sm-4 privacy-field">
                        <input name="privacy" type="checkbox" value="1" required/> <?php echo $this->languageController->getTranslation('accept-privacy') ?>
                    </div>
                    <div class="clear"></div>
                    <div class="submit-button clear col-xs-12">
                        <input type="submit" name="invia-cv" value="<?php echo $this->languageController->getTranslation('send-cv') ?>" />
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
        
        <!-- testo post form -->
        <div clasS="container-post-form">
            <div class="container-1024">
                <div class="post-form">
                    <div class="hidden-xs img-componi-curriculum"></div>
                    <p class="link col-xs-6"><?php echo $this->languageController->getTranslation('post-form-link-cv') ?></p> 
                    <a class="button-link col-xs-6" target="_blank" href="<?php echo plugins_url().'/gestione_cv/download/CVTemplate_it_IT.doc' ?>">DOWNLOAD</a>
                    <div class="clear"></div>
                    <div class="col-xs-12 description-cv">
                        <?php echo $this->languageController->getTranslation('post-form-text') ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- end testo post form -->
        
         <script type="text/javascript">
             jQuery(document).ready(function($){
                if($('#motore-ricerca').size() > 0){
                    $('#motore-ricerca').hide();
                } 
             });
         </script>
    <?php   
        //stampo le call Ajax delle select5
        $this->printAjaxCallRegioniProvince();
        $this->printAjaxCallRuoli();        
   
    }      
  
    function printRegioniProvince($title){
        
        $html = '<div class="title col-xs-12">'.$title.'</div>'
                . '<div id="container-regione" class="container-regione col-xs-12 col-sm-6">'
                . '<div class="field">'
                . '<label for="regione">'.$this->languageController->getTranslation('region').'</label>'
                . '<select id="regione" name="regione">'
                . '<option value=""></option>';

        //ottengo le regioni
        $regioni = $this->locatorController->getRegioni();        
        foreach($regioni as $regione){
            $html.= '<option value="'.$regione['cod'].'">'.$regione['nome'].'</option>';
        }
                        
        $html.= '</select></div></div>';
        
        //ottengo le province mediante chiamata ajax
        $html.= '<div class="field container-provincia col-xs-12 col-sm-6"><label for="provincia">'.$this->languageController->getTranslation('province').'</label>'
                . '<div id="container-province"></div></div>';
        
        return $html;        
    }
    
    
    function printAjaxCallRuoli(){
?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var idCat = jQuery('select[name=categoria], select[name=ricerca-categoria]').val();
                jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                
    
                jQuery(document).on('change', 'select[name=categoria]',function(){
                    idCat = jQuery('select[name=categoria]').val();                    
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                });
                
                jQuery(document).on('change', 'select[name=ricerca-categoria]',function(){
                    idCat = jQuery('select[name=ricerca-categoria]').val();                    
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                });
                
                
            });
            
            function printRuoli(data){
                jQuery(function(){
                    //pulisco
                    jQuery('#contenitore-selettore-ruoli').html("");
                    
                    var html = '<label for="ruolo"><?php echo $this->languageController->getTranslation('select-role') ?></label>';
                    if(data.length > 0){
                        html += '<select id="ruolo" name="ruolo">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            html += '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                        }
                        html+= '</select>';
                    }
                    else{
                        html += '<div class="no-result"><?php echo $this->languageController->getTranslation('no-role') ?></div>';
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
                    //jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                    
                    jQuery.ajax({
                        type:'POST',
                        dataType: 'json',
                        url: '<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>',
                        data: {id_regione: idRegione},
                        success: function(data){                           
                                    printProvince(data);                           
                                }
                    });
                }
    
                jQuery(document).on('change', '#regione',function(){
                    idRegione = jQuery('#regione').val();   
                     //pulisco
                    jQuery('#container-province').html("");
                    if(idRegione !== ''){
                        //jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                        
                        jQuery.ajax({
                            type:'POST',
                            dataType: 'json',
                            url: '<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>',
                            data: {id_regione: idRegione},
                           success: function(data){                           
                                     printProvince(data);                           
                                    }      
                            
                        });
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

    public function listenerAdminCV(){
        //Listener di approva cv
        if(isset($_POST['approva-cv'])){
            $idCV = $_POST['idCV'];
            $cv = new CV();
            $cv = $this->cvController->getCvById($idCV);           
            if($cv != null){
                $cv->setPubblicato(1);       
                //aggiorno il ruolo
                if($this->cvController->updateCV($cv, $idCV)){
                    $this->cvController->sendConfirmEmail('update', $cv);
                    echo '<div class="ok">Il cv è stato pubblicato.</div>';
                }
                else{
                    echo '<div class="ko">Errore nel pubblicare il cv </div>';
                }                
            }
            unset($_POST['idCV']);
        }
        
         //Listner di elimina cv
        if(isset($_POST['elimina-cv'])){
            $idCV = $_POST['idCV'];            
            if($this->cvController->deleteCV($idCV)){
                echo '<div class="ok">Curriculum cancellato con successo!</div>';
            }
            else{
                echo '<div class="ko">Errore nella cancellazione del curriculum.</div>';
            }
            
            unset($_POST['idCV']);
        }
        
        //Listener di aggiorna CV
        if(isset($_POST['aggiorna-cv'])){
            //ottengo in questo caso il valore di idRuolo da salvare e aggiornare il cv
            
            $idCv = $_POST['idCV'];
            $idRuolo = $_POST['idRuolo'];
            //ottengo l'oggetto cv 
            $cv = $this->cvController->getCvById($idCv);
            //setto il nuovo ruolo
            $cv->setRuolo($idRuolo);
            //aggiorno il cv
            $this->cvController->updateCV($cv, $idCv);
        }
        
    }
    
    public function listenerAdminRuoli(){        
        
        //Listener di inserisci ruolo
        if(isset($_POST['inserisci-ruolo'])){
            //entrambi i field devono essere presenti
            if(isset($_POST['categoria']) && isset($_POST['ruolo']) && trim($_POST['ruolo'])!= ''){
                
                //creo l'istanza di ruolo
                $ruolo = new Ruolo();
                $ruolo->setCategoria($_POST['categoria']);
                $ruolo->setNome(trim($_POST['ruolo']));
                $ruolo->setPubblicato(1);
                
                //lo salvo nel db
                if($this->ruoloController->saveRuoloAdmin($ruolo) != false){
                    echo '<div class="ok">Ruolo salvato con successo!</div>';
                }
                else{
                    echo '<div class="ko">Il ruolo '.$ruolo->getNome().' è già presente nel sistema.</div>';
                }
                
            }
            else{
                echo '<div class="ko">Inserimento non avvenuto. Specificare entrambi i campi</div>';
            }
        }        
        
        //Listener di approva ruolo
        if(isset($_POST['approva-ruolo'])){
            $idRuolo = $_POST['idRuolo'];
            $ruolo = new Ruolo();
            $ruolo = $this->ruoloController->getRuoloById($idRuolo);
            if($ruolo != null){
                $ruolo->setPubblicato(1);
                //aggiorno il ruolo
                if($this->ruoloController->updateRuolo($ruolo, $idRuolo)){
                    echo '<div class="ok">Il ruolo '.$ruolo->getNome().' è stato pubblicato.</div>';
                }
                else{
                    echo '<div class="ko">Errore nel pubblicare il ruolo '.$ruolo->getNome().'</div>';
                }                
            }
            unset($_POST['idRuolo']);
        }
        
        //Listner di elimina ruolo
        if(isset($_POST['elimina-ruolo'])){
            $idRuolo = $_POST['idRuolo'];
            if($this->ruoloController->deleteRuolo($idRuolo)){
                echo '<div class="ok">Ruolo cancellato con successo!</div>';
            }
            else{
                echo '<div class="ko">Errore nella cancellazione del ruolo.</div>';
            }
            
            unset($_POST['idRuolo']);
        }
        
        //Listener di aggiorne ruolo
        if(isset($_POST['aggiorna-ruolo'])){         
            $ruolo = new Ruolo();
            $ruolo = $this->ruoloController->getRuoloById($_POST['idRuolo']); 
            $ruolo->setNome($_POST['tempNomeRuolo']);
            //aggiorno
            if($this->ruoloController->updateRuolo($ruolo, $_POST['idRuolo'])){
                echo '<div class="ok">Ruolo aggiornato correttamente!</div>';
            }
            else{
                echo '<div class="ko">Errore nell\'aggiornamento del ruolo.</div>';
            }
        }
        
    }
        
    function printStatoRuolo($ruolo){
        if($ruolo->pubblicato == 1){
            return 'Pubblicato';
        }
        else if($ruolo->pubblicato == 0){
            return '<form action="'.curPageURL().'" method="POST" class="container-approva"><input type="hidden" name="idRuolo" value="'.$ruolo->ID.'"/><input type="submit" name="approva-ruolo" value="Approva"></form>';
        }
    } 
    
    function printStatoCV($cv){
        if($cv->pubblicato == 1){
            return 'Pubblicato';
        }
        else if($cv->pubblicato == 0){
            //Al cv per approvarlo devo anche effettuare un controllo sul ruolo. 
            //Se il ruolo non è approvato non posso approvare anche il CV
            $ruolo = $this->ruoloController->getRuoloById($cv->ruolo);
           
            if($ruolo != null && $ruolo->getPubblicato() == 1){            
                return '<form action="'.curPageURL().'" method="POST" class="container-approva"><input type="hidden" name="idCV" value="'.$cv->ID.'"/><input type="submit" name="approva-cv" value="Approva"></form>';
            }
            else{
                return '<form action="'.curPageURL().'" method="POST" class="container-approva"><input type="hidden" name="idCV" value="'.$cv->ID.'"/><input type="submit" name="approva-cv" value="Approva" disabled></form>';
            }
           
        }
    }
    
    public function printUserCVs($cvs){
        if(count($cvs) > 0){
?>
        
        <p class="found-cv col-xs-12">Curriculum presenti: <?php echo count($cvs); ?></p>
        
        <div class="hidden-xs">
        <table class="table-cvs">
            <thead>
                <tr>                    
                    <td>Cognome</td>
                    <td>Nome</td>
                    <td>Email</td>                    
                    <td>Ruolo</td>
                    <td>Dove</td>
                    <td>CV</td>                    
                </tr>
            </thead>
            <tbody>
<?php
            foreach($cvs as $cv){
                
                $regione = $this->locatorController->getRegioneById($cv->regione);
                $provincia = $this->locatorController->getProvinciaById($cv->provincia);
                
                $temp_ruolo = $this->ruoloController->getRuoloById($cv->ruolo);
                $nomeRuolo = "";
                if($temp_ruolo != null){
                   $nomeRuolo .= getNome($temp_ruolo);
                }
                else{
                    $nomeRuolo .= 'non indicato';
                }
                
                $location = "";
                if($regione != null){
                    $location.= $regione->regione;
                    if($provincia != null){
                        $location.= ' - '.$provincia->provincia;
                    }
                }
                else{
                    $location.="ovunque";
                }
               
?>
                <tr>                   
                    <td><?php /* cognome */ echo $cv->cognome ?></td>
                    <td><?php /* nome */ echo $cv->nome ?></td>
                    <td><a href="mailto:<?php /* email */ echo $cv->email ?>"><?php /* email */ echo $cv->email ?></a></td>                   
                    <td><?php /* nome ruolo */ echo $nomeRuolo ?></td>
                    <td><?php /* nome regione e provincia */ echo $location ?></td>
                    <td><a target="_blank" href="<?php echo get_home_url().'/'.$cv->cv ?>">Visualizza il CV</a></td>                    
                </tr>
<?php
            }
?>
            </tbody>
        </table>
        </div>
        
        <div class="visible-xs col-xs-12">
<?php
        foreach($cvs as $cv){
            $regione = $this->locatorController->getRegioneById($cv->regione);
            $provincia = $this->locatorController->getProvinciaById($cv->provincia);

            $temp_ruolo = $this->ruoloController->getRuoloById($cv->ruolo);
            $nomeRuolo = "";
            if($temp_ruolo != null){
               $nomeRuolo .= getNome($temp_ruolo);
            }
            else{
                $nomeRuolo .= 'non indicato';
            }

            $location = "";
            if($regione != null){
                $location.= $regione->regione;
                if($provincia != null){
                    $location.= ' - '.$provincia->provincia;
                }
            }
            else{
                $location.="ovunque";
            }
?>
            <div class="col-xs-12 single-curriculum">
                <p class="col-xs-12"><span>Nome</span> <?php echo $cv->cognome ?> <?php echo $cv->nome ?></p>
                <p class="col-xs-12"><span>Mail</span> <a href="mailto:<?php /* email */ echo $cv->email ?>"><?php /* email */ echo $cv->email ?></a></p>
               
                <p class="col-xs-12"><span>Ruolo</span> <?php /* nome ruolo */ echo $nomeRuolo ?></p>
                <p class="col-xs-12"><span>Dove</span> <?php /* nome regione e provincia */ echo $location ?></p>
                
                <p class="url-cv col-xs-12"><a target="_blank" href="<?php echo get_home_url().'/'.$cv->cv ?>">Visualizza il CV</a></p>
                <div class="clear"></div>
            </div>
<?php
        }
?>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
<?php
        }
        else{
            echo 'Non ci sono curriculum da visualizzare';
        }
        
    }
    
    
    /**
     * La funzione stampa la tabella amministrativa dei CVs
     * @param type $cvs
     */
    public function printCVs($cvs){
        if(count($cvs) > 0){
?>
        Curriculum trovati: <?php echo count($cvs); ?>
        <table class="table-cvs">
            <thead>
                <tr>
                    <td>Data inserimento</td>
                    <td>Cognome</td>
                    <td>Nome</td>
                    <td>Email</td>
                    <td>Categoria</td>
                    <td>Ruolo</td>
                    <td>Dove</td>
                    <td>CV</td>
                    <td>Stato</td>
                    <td>Azioni</td>
                </tr>
            </thead>
            <tbody>
<?php
            foreach($cvs as $cv){
                
                $regione = $this->locatorController->getRegioneById($cv->regione);
                $provincia = $this->locatorController->getProvinciaById($cv->provincia);
                
                $temp_ruolo = $this->ruoloController->getRuoloById($cv->ruolo);
                $nomeRuolo = "";
                if($temp_ruolo != null){
                   $nomeRuolo .= getNome($temp_ruolo);
                }
                else{
                    $nomeRuolo .= '---';
                }
                
                $location = "";
                if($regione != null){
                    $location.= $regione->regione;
                    if($provincia != null){
                        $location.= ' - '.$provincia->provincia;
                    }
                }
                else{
                    $location.="ovunque";
                }
               
?>
                <tr>
                    <td><?php /* data */ echo getTime($cv->data_inserimento) ?></td>
                    <td><?php /* cognome */ echo $cv->cognome ?></td>
                    <td><?php /* nome */ echo $cv->nome ?></td>
                    <td><?php /* email */ echo $cv->email ?></td>
                    <td><?php /* nome categoria */ echo getNomeCategoriaById($cv->categoria) ?></td>
                    <td><div style="display:none" class="select-ruolo" id="col-ruolo-<?php echo $cv->ID ?>"></div><div id="col-nome-ruolo-<?php echo $cv->ID ?>" class="nome-ruolo"><?php /* nome ruolo */ echo $nomeRuolo ?></div></td>
                    <td><?php /* nome regione e provincia */ echo $location ?></td>
                    <td><a target="_blank" href="<?php echo get_home_url().'/'.$cv->cv ?>">Apri il CV</a></td>
                    <td><?php echo $this->printStatoCV($cv) ?></td>
                    <td>
                        <form action="<?php echo curPageURL() ?>" name="modifica-cv" class="modifica-cv" method="POST">
                            <input type="hidden" name="idCV" value="<?php echo $cv->ID ?>" /> 
                            <input type="hidden" name="idCat" value="<?php echo $cv->categoria ?>" />
                            <input type="hidden" name="idRuolo" class="idRuolo" data-cv="<?php echo $cv->ID ?>" value="<?php echo $cv->ruolo ?>" />
                            <input type="button" name="modifica-cv" value="Modifica" />
                            <input style="display:none" type="submit" name="aggiorna-cv" value="Aggiorna">
                            <input type="submit" name="elimina-cv" value="Elimina">
                        </form>
                    </td>
                </tr>
<?php
            }
?>
            </tbody>
        </table>        
       
<?php
        }
        else{
            echo 'Non ci sono curriculum per questa voce';
        }
    }
    
    
    public function listnerAdminFormCV(){
?>
      <script type="text/javascript">
          jQuery(document).ready(function($){              
              $('.modifica-cv input[name=modifica-cv]').click(function(){
                //attivo l'ultimo che ho cliccato
                //ottengo tutti gli elementi
                $('.modifica-cv input[name=modifica-cv]').show();
                $('.modifica-cv input[name=aggiorna-cv]').hide();
                $('.select-ruolo').hide();
                $('.nome-ruolo').show();
                //ottengo gli id (cv e categoria)
                var idCv = $(this).siblings('input[name=idCV]').val();
                var idCat = $(this).siblings('input[name=idCat]').val();
                var idRuolo = $(this).siblings('.idRuolo').val();
                //devo sostituire il bottone modifica con aggiorna
                $(this).siblings('input[name=aggiorna-cv]').show();
                $(this).hide();
                //chiamata ajax per ottenere la lista dei ruoli per l'id categoria passato
                jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, function(data){printRuoli2(idCv, idRuolo, data);}, 'json');
                //ascoltatore sulla select del ruolo
                jQuery(document).on('change', 'select[name=aggiorna-ruolo]',function(){                    
                    var idNewRuolo = jQuery('select[name=aggiorna-ruolo]').val();      
                    //devo cambiare il valore nell'input hidden associato
                    $("input[data-cv='"+idCv+"'").val(idNewRuolo);                    
                });
                
                
              });
              
              function printRuoli2(idCv, idRuolo, data){
                jQuery(function(){
                    //pulisco
                    jQuery('#col-ruolo-'+idCv).html("");
                    
                    var html = '';
                    if(data.length > 0){
                        html += '<select id="ruolo-'+idCv+'" name="aggiorna-ruolo">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            if(data[i].id === idRuolo){
                                html += '<option value="'+data[i].id+'" selected>'+data[i].nome+'</option>';
                            }
                            else{
                                html += '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                            }                                   
                        }
                        html+= '</select>';
                    }
                    else{
                        html += '<div class="no-result"><?php echo $this->languageController->getTranslation('no-role') ?></div>';
                    }
                    
                    jQuery('#col-ruolo-'+idCv).append(html);
                    jQuery('#col-ruolo-'+idCv).show();
                    jQuery('#col-nome-ruolo-'+idCv).hide();
                });
            }
              
          });
      </script>  
<?php
    }
    
    /**
     * La funzione stampa la tabella dei ruoli a seconda dei ruoli forniti
     * 
     * @param type $ruoli
     */
    public function printRuoli($ruoli, $title){
        //considero ruoli un array di ruoli
        if(count($ruoli) > 0){
?>      
        <div class="container-table-ruoli">
            <h3><?php echo $title ?></h3>
        Ruoli trovati: <?php echo count($ruoli); ?>
        <table class="table-ruoli">
            <thead>
                <tr>
                    <td>Ruolo</td>
                    <td>Categoria</td>
                    <td>Stato</td>
                    <td>Azione</td>
                </tr>                
            </thead>
            <tbody>
<?php
            foreach($ruoli as $ruolo){
                             
?>
                <tr>
                    <td><input type="text" name="nome-ruolo-<?php echo $ruolo->ID ?>" value="<?php echo $ruolo->nome ?>" disabled/></td>
                    <td><?php echo getNomeCategoriaById($ruolo->categoria) ?></td>
                    <td><?php echo $this->printStatoRuolo($ruolo) ?></td>
                    <td>
                        <form action="<?php echo curPageURL() ?>" method="POST"  class="azione-ruolo">
                            <input type="hidden" name="idRuolo" value="<?php echo $ruolo->ID ?>" />
                            <input type="hidden" name="tempNomeRuolo" value="<?php echo $ruolo->nome ?>" />
                            <input type="button" name="modifica-ruolo" value="Modifica">
                            <input style="display:none" type="submit" name="aggiorna-ruolo" value="Aggiorna">
                            <input type="submit" name="elimina-ruolo" value="Elimina">
                        </form>
                    </td>
                </tr>
<?php
                
            }
?>
            </tbody>            
        </table>
        </div>
<?php    
        }
        else{
            
        }
    }     
    
    public function listenerFormRuoli(){
?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.azione-ruolo input[name=modifica-ruolo]').click(function(){
                   var idRuolo = $(this).siblings('input[name=idRuolo]').val();                   
                   $(this).siblings('input[name=aggiorna-ruolo]').show();
                   $(this).hide();
                   $('input[name=nome-ruolo-'+idRuolo+']').removeAttr('disabled');
                });
                
                
                $('input[name=aggiorna-ruolo]').click(function(){
                    var idRuolo = $(this).siblings('input[name=idRuolo]').val(); 
                    var nome = $('input[name=nome-ruolo-'+idRuolo+']').val();
                    $(this).siblings('input[name=tempNomeRuolo]').val(nome);                 
                    
                });
            });
        </script>
<?php        
    }
    
    public function printCVsNonPubblicati(){
        $this->printCVs($this->cvController->getCVsNonPubblicati());
    }
    
    public function printUltimiCVsPubblicati(){
        $this->printCVs($this->cvController->getUltimiCVsPubblicati());
    }
    
    public function printRuoliNonPubblicati($title){
        $this->printRuoli($this->ruoloController->getRuoliNonPubblicati(), $title);
    }
    
    public function printUltimiRuoliApprovati($title){
        $this->printRuoli($this->ruoloController->getUltimiRuoliApprovati(), $title);
    }
    
    public function listenerSearchRuoli(){
        //su questa funzione è posto l'ascoltatore della ricerca
        if(isset($_POST['ricerca-ruolo'])){         
            
            
            $fields = array();
                        
            if(isset($_POST['nome-ruolo'])){
                $fields['nome'] = strip_tags(trim($_POST['nome-ruolo']));
            }
            if(isset($_POST['ricerca-categoria'])){
                $fields['categoria'] = $_POST['ricerca-categoria'];
            }
            if(isset($_POST['ricerca-stato'])){
                $fields['pubblicato'] = $_POST['ricerca-stato'];
            }
            
            $this->printRuoli($this->ruoloController->searchRuoli($fields),'');
        }
    }
    
    public function listenerSearchCV($role){
        //qua risiede l'ascoltatore della ricerca dei CV
        if(isset($_POST['ricerca-cv'])){
                        
            $param = array();
            
            if(isset($_POST['ricerca-nome']) && trim($_POST['ricerca-nome']) != '' ){
                $param['nome'] = trim($_POST['ricerca-nome']);
            }
            if(isset($_POST['ricerca-cognome']) && trim($_POST['ricerca-cognome']) != '' ){
                $param['cognome'] = trim($_POST['ricerca-cognome']);
            }
            if(isset($_POST['ricerca-email']) && trim($_POST['ricerca-email']) != '' ){
                $param['email'] = trim($_POST['ricerca-email']);
            }
            if(isset($_POST['ricerca-categoria']) && trim($_POST['ricerca-categoria']) != '' ){
                $param['categoria'] = trim($_POST['ricerca-categoria']);
            }
            if(isset($_POST['ruolo']) && trim($_POST['ruolo']) != '' ){
                $param['ruolo'] = trim($_POST['ruolo']);
            }
            if(isset($_POST['ricerca-categoria']) && trim($_POST['ricerca-categoria']) != '' ){
                $param['categoria'] = trim($_POST['ricerca-categoria']);
            }
            if(isset($_POST['regione']) && trim($_POST['regione']) != '' ){
                $param['regione'] = trim($_POST['regione']);
            }
            if(isset($_POST['provincia']) && trim($_POST['provincia']) != '' ){
                $param['provincia'] = trim($_POST['provincia']);
            }
            if(isset($_POST['ricerca-stato']) && trim($_POST['ricerca-stato']) != '' ){
                $param['pubblicato'] = trim($_POST['ricerca-stato']);
            }
            
            $param['ordine'] = 'id';
            
            //stampo il risultato
            if($role == 'admin'){
                $this->printCVs($this->cvController->getCVsByParameters($param));
            }
            else{
                $param['ordine'] = 'provincia, ruolo, id';
            ?>    
                <div class="container-results">
                        <div class="container-1024">
                            <?php $this->printUserCVs($this->cvController->getCVsByParameters($param)); ?>
                        </div>
                    </div>
            <?php
                
            }
            
        }
    }
    
    public function printUserSearchBox($categoria){
    ?>
        <div class="container-search">
        <div class="container-1024">
        <h3 class="green col-xs-12"><?php echo $this->languageController->getTranslation('search') ?></h3>
        <div id="ricerca-cv">
           
            <form name="form-ricerca-cv" action="<?php echo curPageURL() ?>#risultati-cv" method="POST">                
               
                <?php echo $this->printRegioniProvince('') ?>        
                
                     
                    <div id="contenitore-selettore-ruoli" class="field col-xs-12 col-sm-6">
                        <input type="hidden" name="ricerca-categoria" value="<?php echo $categoria ?>" />
                        <label for="ruolo"><?php echo $this->languageController->getTranslation('role') ?></label>
                        <select id="ruolo" name="ruolo">
                              <option value=""></option>
                    <?php 
                        //ottengo i ruoli pubblicati non da chiamata ajax
                        $ruoli = $this->ruoloController->getRuoliByCategory($categoria, 1);
                        if(count($ruoli > 0)){                   
                            foreach($ruoli as $ruolo){
                    ?>
                                <option value="<?php echo $ruolo['id'] ?>"><?php echo $ruolo['nome'] ?></option>                            
                    <?php
                            }                   
                        }
                        else {
                            //non ci sono ruoli pubblicati                   
                        }
                    ?>
                             </select>
                    </div>
                          
                <div class="clear"></div>
                <div class="ricerca field">
                    <input type="submit" value="<?php echo $this->languageController->getTranslation('search') ?>" name="ricerca-cv" />
                    <!--<img src="<?php echo plugins_url() ?>/gestione_cv/images/logo-transparent.png" class="logo-image" style="max-height: 52px;" />-->
                </div>
                <div class="clear"></div>
            </form>

            <?php echo $this->printAjaxCallRegioniProvince() ?>           

        </div>
        </div>
            </div>
        <div id="risultati-cv">
           <?php echo $this->listenerSearchCV('user') ?> 
        </div>
        
    <?php    
    }
    
}
