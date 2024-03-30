//Funzione che controlla se la carta è scaduta
function checkScadenzaCarta() {
    var scadenzaCarta = document.getElementById("scadenzaCarta").value;
    var SendButton = document.getElementById("invioAcquisto");
    SendButton.disabled = false;

    var dataCorrente = new Date(); 
    var [annoScadenza, meseScadenza] = scadenzaCarta.split('-');  
    var dataScadenza = new Date(annoScadenza, meseScadenza - 1);
    if(dataScadenza < dataCorrente) {
        document.getElementById("errorScadenzaCarta").style.visibility = "visible";
        SendButton.disabled = true;
    } else {
        document.getElementById("errorScadenzaCarta").style.visibility = "hidden";
    }
}

//Funzione che controlla se la data di inizio è precedente alla data di fine e fa il calcolo del costo totale di noleggio
function checkDateNoleggio(prezzo) {
    var dataInizio = new Date(document.getElementById("dataInizio").value);
    var dataFine = new Date(document.getElementById("dataFine").value);
    var SendButton = document.getElementById("invioAcquisto");
    SendButton.disabled = false;

    if (dataFine < dataInizio) {
        document.getElementById("errorDataFine").style.visibility = "visible";
        SendButton.disabled = true;
    } else {
        document.getElementById("errorDataFine").style.visibility = "hidden";
    }
    /*Se è presente una data di inizio noleggio, una data di fine noleggio e la data di fine è successiva alla data di inizio
    si procede con il calcolo del costo totale di noleggio*/
    if (dataInizio && dataFine && dataFine >= dataInizio) {
        /*Calcolo della differenza tra le date in millisecondi, conversione della differenza in giorni, 
        calcolo del prezzo totale moltiplicando il prezzo giornaliero per la differenza in giorni 
        e aggiungendo il prezzo giornaliero; infine viene impostato il valore del prezzo totale nella pagina RentPage.php*/ 
        var differenzaInMillisecondi = dataFine - dataInizio;
        var differenzaInGiorni = Math.floor(differenzaInMillisecondi / (1000 * 60 * 60 * 24));
        var prezzoTotale = (prezzo * differenzaInGiorni)+ prezzo;
        document.getElementById("prezzoTotale").textContent = prezzoTotale.toFixed(2);
    } else {
        //Verrà semplicemente mostrato il prezzo giornaliero
        document.getElementById("prezzoTotale").textContent = prezzo.toFixed(2);
    }
}

//Funzione che fa partire un alert se il noleggio è stato effettuato con successo riportando l'utente alla sua area riservata
function noleggioEffettuato() {
    alert("Pagamento effettuato con successo!");
    window.location.href = "ReservedPage.php";
}
