<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="ModifyVehiclePage.js"></script>
    <link rel="stylesheet" href="ModifyVehiclePage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento nuovo veicolo</title>
</head>
<!--Effettuiamo il controllo sul checkTipoVeicolo() in questo punto perché per qualche eventuale errore di inserimento
vogliamo che, quando la pagina viene ricaricata, i campi rimangano invariati-->
<body onload="checkTipoVeicolo()">
    <?php
        //Inizio della sessione prelevando il nome dell'admin
        session_start();
        $user=$_SESSION["nome"];
        //Visibilità nascosta degli errori
        $errorVeicolo = "none";
        $errorImmEsistente = "none";
        $errorEstErrata = "none";
        $errorImmagine = "none";
        //Da qui in poi si prelevano le informazioni relative all'auto da inserire o rimuovere
        if(isset($_POST['nome']))
            $nome = $_POST['nome'];
        else
            $nome = "";
        if(isset($_POST['descrizione']))
            $descrizione = $_POST['descrizione'];
        else
            $descrizione = "";
        if(isset($_POST['tipoVeicolo']))
            $tipoVeicolo = $_POST['tipoVeicolo'];
        else
            $tipoVeicolo = "";
        if($tipoVeicolo === "Auto" && isset($_POST['patenteAuto']))
            $patente = $_POST['patenteAuto'];
        else if($tipoVeicolo === "Moto" && isset($_POST['patenteMoto']))
            $patente = $_POST['patenteMoto'];
        else
            $patente = "";
        if(isset($_POST['targa']))
            $targa = $_POST['targa'];
        else
            $targa = "";
        if(isset($_POST['prezzo']))
            $prezzo = $_POST['prezzo'];
        else
            $prezzo = "";
        if(isset($_POST['nPosti']))
            $nPosti = $_POST['nPosti'];
        else
            $nPosti = "";
        
        /*Questo if serve per controllare se l'immagine esiste e se si
        ottiene il nome del file immagine del campo $_FILES combinando il
        nome della cartella in cui sono inserite le immagini e il file inserito
        il cui nome è ottenuto dalla funzione basename() poiché $_FILES è
        un array associativo che contiene le informazioni sui file caricati*/
        if(isset($_POST['inserisci'])) {
            $nomeImmagine = "Immagini autonoleggio/" . basename($_FILES["immagine"]["name"]);
            $checkImm = true;
            //Da qui preleviamo l'estensione dell'immagine per effettuare dei controlli
            $estensioneImmagine = strtolower(pathinfo($nomeImmagine, PATHINFO_EXTENSION));
            
            //Controllo se il file esiste già, se così dovesse essere si rende visibile l'errore apposito
            if (file_exists($nomeImmagine)) {
                $errorImmEsistente = "block";
                $checkImm = false;
            }
            
            //Consentire l'inserimento di solo determinati formati di file, se così dovesse essere si rende visibile l'errore apposito
            if ($estensioneImmagine != "jpg" && $estensioneImmagine != "jpeg" && $estensioneImmagine != "png") {
                $errorEstErrata = "block";
                $checkImm = false;
            }
        }   
        /*Se il campo targa non è vuoto e l'immagine è stata ricevuta correttamente, prima di tutto
        si controlla se la targa non sia già presente, se così si rende visibile l'errore apposito;
        dopodiché, se la targa non esiste già, si sposta il file inserito dall'admin nella cartella 
        specificata prima ("Immagini autonoleggio") e se l'inserimento del veicolo va a buon fine
        viene richiamata la funzione "alertVeicoloInserito() passandogli come parametro proprio la targa"*/
        if($targa != "" && $checkImm) {
            if(vehicleExist($targa)) {
                $errorVeicolo = "block";
            } else {
                if (move_uploaded_file($_FILES["immagine"]["tmp_name"], $nomeImmagine)) {
                    if(insertVeicolo($nome, $descrizione, $tipoVeicolo, $patente, $targa, $prezzo, $nPosti, $nomeImmagine)) {
                        echo '<script>';
                        echo 'alertVeicoloInserito("'.$targa.'");';
                        echo '</script>';
                    }
                } else {
                    $errorImmagine = "block";
                }
            }
        }
    ?>

    <?php
        /*Questa è la sezione per la rimozione di un veicolo che avviene semplicemente indicando 
        nell'input text apposito la targa associata al veicolo da rimuovere*/
        $errorRimozione = "none";
        if(isset($_POST['targaDaRimuovere']))
            $targaDaRimuovere = $_POST['targaDaRimuovere'];
        else 
            $targaDaRimuovere = "";
        /*Se è stato cliccato il pulsante rimuovi controlla prima se il veicolo da rimuovere
        associato alla targa da rimuovere esiste, se così allora si effettua la rimozione del veicolo
        con la funzione removeVehicle() passandogli come parametro la targa da rimuovere.
        Se il veicolo è stato rimosso con successo allora viene richiamata la funzione alertVeicoloRimosso().
        Se dovesse esserci un errore allora l'erroreRimozione verrà visualizzato*/
        if(isset($_POST['rimuovi'])) {
            if(vehicleExist($targaDaRimuovere)) {
                if(removeVehicle($targaDaRimuovere)) {
                    echo '<script>';
					echo 'alertVeicoloRimosso("'.$targaDaRimuovere.'");';
					echo '</script>';
                    $targaDaRimuovere = "";
                }
            } else {
                $errorRimozione = "block";
            }
        }
    ?>
    
    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <section class="main">
        <?php
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1>Aggiungi o rimuovi un veicolo</h1>";
            include("header.php");
        ?>
    </section>

    <!--Contenitore esterno per il form di inserimento o rimozione del veicolo-->
    <section class="maincontent">
        <div class="externalcontainer">
            <!--Container del form di inserimento del veicolo-->
            <div class="container">
                <!--Per l'inserimento tutti i campi del formsono obbligatori-->
                <form id="insertForm" method="post" action="ModifyVehiclePage.php" enctype="multipart/form-data">
                        <label for="nome">Nome
                            <input type="text" name="nome" id="nome" value="<?php echo $nome?>" required/>
                        </label>
                        <!--Per la descrizione (max 250 caratteri) usiamo il "textarea"-->
                        <label for="descrizione">Descrizione
                            <textarea name="descrizione" maxlength="250" rows="4" cols="25" id="descrizione" value="<?php echo $descrizione?>" required></textarea>
                        </label>
                        <!--Inserimento del select del "tipo veicolo" (Auto o moto) e in base all'opzione selezionata verrà 
                        richiamata la funzione checkTipoVeicolo() che controlla se il veicolo da inserire è un'auto o una moto
                        mostrando l'apposita "patente necessaria"-->
                        <label for="tipoVeicolo">Tipo veicolo
                            <select id="tipoVeicolo" name="tipoVeicolo" onchange="checkTipoVeicolo()" required>
                                <option value="Auto">Auto</option>
                                <option value="Moto" <?php if($tipoVeicolo === "Moto") echo "selected";?>>Moto</option>
                            </select>
                        </label>
                    <p id="patenteAuto">
                        <label for="patenteAuto">Patente necessaria
                            <!--Input text di sola lettura-->
                            <input id="patenteAutov" name="patenteAuto" value="B" readonly/>
                        </label>
                    </p>
                    <p id="patenteMoto">
                        <label for="patenteMoto">Patente necessaria
                            <select id="patenteMotov" name="patenteMoto" required>
                                <option value="AM">AM</option>
                                <option value="A1" <?php if($patente === "A1") echo "selected";?>>A1</option>
                                <option value="A2" <?php if($patente === "A2") echo "selected";?>>A2</option>
                                <option value="A" <?php if($patente === "A") echo "selected";?>>A</option>
                            </select>
                        </label>
                    </p>
                        <!--Input della targa con controllo della targa tramite la funzione checkTarga-->
                        <label for="targa">Targa
                            <input type="text" minlength="7" maxlength="7" name="targa" id="targa" onchange="checkTarga()" value="<?php echo $targa?>" required/>
                        </label>
                        <label for="prezzo">Prezzo al giorno
                            <input type="text" name="prezzo" id="prezzo" pattern="^\d{0,3}(\.\d{2})?$" value="<?php echo $prezzo?>" required/>
                        </label>
                        <label for="nPosti">Numero di posti
                            <input type="text" name="nPosti" id="nPosti" pattern="^\d+$" value="<?php echo $nPosti?>" required/>
                        </label>
                        <!--div contenente l'input di tipo file che controlla che file è stato inserito e tramite la funzione 
                        updateFileName() mostra affianco il nome del file appena inserito-->
                        <div class="filecontainer">
                            <input type="file" id="immagine" name="immagine" required onchange="updateFileName(this)" />
                            <label for="immagine" class="immagine">Scegli un file</label>
                            <span id="fileselezionato"></span>
                        </div>
                        <p>
                            <!--Pulsante di inserimento del veicolo-->
                            <input type="submit" id="inserisci" name="inserisci" value="Inserisci Veicolo"/>
                        </p>
                        <!--div contenente gli errori che verranno mostrati se qualcosa non dovesse essere stato inserito in maniera corretta-->
                        <div class="errori">
                            <p id="errorTargaAuto" >La targa non ha un formato valido per le Auto</p>
                            <p id="errorTargaMoto" >La targa non ha un formato valido per le Moto</p>
                            <p class="errorVeicolo" style="display: <?php echo $errorVeicolo?>">Il veicolo con targa <?php echo $targa?> è già esistente!</p>
                            <p class="errorImmEsistente" style="display: <?php echo $errorImmEsistente?>">L'immagine esiste già nella directory!</p>
                            <p class="errorEstErrata" style="display: <?php echo $errorEstErrata?>">Sono consentiti solo file di tipo JPG, JPEG e PNG!</p>
                            <p class="errorImmagine" style="display: <?php echo $errorImmagine?>">Immagine non caricata!</p>
                        </div>
                </form>
            </div>
            <!--Container del form di cancellazione del veicolo specificando la targa da voler rimuovere-->
            <div class="container2">
                <form id="deleteForm" method="post" action="ModifyVehiclePage.php">
                    <p>
                        <label for="targaDaRimuovere">Targa da rimuovere
                            <input type="text" name="targaDaRimuovere" id="targaDaRimuovere" value="<?php echo $targaDaRimuovere?>" required/>
                        </label>
                    </p>
                    <!--Errore che verrà visualizzato se la targa associata al veicolo da rimuovere non esiste-->
                    <p class="errorRimozione" style="display: <?php echo $errorRimozione?>">Il veicolo con targa <?php echo $targaDaRimuovere?> non esiste!</p>
                    <input type="submit" id="rimuovi" name="rimuovi" value="Rimuovi Veicolo"/>
                </form>
            </div>
        </div>
    </section>  
    <!--Inclusione del footer-->            
    <?php include("footer.php");?>
