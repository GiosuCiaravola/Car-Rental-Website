<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ReservedPage.css">
    <link rel="icon" href="Immagini autonoleggio\logo bianco.png">
    <title>Pagina riservata</title>
</head>
<body>

<?php
    //Inizio della sessione
    session_start();
    $carta = false;
    //Si ottengono le informazioni dell'utente dalla mail della sessione corrente
    if($row = getUser($_SESSION["email"])) {
        /*Mettiamo in nome, cognome...gli attuali dati dell'utente loggato per visualizzarli 
        nelle caselle di testo ed eventualmente effettuare la modifica */
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $nPatente = $row['numero_patente'];
        $pAuto = $row['patente_auto'];
        $pMoto = $row['patente_moto'];
        $nCarta = $row['numero_carta'];
        /*Salvataggio della data di scadenza della carta nel formato YYYY-MM-DD */ 
        $dScadenzaConG = $row['data_scadenza_carta'];
        if($dScadenzaConG == null)
            $dScadenza = $dScadenzaConG;
        else
            /*Con la funzione strtotime() convertiamo la data con formato YYYY-MM-DD nel formato YYYY-MM proprio perché le carte sono in questo formato*/
            $dScadenza = date("Y-m", strtotime($dScadenzaConG));
            $vCodice = $row['codice_carta'];
        /*Se l'attuale numero di carta non è null allora significa che l'utente ha inserito il numero di carta per effettuare un pagamento 
        e quindi ne è in possesso*/
        if($nCarta != null)
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
            $pagetitle = "<h1>Benvenuto nella tua area riservata $nome</h1>";
            include("header.php");
        ?>
    </section>

    <div class="maincontent">
        <!--Creazione del container che conterrà le informazioni dell'utente-->
		<div class="container">
                <!--Colonna che conterrà le informazioni personali-->
                <div class="leftcolumn" id="leftcolumn">
					<h2>Nome</h2><p><?php echo $nome?></p>
                    <h2>Cognome</h2><p><?php echo $cognome?></p>
                    <h2>E-mail</h2><p><?php echo $_SESSION['email']?></p>
                </div>
                <!--Colonna che conterrà le informazioni personali riguardo la patente-->
                <div class="rightcolumn" id="rightcolumn">
                    <h2>N°Patente</h2><p><?php echo $nPatente?></p>
                    <h2>Patente auto</h2><p><?php echo $pAuto?></p>
                    <h2>Patente moto</h2><p><?php echo $pMoto?></p>
                </div>
                <!--Colonna che conterrà le informazioni personali riguardo i dati di pagamento-->
                <div class="paymentcolumn">
                    <?php
                        if($carta) {
                            echo "<h2>N° carta</h2><p>$nCarta</p>";
                            echo "<h2>Scadenza Carta</h2><p>$dScadenza</p>";
                            echo "<h2>Codice carta</h2><p>$vCodice</p>";
                        }
                    ?>
                </div>
                <?php
                    /*Se la sessione riconosce che l'utente loggato è l'amministratore, appare sul lato destro del container
                    il pulsante di aggiunta o rimozione di un veicolo che solo lui è autorizzato a fare*/
                    if($_SESSION['email'] === "admin@gmail.com")
                        echo "<a href=\"ModifyVehiclePage.php\" id=\"modifybutton\"><button class=\"modifybutton\"><p>Modifica veicoli</p></button></a>";
                ?>
        </div>

        <?php
            if(!$_SESSION['email'] === "admin@gmail.com")
                echo "<a href=\"ModifyVehiclePage.php\"><button class=\"modifybutton\"><p>Modifica veicoli</p></button></a>";
            else {
                //Se l'utente non è l'admin allora verrà visualizzato lo storico dei noleggi dell'utente
                require "db.php";
                $email = $_SESSION['email'];
                $db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
                //Query al db che prende dalla tabella noleggio tutti i noleggi effettuati dall'utente ordinati per data_fine noleggio
                $sql = "SELECT * FROM NOLEGGIO WHERE utente = $1 ORDER BY data_fine DESC;";
                $prep = pg_prepare($db, "sqlNoleggi", $sql); 
                $ret = pg_execute($db, "sqlNoleggi", array($email));
                if(!$ret) {
                    echo "ERRORE QUERY: " . pg_last_error($db);
                }
                else{
                    /*Poiché devono essere mostrati tutti quanti i noleggi effettuati, 
                    si fa un while con tante nQuery al db quanti sono i noleggi effettuati*/
                    $nQuery = 0;        //Contatore per le query
                    $flag = false;      //Flag di controllo per capire se l'utente ha effettuato almeno un noleggio
                    while($noleggio = pg_fetch_assoc($ret)) {
                        $flag = true;   //La flag diventa true perché l'utente ha fatto almeno un noleggio
                        /*Si prendono dal singolo veicolo noleggiato l'auto, la data di inizio e quella di fine noleggio*/
                        $auto = $noleggio['veicolo'];
                        $dataInizio = $noleggio['data_inizio'];
                        $dataFine = $noleggio['data_fine'];
                        //Query al db per selezionare dalla tabella veicolo il nome e l'immagine del veicolo associato alla targa 
                        $sql = "SELECT nome, immagine FROM VEICOLO WHERE targa = $1;";
                        /*Concateniamo al nome della query il contatore precedentemente inizializzato 
                        perché il nome della query deve essere univoco*/
                        $prep = pg_prepare($db, "sqlVeicoloNoleggio".$nQuery, $sql);            
                        $qVeicolo = pg_execute($db, "sqlVeicoloNoleggio".$nQuery, array($auto));
                        if(!$qVeicolo) {
                            echo "ERRORE QUERY: " . pg_last_error($db);
                        }
                        else{
                            /*Se la query non ha dato errore si procede a estrarre le informazioni del veicolo quali l'immagine e il nome*/
                            $macchina = pg_fetch_assoc($qVeicolo);
                            $immagine = $macchina['immagine'];
                            $nome = $macchina['nome'];
                            /*Da qui si crea il div che conterrà tutte le informazioni del veicolo noleggiato
                            tra cui anche la data di inizio e fine noleggio*/
                            echo "<div class=\"vehicle\">
                                    <img src=\"$immagine\"/>
                                    <h1>$nome</h1>
                                    <h2>Data di inizio noleggio: </h2><p>$dataInizio</p>
                                    <h2>Data di fine noleggio: </h2><p>$dataFine</p>";
                                    /*Se la data di inizio del noleggio è successiva a quella attuale allora apparirà la scritta del
                                    "noleggio a breve" con colore arancione. Stessa cosa per gli altri controlli*/
                                    if($dataInizio > date('Y-m-d')) {
                                        echo "<p class=\"esito\" style=\"color: orange\">Inizio a breve</p>";
                                    } else if($dataFine >= date('Y-m-d')){
                                        echo "<p class=\"esito\" style=\"color: green\">In corso</p>";
                                    }
                                    else
                                        echo "<p class=\"esito\" style=\"color: red\">Terminato</p>";
                                    //Aggiornamento del contatore per differenziare le query
                                    $nQuery = $nQuery + 1;
                            echo "</div>";
                        }
                    }
                    if(!$flag) {
                        //Se l'utente non ha effettuato alcun noleggio appare la scritta "Non hai effettuato alcun noleggio. Fallo navigando nel nostro sito!"
                        echo "<div class=\"nonoleggio\"><p class=\"nonoleggio\">Non hai effettuato alcun noleggio. Fallo navigando nel nostro sito!</p></div>";
                    }
                }
            }
        ?>
    </div>
    <!--Inclusione del footer-->
    <?php include("footer.php");?>  
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