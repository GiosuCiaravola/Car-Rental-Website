//Alert che viene attivato se le modifiche del profilo sono avvenute con successo
function alertModifySucceded() {
    alert("Modifiche effettuate con successo!");
    window.location.href = "ReservedPage.php";
}

/*Funzione che controlla se la patente inserita è corretta rispettando il regex: 
se questo non dovesse avvenire viene mostrato l'errore "errorNumeroPatente" 
e disabilitato il pulsante di aggiornamento delle modifiche*/
function checkPatente() {
    var numeroPatente = document.getElementById("numeroPatente").value;
    var modifyButton = document.getElementById("modifyButton");
    modifyButton.disabled = false;

    var regexPatente = /^([A-Z]{2}[0-9]{7}[A-Z])|(^[U]1[A-Z0-9]{7}[A-Z])$/;
    if(!regexPatente.test(numeroPatente)){
        document.getElementById("errorNumeroPatente").style.visibility = "visible";
        modifyButton.disabled = true;
        } else {
        document.getElementById("errorNumeroPatente").style.visibility = "hidden";
    }
}

/*Funzione che controlla se i due tipi di patenti sono uguali: l'unico caso in cui questo accade
è quando l'utente seleziona per entrambi i tipi di patente l'opzione "nessuna". 
Scatta l'errore perché vogliamo che l'utente abbia almeno una patente, che sia auto o moto*/
function checkTipoPatente() {
    var patenteAuto = document.getElementById("patenteAuto").value;
    var patenteMoto = document.getElementById("patenteMoto").value;
    var modifyButton = document.getElementById("modifyButton");
    modifyButton.disabled = false;

    if (patenteAuto === patenteMoto) {
        document.getElementById("errorTipoPatenti").style.visibility = "visible";
        modifyButton.disabled = true;
    } else {
        document.getElementById("errorTipoPatenti").style.visibility = "hidden";
    }
}

/*Funzione che controlla se la data di scadenza della carta è precedente alla data corrente.
Il controllo è fatta solo su anno e mese e se si seleziona una carta con lo stesso mese di quello corrente 
(o precedente) viene reso visibile l'errore "errorScadenzaCarta" e disabilitato il pulsante di conferma modifica*/
function checkScadenzaCarta() {
    var scadenzaCarta = document.getElementById("scadenzaCarta").value;
    var modifyButton = document.getElementById("modifyButton");
    modifyButton.disabled = false;
    /*Creazione di un nuovo oggetto di classe "Date" e successivo ottenimento del mese e dell'anno della variabile scadenzaCarta*/
    var dataCorrente = new Date(); 
    var [annoScadenza, meseScadenza] = scadenzaCarta.split('-');
    /*Il mese viene decrementato di uno perché i mesi in js sono indicizzati da 0 a 11 */ 
    var dataScadenza = new Date(annoScadenza, meseScadenza - 1);
    if(dataScadenza < dataCorrente) {
        document.getElementById("errorScadenzaCarta").style.visibility = "visible";
        modifyButton.disabled = true;
    } else {
        document.getElementById("errorScadenzaCarta").style.visibility = "hidden";
    }
}

