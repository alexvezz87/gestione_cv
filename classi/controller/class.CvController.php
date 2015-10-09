<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once '../DAO/class.CvDAO.php';
/**
 * Description of class
 *
 * @author Alex
 */
class CvController {
    private $DAO;
    private $language;
    
    public function __construct() {
        $this->DAO = new CvDAO();
        $this->language = new LanguageController();
    }
    
    /**
     * La funzione salva un cv nel database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function saveCV(CV $cv){
        
        if($this->DAO->isCvAlreadyInDB($cv) == false){
            //se il cv non è presente, lo salvo
            if($this->DAO->saveCV($cv) == true){
                //invio le mail
                $this->sendConfirmEmail('user', $cv);
                $this->sendConfirmEmail('admin', $cv);
                return 1;
            }
        }
        else{
            //altrimenti faccio l'update
            $idCV = $this->DAO->getCvID($cv);
            if($this->DAO->updateCV($cv, $idCV)){
                //invio le mail
                $this->sendConfirmEmail('user', $cv);
                $this->sendConfirmEmail('admin', $cv);
                return 2;
            }
        }
        return false;
    }
    
    /**
     * La funzione elimina un determinato cv dal database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function deleteCV($idCV){
        //per eliminare un cv, prima devo ottenere il suo ID
               
        if($this->DAO->deleteCV($idCV)== true){
            return true;
        }            
       
        return false;
        
    }
    
    public function getCVsByCategory($categoria){
        $param['categoria'] = $categoria;
        $result = $this->DAO->getCVsByParameters($param);       
        return $this->getCVsArray($result); 
    }
    
    public function getCVsByParameters($param){
        $result = $this->DAO->getCVsByParameters($param);
        return $result;
    }
    
    /**
     * Funzione interna che dato in ingresso un oggetto query, restituisce un array di cv
     * 
     * @param type $result
     * @return boolean|array
     */
    function getCVsArray($result){
         if(count($result) > 0){
            $cvs = array();
            //ciclo da verificare
            foreach($result as $value){
                //creo una nuova istanza di CV                
                $cv = $this->fromQueryResultToCv($value); 
                //salvo il cv nell'array di cvs
                array_push($cvs, $cv);
            }
            
            return $cvs;
        }
        return false;
    }
    
    function fromQueryResultToCv($value){
       // print_r($value);
        $cv = new CV();
        $cv->setCategoria($value->categoria);
        $cv->setCognome($value->cognome);
        $cv->setCv($value->cv);
        $cv->setDataInserimento($value->data_inserimento);
        $cv->setEmail($value->email);                
        $cv->setNome($value->nome);
        $cv->setProvincia($value->provincia);
        $cv->setPubblicato($value->pubblicato);
        $cv->setRegione($value->regione);
        $cv->setRuolo($value->ruolo);
        return $cv;
    }
    
    /**
     * La funzione dato un determinato cv, lo aggiorna a database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function updateCV(CV $cv, $idCV){
        //ottengo il ruolo dall'ID
        $temp = new CV();
        $temp = $this->fromQueryResultToCv($this->DAO->getCvById($idCV));       
        //dal cv si può modificare solo lo stato di pubblicato        
        $temp->setPubblicato($cv->getPubblicato());
        
        if($this->DAO->updateCV($temp, $idCV) == true){
            return true;
        }
        return false;
    }
    
    public function getCVsNonPubblicati(){
        return $this->DAO->getCVsNonPubblicati();
    }
    
    public function getUltimiCVsPubblicati(){
        return $this->DAO->getUltimiCvPubblicati();
    }
    
    public function getCvById($idCV){
        $temp = $this->DAO->getCvById($idCV);
        return $this->fromQueryResultToCv($temp);
    }
    
    /**
     * Funzione che invia una mail di conferma
     */
    function sendConfirmEmail($type, CV $cv){
        //type identifica il tipo di mail da spedire
        //type == 'user' --> Invia una mail di conferma all'utente
        //type == 'admin' --> invia una mail di notifica all'amministratore
        
        $msg = '';
        $title = '';
        $email = '';
        
        switch($type){
            case 'user':                
                $email = $cv->getEmail();
                $title = $this->language->getTranslation('email-save-title-user');
                $msg = $this->language->getTranslation('msg-save-email-user');
                
                break;
            
            case 'admin':
                $email = get_option('admin_email');
                $title = $this->language->getTranslation('email-save-title-admin').' - '.$cv->getCognome().' '.$cv->getNome();
                if($cv->getPubblicato() == 0){
                    $msg = $this->language->getTranslation('msg-save-email-admin-cv-to-approve');
                }
                else{
                    $msg = $this->language->getTranslation('msg-save-email-admin');
                }                
                break;
        }
        try{
            //aggiungo il filtro per l'html sulla mail
            add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
            //invio la mail
            wp_mail($email, $title, $msg);
            return true;
        }
        catch(Exception $ex){
            _e($ex);
            return false;
        }
    }
}


