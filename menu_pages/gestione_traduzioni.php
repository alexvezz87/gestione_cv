<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$traduzioneController = new TraduzioneController();


?>

<h1>Gestione Traduzioni</h1>


<?php
//pagina inserisci cv
foreach($_POST as $key => $value){
    $traduzione = new Traduzione();
    $traduzione->setCod($key);
    if(strpos($key, 'ita') == true){
        //italiano
        $traduzione->setLan('ita');
    }
    else if(strpos($key, 'eng')){
        //inglese
        $traduzione->setLan('eng');
    }
    $traduzione->setText($value);
    //salvo
    $traduzioneController->salvaTraduzione($traduzione);
}


?>

<form action="<?php echo curPageURL() ?>" method="POST">
    <!-- pagina inserisci CV -->
    
    <h3>Inserisci CV</h3>
    <table class="table-traduzioni">
        <tr>
            <td>
                Titolo pagina ITA
            </td>
            <td>                
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('inserisci-cv-title-ita'); ?>' name="inserisci-cv-title-ita">
            </td>
        </tr>
        <tr>
            <td>                
               Titolo pagina ENG
            </td>
            <td>
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('inserisci-cv-title-eng'); ?>' name="inserisci-cv-title-eng">
            </td>
        </tr>
        <tr>
            <td>
                Testo link ITA
            </td>
            <td>                
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('inserisci-cv-link-ita'); ?>' name="inserisci-cv-link-ita">
            </td>
        </tr>
        <tr>
            <td>                
                Testo link ENG
            </td>
            <td>
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('inserisci-cv-link-eng'); ?>' name="inserisci-cv-link-eng">
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 1 ITA
            </td>
            <td>
                <?php
                    $editor_id = 'inserisci-cv-text-1-ita';
                    $content = $traduzioneController->getTraduzione($editor_id);               
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 1 ENG
            </td>
            <td>
                <?php                    
                    $editor_id = 'inserisci-cv-text-1-eng';
                    $content = $traduzioneController->getTraduzione($editor_id); 
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>    
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 2 ITA
            </td>
            <td>
                <?php
                    $editor_id = 'inserisci-cv-text-2-ita';
                    $content = $traduzioneController->getTraduzione($editor_id); 
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 2 ENG
            </td>
            <td>
                <?php                    
                    $editor_id = 'inserisci-cv-text-2-eng';
                    $content = $traduzioneController->getTraduzione($editor_id); 
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>    
            </td>
        </tr>
    </table>
    <input type="submit" value="SALVA" />
</form>
<hr>



<form action="<?php echo curPageURL() ?>" method="POST" name="scopri-cv">
    <!-- pagina inserisci CV -->
    
    <h3>Scopri CV</h3>
    <table class="table-traduzioni">
        <tr>
            <td>
                Titolo pagina ITA
            </td>
            <td>                
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('scopri-cv-title-ita'); ?>' name="scopri-cv-title-ita">
            </td>
        </tr>
        <tr>
            <td>                
                Titolo pagina ENG
            </td>
            <td>
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('scopri-cv-title-eng'); ?>' name="scopri-cv-title-eng">
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 1 ITA
            </td>
            <td>
                <?php
                    $editor_id = 'scopri-cv-text-1-ita';
                    $content = $traduzioneController->getTraduzione($editor_id);               
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 1 ENG
            </td>
            <td>
                <?php                    
                    $editor_id = 'scopri-cv-text-1-eng';
                    $content = $traduzioneController->getTraduzione($editor_id); 
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>    
            </td>
        </tr>
        <tr>
            <td>
                Come funziona ITA
            </td>
            <td>                
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('scopri-cv-how-title-ita'); ?>' name="scopri-cv-how-title-ita">
            </td>
        </tr>
        <tr>
            <td>                
                Come funziona ENG
            </td>
            <td>
                <input type="text" value='<?php echo $traduzioneController->getTraduzione('scopri-cv-how-title-eng'); ?>' name="scopri-cv-how-title-eng">
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 2 ITA
            </td>
            <td>
                <?php
                    $editor_id = 'scopri-cv-text-2-ita';
                    $content = $traduzioneController->getTraduzione($editor_id);               
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Descrizione 2 ENG
            </td>
            <td>
                <?php                    
                    $editor_id = 'scopri-cv-text-2-eng';
                    $content = $traduzioneController->getTraduzione($editor_id); 
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => $editor_id );
                    wp_editor( $content, $editor_id, $settings );
                ?>    
            </td>
        </tr>
    </table>
    <input type="submit" value="SALVA" />
</form>