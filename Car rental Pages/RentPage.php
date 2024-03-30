<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="RentPage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <script src="RentPage.js"></script>
    <title>Noleggia ora!</title>
</head>
<body>
    <?php
        /*Ottenimento della targa associata al veicolo da noleggiare*/ 
        $targa = $_GET['targa'];
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        //Query al db per selezionare il veicolo da noleggiare con la targa ottenuta
        $sql = "SELECT * FROM VEICOLO WHERE targa=$1";
        $prep = pg_prepare($db, "sqlSingoloVeicolo", $sql); 
        $ret = pg_execute($db, "sqlSingoloVeicolo", array($targa));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
        }
        else{
            //Se la query non ha dato errore si prelevano i dati dell'auto associata alla targa
            if ($veicolo = pg_fetch_assoc($ret)){ 
                $nome = $veicolo['nome'];
                $patente = $veicolo['patente'];         //Patente necessaria per noleggiare il veicolo
                $descrizione = $veicolo['descrizione']; // Non usata per il noleggio
                $prezzo = $veicolo['prezzo_al_giorno'];
                $immagine = $veicolo['immagine'];
                $nPosti = $veicolo['numero_posti'];     // Non usata per il noleggio
                $tipoVeicolo = $veicolo['tipo_veicolo']; 
            }
            else{
                echo "errore nel recupero dati"; //da rivedere
            }
        }
    ?>

    <?php
    //Controlla se è stato inviato il form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invioAcquisto'])) {
        //Ottiene i dati inviati dal form
        $email = $_POST['email'];
        $targa = $_POST['targaVeicolo'];
        $dataInizio = $_POST['dataInizio'];
        $dataFine = $_POST['dataFine'];
        $numeroCarta = $_POST['numeroCarta'];
        $dataScadenzaCarta = $_POST['scadenzaCarta'];
        $codiceCarta = $_POST['codiceCarta'];
        //Inserimento della carta con cui l'utente noleggerà il veicolo
        insertCarta($email, $numeroCarta, $dataScadenzaCarta, $codiceCarta);
        //Controlla se il veicolo non è già noleggiato
        if(checkNoleggio($targa, $dataInizio, $dataFine)) {
                //Se il veicolo non è già occupato da qualcun altro allora inserisce il noleggio nella tabella
                $success = insertNoleggio($email, $targa, $dataInizio, $dataFine);
                    if ($success) {
                        echo '<script>noleggioEffettuato();</script>';
                    } else {
                    }
            } else {
            }
        }

    ?>
    <?php
        //Inizio della sessione
        session_start();
        //Flag che controlla se l'utente non ha mai inserito una carta per effettuare il noleggio
        $carta = false;
        //Ottenimento dell'utente dalla mail della sessione corrente
        if($row = getUser($_SESSION["email"])) {
            //Preleva le informazioni dell'utente
            $user = $row['nome'];
            $email = $row['email'];
            $patenteAuto = $row['patente_auto'];
            $patenteMoto = $row['patente_moto'];
            $numeroCarta = $row['numero_carta'];
            $dataScadenzaCarta = $row['data_scadenza_carta'];
            //La data di scadenza della carta la prende dal db dal formato "AAAA-MM-DD" trasformandola nel formato "YYYY-MM"
            $dataScadenzaCarta = date('Y-m', strtotime($dataScadenzaCarta));
            $codiceCarta = $row['codice_carta'];
            /*Se il numero della carta è presente allora significa che l'utente ha inserito già la carta (magari in precedenza
            ha già effettuato un noleggio) e non lo dovrà fare di nuovo*/
            if($numeroCarta != null)
                $carta = true;
        } else {
            echo "<p>Impossibile recuperare dati utente</p>";
        }
    ?>

    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <section class="main">
        <?php
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1>Dettagli noleggio</h1>";
            include("header.php");
        ?>
    </section>

    <section class="main">
        <!--Creazione del container che conterrà il form delle informazioni del veicolo, dell'utente, di pagamento e dell'acquisto-->
        <div class="container">
            <div class="OrderPage" id="OrderPage">
                <form id="OrderForm" method="post" action="RentPage.php?targa=<?php echo $targa;?>">
                    <!--Creazione del primo fieldset con titolo "Dettagli veicolo"-->
                    <fieldset>
                        <legend>Dettagli veicolo</legend>
                        <p>
                            <label for="nomeVeicolo">Nome veicolo
                                <input type="text" name="nomeVeicolo" id="nomeVeicolo" value="<?php echo $nome?>" readonly/>
                            </label>
                        </p>
                        <p>
                            <label for="targaVeicolo">Targa
                                <input type="text" name="targaVeicolo" id="targaVeicolo" value="<?php echo $targa?>" readonly/>
                            </label>
                        </p>
                        <p>
                            <label for="numeroPosti">Numero posti
                                <input type="text" name="numeroPosti" id="numeroPosti" value="<?php echo $nPosti?>" readonly/>
                            </label>
                        </p>
                        <p>
                            <label for="patenteRichiesta">Patente richiesta
                                <input type="text" name="patenteRichiesta" id="patenteRichiesta" value="<?php echo $patente?>" readonly/>
                            </label>
                        </p>
                    </fieldset>

                    <!--Creazione del secondo fieldset con titolo "Dettagli utente"-->
                    <fieldset>
                        <legend>Dettagli utente</legend>
                        <p>
                            <label for="email">E-mail
                                <input type="text" name="email" id="email" value="<?php echo $email?>" readonly/>
                            </label>
                        </p>
                        <p>
                            <!--Se il veicolo è un auto e quindi si necessita della patente B, viene controllato che l'utente ne è in possesso
                            Se così non dovesse essere verrà visualizzato l'errore che la patente non è in possesso dall'utente-->
                            <?php if ($tipoVeicolo === "Auto"): ?>
                                <label for="patenteAutoPoss">Patente in possesso</label>
                                <input type="text" name="patenteAutoPoss" id="patenteAutoPoss" value="<?php echo $patenteAuto ?>" readonly/>
                                <?php if($patenteAuto !== $patente){
                                        $errorPatenteNonAmmessaVisibility = "visible";
                                    }else{
                                        $errorPatenteNonAmmessaVisibility = "hidden";
                                    }?>
                            <!--Stesso controllo fatto per l'auto lo si fa per la moto-->
                            <?php elseif ($tipoVeicolo === "Moto"): ?>
                                <label for="patenteMotoPoss">Patente in possesso per Moto</label>
                                <input type="text" name="patenteMotoPoss" id="patenteMotoPoss" value="<?php echo $patenteMoto ?>" readonly/>
                                <?php if($patenteMoto === $patente){
                                        $errorPatenteNonAmmessaVisibility = "hidden";
                                    }else if($patenteMoto === 'A1' && $patente === 'AM'){
                                        $errorPatenteNonAmmessaVisibility = "hidden";
                                    }else if($patenteMoto === 'A2' && ($patente === 'AM' || $patente === 'A1')){
                                        $errorPatenteNonAmmessaVisibility = "hidden";
                                    }else if($patenteMoto === 'A'){
                                        $errorPatenteNonAmmessaVisibility = "hidden";
                                    }else{
                                        $errorPatenteNonAmmessaVisibility = "visible";
                                    }?>
                            <?php endif; ?>
                        </p>
                    </fieldset>

                    <!--Creazione del terzo fieldset contenente i dati della carta-->
                    <fieldset>
                        <legend>Dettagli pagamento</legend>
                        <?php
                            /*Se l'utente noleggia un veicolo per la prima volta, dovrà inserire i dati della carta,
                            altrimenti verranno visualizzati nelle caselle i dati che aveva già precedentemente inserito
                            senza opzione di modifica (infatti sono readonly)*/ 
                            if(!$carta) {
                                $dataScadenzaCarta = null;
                                echo "<p>
                                        <label for=\"numeroCarta\">N° carta
                                            <input type=\"text\" pattern=\"[0-9]*\" minlength=\"16\" maxlength=\"16\" name=\"numeroCarta\" id=\"numeroCarta\" value=\"$numeroCarta\" required/>
                                        </label>
                                    </p>";
                                echo "<p>
                                        <label for=\"scadenzaCarta\">Data di scadenza carta
                                            <!--Se l'utente inserisce una carta già scaduta verrà richiamata la funzione checkScadenzaCarta()-->
                                            <input type=\"month\" name=\"scadenzaCarta\" id=\"scadenzaCarta\" value=\"$dataScadenzaCarta\" onfocusout=\"checkScadenzaCarta()\" required/>
                                        </label>
                                    </p>";
                                echo "<p>
                                            <label for=\"codiceCarta\">Codice carta
                                                <input type=\"text\" pattern=\"[0-9]*\" minlength=\"3\" maxlength=\"3\" name=\"codiceCarta\" id=\"codiceCarta\" value=\"$codiceCarta\" required/>
                                            </label>
                                    </p>";
                                }
                                else{
                                    echo "<p>
                                        <label for=\"numeroCarta\">N° carta
                                            <input type=\"text\" pattern=\"[0-9]*\" minlength=\"16\" maxlength=\"16\" name=\"numeroCarta\" id=\"numeroCarta\" value=\"$numeroCarta\" readonly/>
                                        </label>
                                        </p>";
                                    echo "<p>
                                        <label for=\"scadenzaCarta\">Data di scadenza carta
                                            <input type=\"month\" name=\"scadenzaCarta\" id=\"scadenzaCarta\" value=\"$dataScadenzaCarta\" readonly/>
                                        </label>
                                        </p>";
                                    echo "<p>
                                            <label for=\"codiceCarta\">Codice carta
                                                <input type=\"text\" pattern=\"[0-9]*\" minlength=\"3\" maxlength=\"3\" name=\"codiceCarta\" id=\"codiceCarta\" value=\"$codiceCarta\" readonly/>
                                            </label>
                                        </p>";
                                }
                        ?>
                        
                    </fieldset>

                    <!--Creazione del quarto fieldset contenente i dettagli del noleggio del veicolo-->
                    <fieldset>
                        <legend>Acquisto</legend>
                        <p>
                            <!--Creazione dell'input che contiene la data di inzio del noleggio da parte dell'utente.
                            Quando l'utente clicca su una data, viene richiamata la funzione checkDateNoleggio() che riceve
                            come parametro il prezzo del veicolo da voler noleggiare-->
                            <label for="dataInizio">Data di inizio noleggio
                                <input type="date" name="dataInizio" id="dataInizio" min="<?php echo date("Y-m-d");?>" value="<?php echo date("Y-m-d");?>" onchange="checkDateNoleggio(<?php echo $prezzo;?>)" required/>
                            </label>
                        </p>
                        <p>
                            <!--Creazione dell'input che contiene la data di fine del noleggio da parte dell'utente.
                            Quando l'utente clicca su una data, viene richiamata la funzione checkDateNoleggio() che riceve
                            come parametro il prezzo del veicolo da voler noleggiare-->
                            <label for="dataFine">Data di fine noleggio
                                <input type="date" name="dataFine" id="dataFine" min="<?php echo date("Y-m-d");?>" value="<?php echo date("Y-m-d");?>" onchange="checkDateNoleggio(<?php echo $prezzo;?>)" required/>
                            </label>
                        </p>
                        <p>
                            <!--Viene stampato il costo totale del noleggio -->
                            Prezzo totale:
                                <span id="prezzoTotale"><?php echo $prezzo;?></span>
                            €
                        </p>
                    </fieldset>
                    <!--Creazione del pulsante di invio del form che verrà disabilitato se l'utente non ha la patente necessaria per il noleggio del veicolo -->
                    <input type="submit" name="invioAcquisto" id="invioAcquisto" value="Invia" <?php echo ($errorPatenteNonAmmessaVisibility === "visible") ? "disabled" : ""; ?>>
                    <!--Errori che saranno visibili: se la carta è scaduta o se si seleziona una data di inizio successiva a quella di fine noleggio-->
                    <p class="errorScadenzaCarta" id="errorScadenzaCarta">La carta è scaduta!</p>
                    <p class="errorDataFine" id="errorDataFine">La data di fine deve essere successiva a quella di inizio</p>
                    <!--Errore della patente non sufficiente-->
                    <div id="errorPatenteNonAmmessa" style="visibility: <?php echo $errorPatenteNonAmmessaVisibility; ?>">
                        La patente in possesso non è sufficiente!
                    </div>
                </form>
            </div>
        </div>
    </section>
        <!--Inclusione del footer-->                       
        <?php include("footer.php");?>
