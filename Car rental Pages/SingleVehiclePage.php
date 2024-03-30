<?php
    /*Ottenimento della targa per far apparire dinamicamente l'auto con tutte le sue informazioni*/
    $targa = $_GET['targa'];
    require "db.php";
    $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
    /*Query al db che preleva il veicolo con la specifica targa*/
    $sql = "SELECT * FROM VEICOLO WHERE targa=$1";
    $prep = pg_prepare($db, "sqlSingoloVeicolo", $sql); 
    $ret = pg_execute($db, "sqlSingoloVeicolo", array($targa));
    if(!$ret) {
        echo "ERRORE QUERY: " . pg_last_error($db);
    }
    else{
        if ($veicolo = pg_fetch_assoc($ret)){
            /*Prelievo delle informazioni dall'array associativo ritornato dalla query 
            che contiene tutte le informazioni del veicolo che l'utente ha deciso di noleggiare*/
            $nome = $veicolo['nome'];
            $patente = $veicolo['patente'];
            $descrizione = $veicolo['descrizione'];
            $prezzo = $veicolo['prezzo_al_giorno'];
            $immagine = $veicolo['immagine'];
            $nPosti = $veicolo['numero_posti'];
        }
        else{
            echo "errore nel recupero dati";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="SingleVehiclePage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            //Stampa del titolo corrispondente al veicolo
            echo "$nome";
            //Inizio della sessione
            session_start();
        ?>
    </title>
</head>
<body>
    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <section class="main">
        <?php
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1>$nome</h1>";
            include("header.php");
        ?>

        <?php
            //Creazione del div contenente i dati del veicolo
            echo "<div class=\"vehicle\">
                    <img class=\"vehicle\" src=\"$immagine\">
                    <h1>$nome</h1>
                    <div class=\"informazioni\">
                        <div class =\"tendinacontainer\">
                            <div class=\"nposti\"><img src=\"Immagini autonoleggio/numeroposti.png\"><p class=\"nposti\">$nPosti</p></div>
                            <div class=\"tendinainfo\">
                                <p>Numero posti</p>
                            </div>
                        </div>
                        <div class =\"tendinacontainer\">
                            <div class=\"patente\"><img src=\"Immagini autonoleggio/patente.png\"><p class=\"patente\">$patente</p></div>
                            <div class=\"tendinainfo\">
                                <p>Patente</p>
                            </div>
                        </div>
                        <div class =\"tendinacontainer\">
                            <div class=\"targa\"><img src=\"Immagini autonoleggio/targa.png\"><p class=\"targa\">$targa</p></div>
                            <div class=\"tendinainfo\">
                                <p>Targa</p>
                            </div>
                        </div>
                    </div>
                    <h2>A soli <span>$prezzo €</span> al giorno!</h2>
                    <div class=\"descrizione\"><p class=\"descrizione\">$descrizione</p></div>";
                    //Se l'utente non ha effettuato il login e decide di noleggiare l'auto, verrà portato ad effettuare il login o a registrarsi
                    if(empty($_SESSION["nome"])){
                        echo "<a href=\"SignLogPage.php\"><button id=\"acquista\"><span>Noleggia ora!</span></button></a>";
                    } else {
                        //Se l'utente è loggato, allora si aprirà la pagina del noleggio dove potrà noleggiare l'auto
                        echo "<a href=\"RentPage.php?targa=$targa\"><button id=\"acquista\"><span>Noleggia ora!</span></button></a>";
                    }
                echo "</div>";
        ?>
        <!--Inclusione del footer-->
        <?php include("footer.php");?>
    </section>
</body>
</html>