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
class Ruolo {
   
    private $nome;
    private $categoria; 
    private $pubblicato;
    
    //definisco il costruttore 
    public function __construct(){
        //costruttore vuoto
    }
    
    //definisco i metodi
    function getNome() {
        return $this->nome;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getPubblicato() {
        return $this->pubblicato;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setPubblicato($pubblicato) {
        $this->pubblicato = $pubblicato;
    }


    
}
