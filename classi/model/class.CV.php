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
class CV {
    private $dataInserimento;
    private $nome;
    private $cognome;
    private $email;
    private $categoria;
    private $ruolo;
    private $regione;
    private $provincia;
    private $cv;
    private $pubblicato;
    
    //costruttore
    function __construct() {
        //costruttore vuoto
    }
    
    //metodi
    function getDataInserimento() {
        return $this->dataInserimento;
    }

    function getNome() {
        return $this->nome;
    }

    function getCognome() {
        return $this->cognome;
    }

    function getEmail() {
        return $this->email;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getRuolo() {
        return $this->ruolo;
    }

    function getRegione() {
        return $this->regione;
    }

    function getProvincia() {
        return $this->provincia;
    }

    function getCv() {
        return $this->cv;
    }

    function getPubblicato() {
        return $this->pubblicato;
    }

    function setDataInserimento($dataInserimento) {
        $this->dataInserimento = $dataInserimento;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setRuolo($ruolo) {
        $this->ruolo = $ruolo;
    }

    function setRegione($regione) {
        $this->regione = $regione;
    }

    function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    function setCv($cv) {
        $this->cv = $cv;
    }

    function setPubblicato($pubblicato) {
        $this->pubblicato = $pubblicato;
    }

}

?>