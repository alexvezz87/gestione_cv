<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//require_once '../DAO/class.RuoloDAO.php';

/**
 * Description of class
 *
 * @author Alex
 */
class RuoloContoller {
    
    private $DAO;
    
    public function __construct() {
        $this->DAO = new RuoloDAO();
    }
    
    /**
     * La funzione passata una categoria, restituisce tutti i ruoli associati a quella categoria
     * 
     * @param type $categoria
     * @return boolean|array
     */
    public function getRuoliByCategory($categoria, $pubblicato){
        
        //interrogo il db
        $result = null;
        if($pubblicato == null){
            //ricerco tutti i ruoli
            $result = $this->DAO->getRuoliByCategoria($categoria);
        }
        else if($pubblicato == 1){
            //ricerco solo i ruoli pubblicati
            $result = $this->DAO->getRuoliPubblicatiByCategoria($categoria);
        }
        else{
            //ricerco solo i ruoli non pubblicati
            $result = $this->DAO->getRuoliNonPubblicatiByCategoria($categoria);
        }
        if(count($result) > 0){
                $ruoli = array();
                
                //ciclo da verificare
                foreach ($result as $value) {
                    //creo una nuova istanza di ruolo
                    
                    $temp = array();
                    $temp['id'] = $value->ID;
                    $temp['nome'] = $value->nome;
                    $temp['categoria'] = $value->categoria;
                    $temp['pubblicato'] = $value->pubblicato;
                    
//                    $ruolo = new Ruolo();
//                    $ruolo->setNome($value->nome);
//                    $ruolo->setCategoria($value->categoria);
//                    $ruolo->setPubblicato($value->pubblicato);
                    //salvo il ruolo in un array di ruoli
                    array_push($ruoli, $temp);
                }
                
                return $ruoli;
            }            
            return false;
    }
    
    /**
     * 
     * La funzione salva un ruolo nel database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function saveRuolo(Ruolo $ruolo){
       
        if($this->DAO->isRuoloAlreadyInDB($ruolo) == false){
            return $this->DAO->salvaRuolo($ruolo);   
        }
        
        return $this->DAO->getIDRuolo($ruolo);      
    }
    
    /**
     * Funzione usata dagli amministratori per salvare un ruolo nel database
     * serve per differenziarsi da quella usata dagli utenti nel caso il ruolo esista già
     * in quel caso viene ritornato false
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function saveRuoloAdmin(Ruolo $ruolo){
        
        if($this->DAO->isRuoloAlreadyInDB($ruolo) == false){
            return $this->DAO->salvaRuolo($ruolo);   
        }
        //il ruolo esiste già
        return false;      
    }

    /**
     * La funzione elimina un determinato ruolo dal database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function deleteRuolo($idRuolo){
        //per eliminare un rulo prima ottengo il suo id        
        
        if($this->DAO->deleteRuolo($idRuolo) == true){
            return true;
        }
        return false;
      
    }
    
    /**
     * La funzione passato un ruolo restituisce la sua istanza presente nel db oppure false se non esiste
     * 
     * @param Ruolo $ruolo
     * @return boolean|\Ruolo
     */
    public function getRuolo(Ruolo $ruolo){
        $tempRuolo = $this->DAO->getRuolo($ruolo);
        if($tempRuolo != false){
            $ruoloNew = new Ruolo();
            $ruoloNew->setCategoria($tempRuolo->categoria);
            $ruoloNew->setNome($tempRuolo->nome);
            $ruoloNew->setPubblicato($tempRuolo->pubblicato);
            
            return $ruoloNew;
        }
        return false;
    }
  
    
    /**
     * La funzione dato un determinato ruolo lo aggiorna a database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function updateRuolo(Ruolo $ruolo, $idRuolo){
        if($this->DAO->updateRuolo($ruolo, $idRuolo) == true){
            return true;
        }
    } 
    
    
    public function getRuoloById($idRuolo){
        $temp = $this->DAO->getRuoloByID($idRuolo);
        if($temp != null){
            $ruolo = new Ruolo();
            $ruolo->setCategoria($temp->categoria);
            $ruolo->setPubblicato($temp->pubblicato);
            $ruolo->setNome($temp->nome);
            return $ruolo;
        }
        return null;
    }
    
    public function isRuoloPubblicato($idRuolo){        
            
        $ruolo = $this->DAO->getRuoloByID($idRuolo);               

        if($ruolo->pubblicato == 1){
            //ruolo pubblicato
            return true;
        }
        else{
            //ruolo non pubblicato
            return false;
        }
    }
    
    public function getRuoliNonPubblicati(){
        return $this->DAO->getRuoliNonPubblicati();
    }
    
    public function getUltimiRuoliApprovati(){
        return $this->DAO->getUltimiRuoliApprovati();
    }
    
}

?>