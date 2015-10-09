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
  //confirmation-email
  $lan['email-save-title-user'] = 'The Work Note - Registrazione Curriculum Vitae';
  $lan['email-save-title-admin'] = 'Nuovo Curriculum caricato';
  $lan['msg-save-email-user'] = '<h3>Curriculum correttamente registrato</h3>Gentile utente il suo curriculum è stato correttamente registrato all\'interno del nostro sistema. <br><br> Cordiali Saluti<br>The Work Note';
  $lan['msg-save-email-admin'] = 'Un nuovo curriculum è stato caricato nel sistema';
  $lan['msg-save-email-admin-cv-to-approve'] = 'Un nuovo curriculum è stato caricato nel sistema ed ha bisogno di essere approvato';
  
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
   //confirmation-email
  $lan['email-save-title-user'] = 'The Work Note - Registration Curriculum Vitae';
  $lan['email-save-title-admin'] = 'New Curriculum uploaded';
  $lan['msg-save-email-user'] = '<h3>Curriculum properly registered</h3>Dear User his curriculum was correctly registered in our system.<br><br>Best Regards<br>The Work Note';
  $lan['msg-save-email-admin'] = 'A new curriculum has been loaded into the system';
  $lan['msg-save-email-admin-cv-to-approve'] = 'A new curriculum has been loaded into the system and needs to be approved';
  
  return $lan; 
}
