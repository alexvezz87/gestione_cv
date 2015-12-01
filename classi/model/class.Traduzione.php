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
class Traduzione {
    private $cod;
    private $lan;
    private $text;
    
    
    function __construct() {
        //costruttore vuoto
    }

    
    //metodi
    function getCod() {
        return $this->cod;
    }

    function getLan() {
        return $this->lan;
    }

    function getText() {
        return $this->text;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setLan($lang) {
        $this->lan = $lang;
    }

    function setText($text) {
        $this->text = $text;
    }


    
}
