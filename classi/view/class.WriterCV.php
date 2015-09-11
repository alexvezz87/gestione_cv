<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//includo i controller
//require_once plugin_dir_path().'classi/controller/class.CvController.php';
//require_once '../../controller/class.RuoloController.php';


/**
 * Description of class
 *
 * @author Alex
 */
class WriterCV {

    private $cvController;
    private $ruoloController;
    private $locatorController;
    
    function __construct() {
        $this->cvController = new CvController();
        $this->ruoloController = new RuoloContoller();
        $this->locatorController = new LocatorController();
    }
    
    
    //metodi
    function getCvController() {
        return $this->cvController;
    }

    function getRuoloController() {
        return $this->ruoloController;
    }

    function setCvController($cvController) {
        $this->cvController = $cvController;
    }

    function setRuoloController($ruoloController) {
        $this->ruoloController = $ruoloController;
    }


    public function printInsertCvForm(){
        
                
        $html .= '<h2>Form di Inserimento</h2>'
                . '<form id="inserimento-cv" action="'.curPageURL().'" type="POST" >'
                . '<div class="field">'
                . '<label for="nome">Nome</label>'
                . '<input type="text" id="nome" value="" name="nome" required/>'
                . '</div>'
                . '<div class="field">'
                . '<label for="cognome">Cognome</label>'
                . '<input type="text" id="cognome" value="" name="cognome" required/>'
                . '</div>'
                . '<div class="field>'
                . '<label for="email">Email</label>'
                . '<input type="email" id="email" name="email" value="" required/>'
                . '</div>'
                . '<div class="field">'
                . '<label for="select-categoria">Categoria occupazionale</label>'
                .getSelectCategoriaCommerciale()         
                . '</div>'
                //il selettore dei ruoli ha bisogno di essere caricato dinamicamente tramite una chiamata ajax
                . '<div id="contenitore-selettore-ruoli" class="field"></div>'
                . '<div class="field">'
                . '<label for="altro-ruolo">Inserisci un ruolo, se non presente</li>'
                . '<input type="text" id="altro-ruolo" name="altro-ruolo" value="" />'
                . '</div>'
                . '<div class="field doppio">'
                . $this->printRegioniProvince()
                . '</div>'                
                . '<div class="submit-button">'
                . '<input type="submit" name="invia-cv" value="Invia Curriculum" />'
                . '</div>'
                . '</form>';
        
        
        //variabile a parte per scrivere chiamate jquery
        
        _e($html); 
        
        $this->printAjaxCallRegioniProvince();
        $this->printAjaxCallRuoli();
        
?>
        
<?php
    }    
    
  
       
    function printRegioniProvince(){
        
        $html = 'Dove cerchi occupazione ?'
                . '<div id="container-regione">'
                . '<label for="regione">Regione</label>'
                . '<select id="regione" name="regione">'
                . '<option value=""></option>';

        //ottengo le regioni
        $regioni = $this->locatorController->getRegioni();        
        foreach($regioni as $regione){
            $html.= '<option value="'.$regione['cod'].'">'.$regione['nome'].'</option>';
        }
                        
        $html.= '</select></div>';
        
        //ottengo le province mediante chiamata ajax
        $html.= '<label for="provincia">Provincia</label>'
                . '<div id="container-province"></div>';
        
        return $html;        
    }
    
    
    function printAjaxCallRuoli(){
?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var idCat = jQuery('#select-categoria').val();
                jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                
    
                jQuery(document).on('change', '#select-categoria',function(){
                    idCat = jQuery('#select-categoria').val();                    
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_categoria: idCat}, printRuoli, 'json');
                });
                
                
            });
            
            function printRuoli(data){
                jQuery(function(){
                    //pulisco
                    jQuery('#contenitore-selettore-ruoli').html("");
                    
                    var html = '<label for="ruolo">Seleziona Ruolo</label>';
                    if(data.length > 0){
                        html += '<select id="ruolo">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            html += '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                        }
                        html+= '</select>';
                    }
                    else{
                        html += '<br>Ruoli non acora disponibili per questa categoria';
                    }
                    
                    jQuery('#contenitore-selettore-ruoli').append(html);
                });
            }
            
        </script>
<?php        
    }
    
    
    function printAjaxCallRegioniProvince(){
 ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var idRegione = jQuery('#regione').val();
                 //pulisco
                    jQuery('#container-province').html("");
                if(idRegione !== ''){
                    jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                }
    
                jQuery(document).on('change', '#regione',function(){
                    idRegione = jQuery('#regione').val();   
                     //pulisco
                    jQuery('#container-province').html("");
                    if(idRegione !== ''){
                        jQuery.post('<?php echo plugins_url().'/gestione_cv/ajax/ajax_call.php' ?>', {id_regione: idRegione}, printProvince, 'json');
                    }
                });
                
                
            });
            
            function printProvince(data){
                jQuery(function(){                   
                     
                    var html = ""; 
                    if(data.length > 0){
                        html += '<select id="province">';
                        html += '<option value=""></option>';
                        for(var i=0; i< data.length; i++){
                            html += '<option value="'+data[i].cod+'">'+data[i].nome+'</option>';
                        }
                        html+= '</select>';
                    }
                    else{
                        
                    }
                    
                    jQuery('#container-province').append(html);
                });
            }
            
        </script>
<?php
    }

    
}
