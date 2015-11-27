<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author Alex
 */




class LanguageController {

    private $lang;
    private $translation;
    
    function __construct() {
        $this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        
        switch($this->lang){
            case 'it':
                $this->translation = getLanIT();
                break;
            case 'en':
                $this->translation = getLanEN();
                break;
            default:
                $this->translation = getLanEN();                   
                break;                   
        }     
       
    }
    
    function getLang() {
        return $this->lang;
    }

    function setLang($lang) {
        $this->lang = $lang;
    }

    function getTranslation($code){         
        return $this->translation[$code];
    }
        
}

function getLanIT(){
   
  $lan = array();
  $lan['sendCvTitle'] = 'Inviaci il tuo curriculum!';
  $lan['name'] = 'Nome';
  $lan['surname'] = 'Cognome';
  $lan['email'] = 'Email';
  $lan['category'] = 'Categoria Occupazionale';
  $lan['add-role'] = 'Aggiungi il tuo ruolo';
  $lan['occupation'] = 'Dove stai cercando occupazione?';
  $lan['upload-cv'] = 'Carica il tuo Curriculum';
  $lan['accept-privacy'] = 'Accetto la <a target="_blank" href="http://www.theworknote.com/privacy/">normativa sulla privacy</a>';
  $lan['region'] = 'Regione';
  $lan['province'] = "Provincia";
  $lan['send-cv'] = 'Invia Curriculum';
  $lan['select-role'] = 'Seleziona Ruolo';
  $lan['no-role'] = 'Nessun ruolo presente.';
  $lan['search'] = 'Ricerca';
  $lan['role'] = 'Ruolo';
  //confirmation-email
  $lan['email-save-title-user'] = 'The Work Note - Registrazione Curriculum Vitae';
  $lan['email-save-title-admin'] = 'Nuovo Curriculum caricato';
  $lan['msg-save-email-user'] = '<h3>Curriculum correttamente registrato</h3>Gentile utente il suo curriculum è stato correttamente registrato all\'interno del nostro sistema. <br><br> Cordiali Saluti<br>The Work Note';
  $lan['msg-save-email-admin'] = 'Un nuovo curriculum è stato caricato nel sistema';
  $lan['msg-save-email-admin-cv-to-approve'] = 'Un nuovo curriculum è stato caricato nel sistema ed ha bisogno di essere approvato';
  //update email
  $lan['msg-approved-email-title'] = 'Curriculum approvato!';
  $lan['msg-approved-email-msg'] = 'Il tuo curriculum è stato approvato ed ora è visibile alle aziende registrate nel sito.<br><br>Cordiali Saluti<br>The Work Note';
  //testo aggiunto pre-form caricamento cv
  $lan['pre-form-upload-title'] = 'Benvenuto nella sezione "The WorkNote CV richiedere lavoro"';
  $lan['pre-form-upload-text'] = 'Pubblicare gratis su internet il tuo curriculum vitae non è mai stato così facile come su The WorkNote tramite una pagina unica. Se sei già in possesso di uno compilato ed aggiornato puoi semplicemente caricarlo ed esser visionato dalle aziende iscritte a The WorkNote che ricercheranno profili professionali con le tue caratteristiche.';
  //testo post-form
  $lan['post-form-link-cv'] = 'Qui il link per il DOWNLOAD del curriculum vitaee fomato europeo da compilare';
  $lan['post-form-text'] = '<p>Un buon curriculum deve essere:</p> <ul><li>chiaro e curato nell’impaginazione;</li><li>scritto ordinatamente;</li><li>evidenziazione di paragrafi staccati e frasi sottolineate in grassetto;</li><li>preciso nelle informazioni che vengono fornite (periodi di lavoro, nomi aziende, titoli formativi);</li><li>concreto negli eventuali risultati raggiunti;</li><li>corretto, senza errori ortografici;<li>facile da di stile succinto ma non stringato;</li><li>deve invogliare la lettura, scorrevole, diretto, con uso della prima persona;</li><li>personalizzato a seconda dell’azienda/ente a cui ci si rivolge.</li></ul><p>Nota Bene: Il CV va aggiornato periodicamente.</p>';
  //scopri cv 
  $lan['find-cv-title'] = 'Benvenuto nella sezione "The WorkNote CV offrire lavoro"';
  $lan['find-cv-text'] = 'Benvenuto gentile cliente nella sezione di The WorkNote che offre gratuitamente una ricerca personalizzabile di profili professionali per ogni settore ed esigenza lavorativa. In questa pagina si troveranno tramite ricerca dettagliata tutti i CV formato europeo caricati da chiunque cerchi un lavoro o voglia cambiare l’attuale che potranno essere visualizzati online o scaricati.';
  $lan['how-it-works-title'] = 'Come funziona:';
  $lan['how-it-works-text'] = 'Il database filtra i Curriculum Vitae e le mostrerà solo quelli appartenenti alla Sua categoria commerciale, a lei solo il compito di filtrare per regione, provincia e/o ruolo.<br>Facciamo un esempio:<br><br>io sono un ristorante quindi iscritto alla categoria “Alberghi,BAR,Ristorazione” se entro in questa sezione e clicco su ricerca (senza impostare filtri) il database mi mostrerà i curriculum di persone provenienti da tutta Italia ma che, quando hanno inserito il CV, hanno messo come categoria commerciale “Alberghi,BAR,Ristorazione”.';
  //other messages
  $lan['no-curriculum-to-show'] = 'Non ci sono curriculum da visualizzare';
  $lan['no-identification-province'] = 'Il sistema non è riuscito ad identificare la tua provincia di appartenenza.<br>Ti chiediamo di aggiornarla mediante i nostri suggerimenti.';
  $lan['select-region-province'] = 'Scegli regione e provincia.';
  $lan['update-province'] = 'Aggiorna provincia';
  $lan['profile-not-complete'] = 'Il tuo profilo non è completo. Per usufruire di questa funzionalità ti chiediamo di completare la profilazione aggiungendo l\'indirizzo della tua attività.<br>';
  $lan['complete-address'] = 'Completa indirizzo';
  $lan['user-not-registered'] = 'Utente non registrato!';
  
  return $lan; 
}

