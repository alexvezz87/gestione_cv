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
    
    public function __construct() {
        $this->DAO = new CvDAO();
    }
    
    /**
     * La funzione salva un cv nel database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function saveCV(CV $cv){
        if($this->DAO->saveCV($cv) == true){
            return true;
        }
        return false;
    }
    
    /**
     * La funzione elimina un determinato cv dal database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function deleteCV(CV $cv){
        //per eliminare un cv, prima devo ottenere il suo ID
        $idCV = $this->DAO->getCvID($cv);
        if($idCV != null && $idCV != false){
            if($this->DAO->deleteCV($idCv)== true){
                return true;
            }            
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
        return $this->getCVsArray($result);
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
                //salvo il cv nell'array di cvs
                array_push($cvs, $cv);
            }
            
            return $cvs;
        }
        return false;
    }
    
    /**
     * La funzione dato un determinato cv, lo aggiorna a database
     * 
     * @param CV $cv
     * @return boolean
     */
    public function updateCV(CV $cv){
        //per aggiornare un cv, devo prima ottenere il suo id
        $idCV = $this->DAO->getCvID($cv);
        if($idCV != null && $idCV != false){
            if($this->DAO->updateCV($cv, $idCV) == true){
                return true;
            }
        }
        return false;
    }
}