</body>
</html>

<?php
    //Questa funzione permette di inserire i dati del noleggio del veicolo appena noleggiato da un utente
    function insertNoleggio($email, $targa, $dataInizio, $dataFine) {
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connettersi al database: ' . pg_last_error());
        //Converte le date di inizio e fine del noleggio del nuovo utente dal formato "GG-MM-AAAA" a "AAAA-MM-GG" richiesto dal database
        $dataInizio = date("Y-m-d", strtotime($dataInizio));
        $dataFine = date("Y-m-d", strtotime($dataFine));
        //Query al db che inserisce il noleggio nella tabella Noleggio
        $sql = "INSERT INTO NOLEGGIO(utente, veicolo, data_inizio, data_fine) VALUES ($1, $2, $3, $4)";
        $prep = pg_prepare($db, "insertNoleggio", $sql);
        $ret = pg_execute($db, "insertNoleggio", array($email, $targa, $dataInizio, $dataFine));

        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false;
        } else {
            return true;
        }
    }

    //Funzione che permette l'inserimento dei dati della carta perché quando l'utente si è registrato non li inserisce direttamente
    function insertCarta($email, $numeroCarta, $dataScadenzaCarta, $codiceCarta){
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connettersi al database: ' . pg_last_error());
        //Alla data di scadenza della carta si aggiunge un giorno a caso per rispettare il formato del db
        $dataScadenzaCarta = $dataScadenzaCarta . "-01";
        $dataScadenzaCarta = date('Y-m-d', strtotime($dataScadenzaCarta));
        //Query al db per aggiornare i dati dell'utente sugli attributi numeroCarta, dataScadenzaCarta e codiceCarta
        $sql = "UPDATE UTENTE SET numero_carta = $2, data_scadenza_carta = $3, codice_carta = $4 WHERE email=$1";
        $prep = pg_prepare($db, "insertCarta", $sql);
        $ret = pg_execute($db, "insertCarta", array($email, $numeroCarta, $dataScadenzaCarta, $codiceCarta));
        if (!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false;
        } else {
            return true;
        }
    }

    //Funzione che permette di ricavare l'utente con quella specifica mail
    function getUser($email){
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        $sql = "SELECT * FROM UTENTE WHERE email=$1;";
        $prep = pg_prepare($db, "sqlUserData", $sql); 
        $ret = pg_execute($db, "sqlUserData", array($email));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            if ($row = pg_fetch_assoc($ret)){ 
                return $row;
            }
            else{
                return false;
            }
        }
    }	

    /*Questa funzione controlla che l'auto non venga noleggiata da due utenti differenti negli stessi giorni*/
    function checkNoleggio($targa, $dataInizio, $dataFine) {
        require "db.php";
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        /*Query al db per selezionare i veicoli con la specifica targa passata da parametro dalla tabella noleggio 
        con la data di fine maggiore della data corrente*/
        $sql = "SELECT * FROM NOLEGGIO WHERE veicolo=$1 AND data_fine >= CURRENT_DATE";
        $prep = pg_prepare($db, "checkNoleggio", $sql); 
        $ret = pg_execute($db, "checkNoleggio", array($targa));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            /*Sezione in cui viene prelevata la data_inizio e la data_fine di un eventuale noleggio
            di un veicolo che un altro utente ha effettuato*/
            while($noleggio = pg_fetch_assoc($ret)) { 
                $dataInizioNoleggio = $noleggio['data_inizio']; //In dataInizioNoleggio c'è la data di inizio del noleggio dell'eventuale auto già occupata
                $dataFineNoleggio = $noleggio['data_fine']; //In dataFineNoleggio c'è la data di fine del noleggio dell'eventuale auto già occupata

                /*1)Controllo se la data di inizio noleggio che il nuovo acquirente ha inserito è precedente o coincidente alla data 
                di fine noleggio dell'eventuale auto già occupata e se è successiva o coincidente alla data di inizio noleggio dell'eventuale auto già occupata.
                Se la condizione è vera significa che l'utente ha deciso di noleggiare un veicolo prima della data di fine noleggio del veicolo eventualmente già noleggiato
                e dopo la data di fine noleggio del veicolo eventualmente già noleggiato.
                Di conseguenza parte un alert dicendo che l'auto è già occupata dal giorno dataInizioNoleggio al giorno dataFineNoleggio*/
                if(($dataInizio <= $dataFineNoleggio) && ($dataInizio >= $dataInizioNoleggio)) {
                    echo "<script> alert(\"Questo veicolo è già occupato dal $dataInizioNoleggio al $dataFineNoleggio\"); </script>";
                    return false;
                } 
                /*2)Controllo se la data di fine noleggio che il nuovo acquirente ha inserito è precedente o coincidente alla data 
                di fine noleggio dell'eventuale auto già occupata e se è successiva o coincidente alla data di inizio noleggio dell'eventuale auto già occupata.
                Se la condizione è vera significa che l'utente ha deciso di noleggiare un veicolo prima della data di inizio noleggio del veicolo eventualmente già noleggiato
                e dopo la data di inizio noleggio del veicolo eventualmente già noleggiato.
                Di conseguenza parte un alert dicendo che l'auto è già occupata dal giorno dataInizioNoleggio al giorno dataFineNoleggio*/   
                else if(($dataFine <= $dataFineNoleggio) && ($dataFine >= $dataInizioNoleggio)) {
                    echo "<script> alert(\"Questo veicolo è già occupato dal $dataInizioNoleggio al $dataFineNoleggio\"); </script>";
                    return false;
                } 
                /*3)Controllo se la data di fine noleggio che il nuovo acquirente ha inserito è precedente o coincidente alla data 
                di fine noleggio dell'eventuale auto già occupata e se la data di inizio noleggio che il nuovo acquirente ha inserito
                è successiva o coincidente alla data di inizio noleggio dell'eventuale auto già occupata.
                Se la condizione è vera significa che l'utente ha deciso di noleggiare un veicolo nei giorni compresi o proprio gli stessi
                tra la data di inizio noleggio dell'eventuale veicolo già occupato e la data di fine noleggio.
                Di conseguenza parte un alert dicendo che l'auto è già occupata dal giorno dataInizioNoleggio al giorno dataFineNoleggio*/
                else if(($dataFine <= $dataFineNoleggio) && ($dataInizio >= $dataInizioNoleggio)) {
                    echo "<script> alert(\"Questo veicolo è già occupato dal $dataInizioNoleggio al $dataFineNoleggio\"); </script>";
                    return false;
                } 
                /*4)Controllo se la data di fine noleggio che il nuovo acquirente ha inserito è successiva o coincidente alla data 
                di fine noleggio dell'eventuale auto già occupata e se la data di inizio noleggio che il nuovo acquirente ha inserito
                è precedente o coincidente alla data di inizio noleggio dell'eventuale auto già occupata.
                Se la condizione è vera significa che l'utente ha deciso di noleggiare un veicolo nei giorni che comprendono o proprio gli stessi
                la data di inizio noleggio dell'eventuale veicolo già occupato e la data di fine noleggio.
                Di conseguenza parte un alert dicendo che l'auto è già occupata dal giorno dataInizioNoleggio al giorno dataFineNoleggio*/
                else if(($dataFine >= $dataFineNoleggio) && ($dataInizio <= $dataInizioNoleggio)) {
                    echo "<script> alert(\"Questo veicolo è già occupato dal $dataInizioNoleggio al $dataFineNoleggio\"); </script>";
                    return false;
                }
            }
            return true;
        }
    }
?>