function getLanEN(){
   
  $lan = array();
  $lan['sendCvTitle'] = 'Send us your Curriculum!';
  $lan['name'] = 'Name';
  $lan['surname'] = 'Surname';
  $lan['email'] = 'Email';
  $lan['category'] = 'Occupational Category';
  $lan['add-role'] = 'Add your role';
  $lan['occupation'] = 'Where are you looking for employment?';
  $lan['upload-cv'] = 'Upload your Curriculum';
  $lan['accept-privacy'] = 'I accept <a target="_blank" href="http://www.theworknote.com/privacy/">the Privacy Policy</a>';
  $lan['region'] = 'Region';
  $lan['province'] = "Province";
  $lan['send-cv'] = 'Send your Curriculum';
  $lan['select-role'] = 'Select Role';
  $lan['no-role'] = 'No role found.';
  $lan['search'] = 'Search';
  $lan['role'] = 'Role';
   //confirmation-email
  $lan['email-save-title-user'] = 'The Work Note - Registration Curriculum Vitae';
  $lan['email-save-title-admin'] = 'New Curriculum uploaded';
  $lan['msg-save-email-user'] = '<h3>Curriculum properly registered</h3>Dear User his curriculum was correctly registered in our system.<br><br>Best Regards<br>The Work Note';
  $lan['msg-save-email-admin'] = 'A new curriculum has been loaded into the system';
  $lan['msg-save-email-admin-cv-to-approve'] = 'A new curriculum has been loaded into the system and needs to be approved';
  //update email
  $lan['msg-approved-email-title'] = 'Curriculum approved!';
  $lan['msg-approved-email-msg'] = 'Your curriculum has been approved and is now visible to the companies registered in the site.<br><br>Best Regards<br>The Work Note';
  //testo aggiunto pre-form caricamento cv
  $lan['pre-form-upload-title'] = 'Welcome to the "The WorkNote CV require work"';
  $lan['pre-form-upload-text'] = 'Post your CV on web for free has never been as easy as on The WorkNote. If you already have one compiled and updated you can simply load it and be viewed by the companies attending will be looking on The WorkNote The professional profiles with your characteristics.';
  //testo post-form
  $lan['post-form-link-cv'] = 'Here the DOWNLOAD for the empty european CV';
  $lan['post-form-text'] = '<p>A good curriculum should be:</p> <ul><li>layout clear and edited;</li><li>neatly written;</li><li>highlighting detached paragraphs;</li><li>precise in the information provided (periods of work, company names, titles, training);</li><li>include results achieved;</li><li>correct, without spelling errors;<li>easy, succinct style but not too concise;</li><li>encourage reading, sliding, direct, with use of the first person;</li><li>customized to the business.</li></ul><p>Note: The CV must be regularly updated.</p>';
  //scopri cv 
  $lan['find-cv-title'] = 'Welcome to the "The WorkNote CV offer work"';
  $lan['find-cv-text'] = 'Welcome dear customer in the WorkNote section that offers guests a free customizable search of job profiles for each field and business requirement.On this page you will find all the European CV format uploaded by anyone looking for a job or want to change the actual and it can be viewed online or downloaded.';
  $lan['how-it-works-title'] = 'How does it work:';
  $lan['how-it-works-text'] = 'The database splits the CV and show only those belonging at your business category, you only have to split by region, province and / or role.';
  //other messages
  $lan['no-curriculum-to-show'] = 'No curriculum to display';
  $lan['no-identification-province'] = 'The system failed to identify your province. <br> We ask that you update it using our suggestions.';
  $lan['select-region-province'] = 'Select region and province.';
  $lan['update-province'] = 'Update province';
  $lan['profile-not-complete'] = 'Your profile is not complete. To take advantage of this feature we ask that you complete the profiling by adding the address of your business.<br>';
  $lan['complete-address'] = 'Complete address';
  $lan['user-not-registered'] = 'Unregistered user!';
  return $lan; 
}
