<?php
    
    //Autore: Alex Vezzelli - Alex Soluzioni Web
    //url: http://www.alexsoluzioniweb.it/


function getTime($time){
    //viene passata una data nella forma aaaa-mmm-dd hh:mm:ss (es. 2015-09-13 16:30:40)
    //devo restituire gg/mm/aaaa hh:mm

    $temp = explode(' ', $time);
    $time1 = explode('-', $temp[0]);
    $time2 = explode(':', $temp[1]);

    return $time1[2].'/'.$time1[1].'/'.$time1[0].' '.$time2[0].':'.$time2[1];
}


function getNome($field){    
      return $field->getNome();          
}

?>