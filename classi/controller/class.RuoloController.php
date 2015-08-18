<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once '../DAO/class.RuoloDao.php';

/**
 * Description of class
 *
 * @author Alex
 */
class RuoloContoller {
    
    private $DAO;
    
    function __construct() {
        $this->DAO = new RuoloDAO();
    }
    
    /**
     * La funzione passata una categoria, restituisce tutti i ruoli associati a quella categoria
     * 
     * @param type $categoria
     * @return boolean|array
     */
    public function getRuoliByCategoria($categoria){
        
        //interrogo il db
        $result = $this->DAO->getRuoliByCategoria($categoria);
        
        if(count($result) > 0){
                $ruoli = array();
                
                //ciclo da verificare
                foreach ($result as $value) {
                    //creo una nuova istanza di ruolo
                    $ruolo = new Ruolo();
                    $ruolo->setNome($value->nome);
                    $ruolo->setCategoria($value->categoria);
                    $ruolo->setPubblicato($value->pubblicato);
                    //salvo il ruolo in un array di ruoli
                    array_push($ruoli, $ruolo);
                }
                
                return $ruoli;
            }
            else{
                return false;
            }        
    }
    
    /**
     * 
     * La funzione salva un ruolo nel database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function salvaRuolo(Ruolo $ruolo){
        if($this->DAO->salvaRuolo($ruolo) == true){
            return true;
        }
        return false;
    }

    /**
     * La funzione elimina un determinato ruolo dal database
     * 
     * @param Ruolo $ruolo
     * @return boolean
     */
    public function cancellaRuolo(Ruolo $ruolo){
        if($this->DAO->deleteRuolo($ruolo) == true){
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
    
    
}
