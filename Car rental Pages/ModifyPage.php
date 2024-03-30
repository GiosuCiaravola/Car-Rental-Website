<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ModifyPage.css">
    <script src="ModifyPage.js"></script>
    <title>Modifica profilo</title>
</head>
<body>

<?php
    /*Inizio della sessione*/
    session_start();
    $carta = false;
    /*$_SESSION["email"] è una variabile di sessione in PHP utilizzata per memorizzare e 
    recuperare il valore dell'email dell'utente durante la sessione attiva
    e con la funzione getUser ritorniamo la riga dei dati associati all'utente con quella rispettiva mail*/
    if($row = getUser($_SESSION["email"])) {
        /*Mettiamo in vNome, vCognome...gli attuali dati dell'utente loggato per visualizzarli 
        nelle caselle di testo ed eventualmente effettuare la modifica */
        $vNome = $row['nome'];
        $vCognome = $row['cognome'];
        $vNPatente = $row['numero_patente'];
        $vPAuto = $row['patente_auto'];
        $vPMoto = $row['patente_moto'];
        $vNCarta = $row['numero_carta'];
        /*Salvataggio della data di scadenza della carta nel formato YYYY-MM-DD */ 
        $vDScadenzaConG = $row['data_scadenza_carta'];
        if($vDScadenzaConG == null)
            $vDScadenza = $vDScadenzaConG;
        else
            /*Con la funzione strtotime() convertiamo la data con formato YYYY-MM-DD nel formato YYYY-MM proprio perché le carte sono in questo formato*/
            $vDScadenza = date("Y-m", strtotime($vDScadenzaConG));      
            $vCodice = $row['codice_carta'];
        /*Se l'attuale numero di carta non è null allora significa che l'utente ha inserito il numero di carta per effettuare un pagamento 
        e quindi ne è in possesso*/
        if($vNCarta != null)
            $carta = true;
    } else {
        echo "<p>Impossibile recuperare dati utente</p>";
    }
?>

<?php
    /*Questa è la sezione di modifica dei dati in cui l'utente inserisce i dati che ha deciso di modificare; 
    c'è una flag di controllo per vedere se l'utente ha effettuato una modifica di almeno uno dei suoi dati.
    Se l'utente non ha modificato un dato, questo rimarrà sempre lo stesso*/
    $modificato = false;
	if(isset($_POST['nome'])) {
        $modificato = true;
        $nNome = $_POST['nome'];
    } else 
        $nNome = $vNome;
    if(isset($_POST['cognome'])) {
        $modificato = true;
        $nCognome = $_POST['cognome'];
    } else
        $nCognome = $vCognome;
    if(isset($_POST['numeroPatente'])) {
        $modificato = true;
        $nNPatente = $_POST['numeroPatente'];
    } else
        $nNPatente = $vNPatente;
    if(isset($_POST['patenteAuto'])) {
        $modificato = true;
        $nPAuto = $_POST['patenteAuto'];
    } else
        $nPAuto = $vPAuto;
    if(isset($_POST['patenteMoto'])) {
        $modificato = true;
        $nPMoto = $_POST['patenteMoto'];
    } else
        $nPMoto = $vPMoto;
    if(isset($_POST['numeroCarta'])) {
        $modificato = true;
        $nNCarta = $_POST['numeroCarta'];
    } else
        $nNCarta = $vNCarta;
    if(isset($_POST['scadenzaCarta'])) {
        $modificato = true;
        if($_POST['scadenzaCarta'] == null)
            $nDScadenza = null;
        else
            /*Concateniamo alla data di scadenza nel formato YYYY-MM un giorno qualunque per poterlo inserire nel db
            poiché postgress permette di inserire le date solo nel formato YYYY-MM-DD*/
            $nDScadenza = $_POST['scadenzaCarta']."-01";
    } else
        if($vDScadenza == null)
            $nDScadenza = null;
        else
            $nDScadenza = $vDScadenza."-01";
    if(isset($_POST['codiceCarta'])) {
        $modificato = true;
        $nCodice = $_POST['codiceCarta'];
    } else
        $nCodice = $vCodice;

    /*Se è avvenuta la modifica di almeno un dato allora viene effettuata la query al db per poter inserire 
    le modifiche all'utente in sessione con quella specifica mail*/
    if($modificato) {
        require "db.php";
        //Connessione al db
        $userEmail = $_SESSION["email"];
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        /*Query al db per poter modificare la tabella utente di quello specifico utente
        dando agli attributi i nuovi valori di modifica effettuata*/
        $sql = "UPDATE UTENTE SET nome=$2, cognome=$3, numero_patente=$4, patente_auto=$5, patente_moto=$6, numero_carta=$7, data_scadenza_carta=$8, codice_carta=$9 WHERE email=$1;";
        $prep = pg_prepare($db, "sqlUpdateData", $sql); 
        $ret = pg_execute($db, "sqlUpdateData", array($userEmail, $nNome, $nCognome, $nNPatente, $nPAuto, $nPMoto, $nNCarta, $nDScadenza, $nCodice));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            /*Se la modifica è avvenuta con successo viene richiamata la funzione alertModifySucceded()*/
            $_SESSION["nome"] = $nNome;
            echo '<script>';
			echo 'alertModifySucceded();'; 
			echo '</script>';
        }
    }
