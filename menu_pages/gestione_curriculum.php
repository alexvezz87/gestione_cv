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
    <form name="form-ricerca-cv" action="<?php echo curPageURL() ?>#risultati-cv" method="POST">
        <div class="field">
            <label for="ricerca-nome">Nome</label>
            <input type="text" id="ricerca-nome" name="ricerca-nome" value="" />
        </div>
        <div class="field">
            <label for="ricerca-cognome">Cognome</label>
            <input type="text" id="ricerca-cognome" name="ricerca-cognome" value="" />
        </div>
        <div class="field">
            <label for="ricerca-email">Email</label>
            <input type="email" id="ricerca-email" name="ricerca-email" value="" />
        </div>
        <div class="field">
        <?php echo $printer->printRegioniProvince('') ?>        
        </div>
        <div class="field">
            <label for="ricerca-categoria">Categoria</label>
            <?php echo getSelectCategoriaCommercialeNotRequired();  ?>
            <div id="contenitore-selettore-ruoli" class="field"></div>
        </div>
        <div class="field">
            <label for="ricerca-stato">Stato</label>
            <select id="ricerca-stato" name="ricerca-stato">
                <option value=""></option>
                <option value="0">Da Approvare</option>
                <option value="1">Pubblicato</option>
            </select>
        </div>
        
        <div class="ricerca field">
            <input type="submit" value="Ricerca" name="ricerca-cv" />
        </div>
        <div class="clear"></div>
    </form>
    
    <?php echo $printer->printAjaxCallRegioniProvince() ?>
    <?php echo $printer->printAjaxCallRuoli() ?>
    
</div>

<div id="risultati-cv">
   <?php echo $printer->listenerSearchCV('admin') ?> 
</div>