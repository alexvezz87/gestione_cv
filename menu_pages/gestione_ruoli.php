<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$printer = new WriterCV();

?>

<h1>Gestione Ruoli</h1>

<?php echo $printer->listenerAdminRuoli(); ?>

<h3>Inserisci Ruolo</h3>
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

<h3>Ricerca</h3>
<div class="ricerca-ruolo">
    <form action="<?php echo curPageURL() ?>" name="ricerca-ruolo" method="POST">
        <div class="field">
            <label for="nome-ruolo">Nome</label>
            <input id="nome-ruolo" type="text" value="<?php if(isset($_POST['nome-ruolo'])){echo $_POST['nome-ruolo'];} ?>" name="nome-ruolo" />
        </div>
        <div class="field">
            <label for="ricerca-categoria">Categoria commerciale</label>
            <?php echo getSelectCategoriaCommercialeNotRequired();  ?>
        </div>
        <div class="field">
            <label for="ricerca-stato">Stato</label>
            <select id="ricerca-stato" name="ricerca-stato">
                <option value="-1"></option>
                <option value="0" <?php if(isset($_POST['ricerca-stato']) && $_POST['ricerca-stato'] == 0){echo 'selected'; } ?>>Da approvare</option>
                <option value="1" <?php if(isset($_POST['ricerca-stato']) && $_POST['ricerca-stato'] == 1){echo 'selected'; } ?>>Pubblicato</option>
            </select>
        </div>
        <div class="field">
            <input type="submit" name="ricerca-ruolo" value="Ricerca" />
        </div>    
    </form>    
</div>
<div class="risultati-ricerca">
    <?php echo $printer->listenerSearchRuoli(); ?>
</div>

<?php echo $printer->listenerFormRuoli() ?>


