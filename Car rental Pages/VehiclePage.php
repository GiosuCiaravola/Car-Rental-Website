<?php  
    session_start();
    $tipoVeicolo = $_GET['tipoVeicolo'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="VehiclePage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noleggia la tua <?php echo "$tipoVeicolo"; ?></title>
</head>
<body>
    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <section class="main">
        <?php
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1>Catalogo $tipoVeicolo</h1>";
            include("header.php");
        ?>
        <?php
            require "db.php";
            //Connessione al db
            $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
            /*Query al db che seleziona dalla tabella veicolo tutti i veicoli che hanno come attributo "tipo_veicolo" 
            il tipoVeicolo passato quando l'utente ha cliccato sui pulsanti della sidebar (Auto o Moto)*/
            $sql = "SELECT * FROM VEICOLO WHERE tipo_veicolo=$1";
            $prep = pg_prepare($db, "sqlVeicolo", $sql); 
            $ret = pg_execute($db, "sqlVeicolo", array($tipoVeicolo));
            if(!$ret) {
                echo "ERRORE QUERY: " . pg_last_error($db);
            }
            else {
                //Creazione di una griglia per la visualizzazione dei tre veicoli
                echo '<div class="grid-container">'; 
                $count = 0; 
                while($veicolo = pg_fetch_assoc($ret)) {
                    $targa = $veicolo['targa'];
                    $immagine = $veicolo['immagine'];
                    $nome = $veicolo['nome'];
                    $prezzo = $veicolo['prezzo_al_giorno'];
                    if ($count % 3 === 0) {
                        echo '<div class="grid-row">';
                    }
                    echo "<div class=\"vehicle\">
                            <img class=\"vehicle\" src=\"$immagine\">
                            <h1>$nome</h1>
                            <h2>A soli <span style=\"color: red\">$prezzo €</span> al giorno!</h2>
                            <a href=\"SingleVehiclePage.php?targa=$targa\"><button id=\"acquista\"><span>Noleggia ora!</span></button></a>
                        </div>";
                    if (($count + 1) % 3 === 0) {
                        echo '</div>';
                    }
                    $count++;
                }
                if ($count % 3 !== 0) {
                    echo '</div>';
                }
                echo '</div>'; 
             }
        ?>
        <!--Inclusione del footer-->
        <?php include("footer.php");?>
    </section>
</body>
</html>