</body>
</html>

<?php
    /*Funzione che controlla se il veicolo inserito dall'admin esiste già facendo una query al db sulla targa passata come parametro*/ 
    function vehicleExist($targa){
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        $sql = "SELECT targa FROM VEICOLO WHERE targa=$1";
        $prep = pg_prepare($db, "sqlVehicle", $sql); 
        $ret = pg_execute($db, "sqlVehicle", array($targa));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            if ($row = pg_fetch_assoc($ret)){ 
                return true;
            }
            else{
                return false;
            }
        }
    }

    /*Funzione che permette l'inserimento del veicolo facendo una query al db con i parametri sottoindicati*/
    function insertVeicolo($nome, $descrizione, $tipoVeicolo, $patente, $targa, $prezzo, $nPosti, $nomeImmagine) {
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        $sql = "INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti) VALUES ($1, $2, CURRENT_DATE, $3, $4, $5, $6, $8, $7);";
        $prep = pg_prepare($db, "insertUser", $sql); 
        $ret = pg_execute($db, "insertUser", array($nome, $targa, $patente, $descrizione, $prezzo, $tipoVeicolo, $nPosti, $nomeImmagine));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            return true;
        }
    }

    /*Funzione che rimuove il veicolo tramite la targa da rimuovere passata come parametro*/
    function removeVehicle($targaDaRimuovere) {
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        $sql = "SELECT immagine FROM VEICOLO WHERE targa = $1;";
        $prep = pg_prepare($db, "imamgineVeicolo", $sql); 
        $ret = pg_execute($db, "imamgineVeicolo", array($targaDaRimuovere));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
        }
        else{
            /*Oltre al veicolo con tutti i suoi attributi, bisogna anche rimuovere l'immagine dalla cartella: lo si fa usando la funzione unlink*/
            if ($immagine = pg_fetch_assoc($ret)){ 
                if(unlink($immagine['immagine'])) {
                    require "db.php";
                    $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error()); 
                    $sql = "DELETE FROM VEICOLO WHERE targa = $1;";
                    $prep = pg_prepare($db, "deleteVehicle", $sql); 
                    $ret = pg_execute($db, "deleteVehicle", array($targaDaRimuovere));
                    if(!$ret) {
                        echo "ERRORE QUERY: " . pg_last_error($db);
                        return false; 
                    }
                    else{
                        return true;
                    }
                }
            }
            else{
                return false;
            }
        }
    }
?>