<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$printer = new WriterCV();

?>

<h1>Gestione Curriculum</h1>
<?php echo $printer->listenerAdminCV() ?>


<h3>Curriculum da approvare</h3>
<?php echo $printer->printCVsNonPubblicati() ?>

<h3>Ultimi Curriculum inseriti</h3>
<?php echo $printer->printUltimiCVsPubblicati() ?>

<h3>Ricerca</h3>
<div id="ricerca-cv">
    <form name="form-ricerca-cv" action="<?php echo curPageURL() ?>" method="POST">
        <label for="ricerca-nome">Nome</label>
        <input type="text" id="ricerca-nome" name="ricerca-nome" value="" />
        <label for="ricerca-cognome">Cognome</label>
        <input type="text" id="ricerca-cognome" name="ricerca-cognome" value="" />
        <label for="ricerca-email">Email</label>
        <input type="email" id="ricerca-email" name="ricerca-email" value="" />
                
        <?php echo $printer->printRegioniProvince('') ?>        
        
        <label for="ricerca-categoria">Categoria</label>
        <?php echo getSelectCategoriaCommercialeNotRequired();  ?>
        <div id="contenitore-selettore-ruoli" class="field"></div>
        <label for="ricerca-stato">Stato</label>
        <select id="ricerca-stato" name="ricerca-stato">
            <option value=""></option>
            <option value="0">Da Approvare</option>
            <option value="1">Pubblicato</option>
        </select>
        
        <div class="ricerca">
            <input type="submit" value="Ricerca" name="ricerca-cv" />
        </div>
        
    </form>
    
    <?php echo $printer->printAjaxCallRegioniProvince() ?>
    <?php echo $printer->printAjaxCallRuoli() ?>
    
</div>

<div id="risultati-cv">
   <?php echo $printer->listenerSearchCV() ?> 
</div>