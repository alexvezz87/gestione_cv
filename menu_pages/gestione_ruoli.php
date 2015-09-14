<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$printer = new WriterCV();

?>

<h1>Gestione Ruoli</h1>

<?php echo $printer->listenerInsertRuolo(); ?>
<?php echo $printer->listenerAdminRuoli(); ?>

<div class="insert-form">
    <form id="insert-ruolo" name="insert-ruolo" action="<?php echo curPageURL() ?>" method="POST" >
        <div class="field">
            <label for="categoria">Seleziona la categoria commerciale</label>
            <?php echo getSelectCategoriaCommerciale();  ?>
        </div>
        <div class="field">
            <label for="ruolo">Nome Ruolo</label>
            <input id="ruolo" name="ruolo" type="text" value="" required />
        </div>
        <div class="field">
            <input type="submit" name="inserisci-ruolo" value="Inserisci" />
        </div>
    </form>
</div>


<h3>Ruoli da approvare</h3>
<?php echo $printer->printRuoliNonPubblicati() ?>

<h3>Ultimi ruoli approvati</h3>
<?php echo $printer->printUltimiRuoliApprovati() ?>

<?php echo $printer->listenerFormRuoli() ?>


