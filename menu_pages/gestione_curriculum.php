<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$printer = new WriterCV();

?>

<h1>Gestione Curriculum</h1>

<h3>Curriculum da approvare</h3>
<?php echo $printer->printCVsNonPubblicati() ?>