?>
    <!--Inclusione della sidebar-->
    <?php include("leftsidebar.php");?>

    <section class="main">
        <?php 
            //Creazione del titolo della pagina per poi richiamare l'header
            $pagetitle = "<h1>Modifica i tuoi dati $user</h1>";
            include("header.php");
        ?>
        <!--Creazione del container che conterrà il form per permettere all'utente di poter inserire i nuovi dati di modifica.
        Di default apparirà l'attuale valore di ogni campo, recuperati all'inizio tramite $_POST ma comunque sovrascrivibili-->
        <div class="container">
            <div class="modifyPage" id="modifyPage">
                <form id="modifyForm" method="post" action="ModifyPage.php">
                    <p>
                        <label for="nome">Nome
                            <input type="text" name="nome" id="nome" value="<?php echo $vNome?>" required/>
                        </label>
                    </p>
                    <p>
                        <label for="cognome">Cognome
                            <input type="text" name="cognome" id="cognome" value="<?php echo $vCognome?>" required/>
                        </label>
                    </p>
                    <p>
                        <label for="numeroPatente">N° patente
                            <input type="text" minlength="10" maxlength="10" name="numeroPatente" id="numeroPatente" value="<?php echo $vNPatente?>" onfocusout="checkPatente()" required/>
                        </label>
                    </p>
                    <p>
                        <label for="patenteAuto">Patente auto
                            <!--Tag select per patenteAuto che alla selezione di uno dei campi della 
                            tendina fa partire la funzione checkTipoPatente()-->
                            <select id="patenteAuto" name="patenteAuto" onchange="checkTipoPatente()" required>
                                <option value="B">B</option>
                                <option value="Nessuna" <?php if($vPAuto === "Nessuna") echo "selected";?>>Nessuna</option>
                            </select>
                        </label>
                    </p>
                    <p>
                        <label for="patenteMoto">Patente moto
                            <!--Tag select per patenteMoto che alla selezione di uno dei campi della 
                            tendina fa partire la funzione checkTipoPatente()-->
                            <select id="patenteMoto" name="patenteMoto" onchange="checkTipoPatente()"required>
                                <option value="Nessuna">Nessuna</option>
                                <option value="AM" <?php if($vPMoto === "AM") echo "selected";?>>AM</option>
                                <option value="A1" <?php if($vPMoto === "A1") echo "selected";?>>A1</option>
                                <option value="A2" <?php if($vPMoto === "A2") echo "selected";?>>A2</option>
                                <option value="A" <?php if($vPMoto === "A") echo "selected";?>>A</option>
                            </select>
                        </label>
                    </p>
                    <?php
                        /*Poiché durante la registrazione l'utente non inserisce i dati della carta ma lo fa solamente al
                        primo pagamento di un noleggio, se ha effettuato almeno un noleggio allora apparirà anche la 
                        sezione di modifica dei dati della carta*/ 
                        if($carta) {
                            echo    "<p>
                                        <label for=\"numeroCarta\">N° carta
                                            <input type=\"text\" pattern=\"[0-9]*\" minlength=\"16\" maxlength=\"16\" name=\"numeroCarta\" id=\"numeroCarta\" value=\"$vNCarta\" required/>
                                        </label>
                                    </p>";
                            echo    "<p>
                                        <label for=\"scadenzaCarta\">Data di scadenza carta
                                            <input type=\"month\" name=\"scadenzaCarta\" id=\"scadenzaCarta\" value=\"$vDScadenza\" onchange=\"checkScadenzaCarta()\" required/>
                                        </label>
                                    </p>";
                            echo    "<p>
                                        <label for=\"codiceCarta\">Codice carta
                                            <input type=\"text\" pattern=\"[0-9]*\" minlength=\"3\" maxlength=\"3\" name=\"codiceCarta\" id=\"codiceCarta\" value=\"$vCodice\" onfocusout=\"checkCodiceCarta()\" required/>
                                        </label>
                                    </p>";
                        }
                    ?>
                    <div class="modifyButton">
						<input type="submit" name="modify" id="modifyButton" value="Salva modifiche"/>
				    </div>
                </form>
                <!--Sezione degli errori gestita con l'apposito ModifyPage.js-->
                <p class="errorNumeroPatente" id="errorNumeroPatente">Il N° patente inserito non è corretto!</p>
				<p class="errorTipoPatenti" id="errorTipoPatenti">Devi inserire almeno un tipo di patente!</p>
				<p class="errorScadenzaCarta" id="errorScadenzaCarta">La carta è scaduta!</p>
            </div>
            <div class="selection">
                <!--Pulsante che permette all'utente di annullare le modifiche e portarlo nell'area riservata-->
                <a href="ReservedPage.php"><button class="showSignUpButton"><p>Annulla modifiche</p></button></a>
            </div>
        </div>
        <!--Inclusione del footer-->
        <?php include("footer.php");?>
    </section>
</body>

</html>

<?php
    /*Funzione che data la mail preleva i dati dell'utente con una rispettiva email*/
    function getUser($email){
        require "db.php";
        //Connessione al db
        $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
        /*Query al db che seleziona tutti gli utenti dalla tabella utente dove l'email è quella passata come parametro alla funzione*/
        $sql = "SELECT * FROM UTENTE WHERE email=$1;";
        $prep = pg_prepare($db, "sqlUserData", $sql); 
        $ret = pg_execute($db, "sqlUserData", array($email));
        if(!$ret) {
            echo "ERRORE QUERY: " . pg_last_error($db);
            return false; 
        }
        else{
            /*Con questo prendiamo il risultato della query che ritorna un array associativo rappresentante la riga dei dati dell'utente*/
            if ($row = pg_fetch_assoc($ret)){ 
                return $row;
            }
            else{
                return false;
            }
        }
    }	
?>