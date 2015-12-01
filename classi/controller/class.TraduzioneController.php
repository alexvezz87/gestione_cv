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
class TraduzioneController {

    private $DAO;
    
    public function __construct() {
        $this->DAO = new TraduzioneDAO();
    }
    
    public function salvaTraduzione(Traduzione $traduzione){        
        //controllo se la traduzione esiste        
        if($this->DAO->isTraduzioneAlreadyInDB($traduzione) == true){
            //la traduzione c'è --> aggiorno
            return $this->DAO->updateTraduzione($traduzione);
        }
        else{
            //la traduzione non c'è --> salvo
            return $this->DAO->salvaTraduzione($traduzione);
        } 
    }
    
    public function getTraduzione($cod){
       
       $traduzione = $this->DAO->getTraduzione($cod);
       
       if($traduzione != null){           
           return stripslashes($traduzione->text);
       }       
       return null;
    }
    
}
