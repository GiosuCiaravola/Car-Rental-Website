<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="MainPage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMCN RENT</title>
</head>
<body>
    <!--Inizio della sessione dell'utente-->
    <?php session_start();?>

    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <!--Questa sezione contiene il contenuto principale sulla destra-->
    <section class="main">

        <?php
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1 class=\"scrollingtext\">Benvenuto su GMCNRent, qui puoi noleggiare auto e moto a prezzi scontatissimi!</h1>";
            include("header.php");
        ?>

        <!--Questa sezione contiene il contenuto principale sulla destra sotto l'header-->
        <div class="maincontent">

            <!--La classe "header2" contiene la foto di sfondo e la scritta principale-->
            <div class="header2">
                <img class="mainimage" src="Immagini autonoleggio/sfondo.jpg">
                <p class="scrittaprincipale">Noleggia l'auto dei tuoi sogni!</p>
            </div>

            <!--La classe "new" contiene l'immagine del simbolo del "new" e la scritta delle ultime auto aggiunte-->
            <div class="new">
                <img src="Immagini autonoleggio/new.png"/>
                <p class="motoprimopiano">Ultime auto aggiunte</p>
            </div>

            <!--La classe "catalogoauto" contiene le ultime 3 auto inserite nel db per "data di inserimento"-->
            <div class="catalogoauto">
            <?php
                require "db.php";
                //Connessione al db
                $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
                //Query al db che seleziona dalla tabella veicolo le ultime 3 auto aggiunte per data di inserimento
                $sql = "SELECT * FROM VEICOLO WHERE tipo_veicolo='Auto' ORDER BY data_inserimento DESC LIMIT 3";
                $prep = pg_prepare($db, "sqlAuto", $sql); 
                $ret = pg_execute($db, "sqlAuto", array());
                if(!$ret) {
                    echo "ERRORE QUERY: " . pg_last_error($db);
                }
                else {
                    //Creazione di una griglia per la visualizzazione delle 3 auto
                    echo '<div class="gridcontainer">'; 
                    $count = 0;
                    while($auto = pg_fetch_assoc($ret)) {
                        $targa = $auto['targa'];
                        $immagine = $auto['immagine'];
                        $nome = $auto['nome'];
                        $prezzo = $auto['prezzo_al_giorno'];
                        if ($count % 3 === 0) {
                            echo '<div class="gridrow">';
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
            </div>

            <!--La classe "new" contiene l'immagine del simbolo del "new" e la scritta delle ultime auto aggiunte-->
            <div class="new">
                <img src="Immagini autonoleggio/new.png"/>
                <p class="motoprimopiano">Ultime moto aggiunte</p>
            </div>

            <!--La classe "catalogmoto" contiene le ultime 3 moto inserite nel db per "data di inserimento"-->
            <div class="catalogomoto">
            <?php
                require "db.php";
                //Connessione al db
                $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
                //Query al db che seleziona dalla tabella veicolo le ultime 3 auto aggiunte per data di inserimento
                $sql = "SELECT * FROM VEICOLO WHERE tipo_veicolo='Moto' ORDER BY data_inserimento DESC LIMIT 3";
                $prep = pg_prepare($db, "sqlMoto", $sql); 
                $ret = pg_execute($db, "sqlMoto", array());
                if(!$ret) {
                    echo "ERRORE QUERY: " . pg_last_error($db);
                }
                else {
                    //Creazione di una griglia per la visualizzazione delle 3 auto
                    echo '<div class="gridcontainer">'; 
                    $count=0; 
                    while($moto = pg_fetch_assoc($ret)) {
                        $targa = $moto['targa'];
                        $immagine = $moto['immagine'];
                        $nome = $moto['nome'];
                        $prezzo = $moto['prezzo_al_giorno'];
                        if ($count %3 === 0) {
                            echo '<div class="gridrow">';
                        }
                        echo "<div class=\"vehicle\">
                                <img class=\"vehicle\" src=\"$immagine\">
                                <h1>$nome</h1>
                                <h2>A soli <span style=\"color: red\">$prezzo €</span> al giorno!</h2>
                                <a href=\"SingleVehiclePage.php?targa=$targa\"><button id=\"acquista\"><span>Noleggia ora!</span></button></a>
                            </div>";
                        if (($count+1) % 3 === 0) {
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
            </div>
            <!--Inclusione del footer-->
            <?php include("footer.php");?>
        </div>
    </section>     
</body>
</html>