/*Funzione che controlla il tipo di veicolo da inserire per mostrare l'apposita patente necessaria*/
function checkTipoVeicolo() {
    var tipoVeicolo = document.getElementById("tipoVeicolo").value;
    var patenteAuto = document.getElementById("patenteAuto");
    var patenteMoto = document.getElementById("patenteMoto");

    if(tipoVeicolo == "Auto") {
        patenteAuto.style.display = "block";
        patenteMoto.style.display = "none";
    } else {
        patenteAuto.style.display = "none";
        patenteMoto.style.display = "block";
    }
}

/*Funzione che controlla il formato giusto per la targa, sia di auto che di moto mostrando 
eventuali errori*/
function checkTarga() {
    var errorTargaAuto = document.getElementById("errorTargaAuto");
    var errorTargaMoto = document.getElementById("errorTargaMoto");
    var tipoVeicolo = document.getElementById("tipoVeicolo").value;
    var targa = document.getElementById("targa").value;
    var inserisci = document.getElementById("inserisci");
    inserisci.disabled = false;

    if(tipoVeicolo == "Auto") {
        var regex = /^[A-Z]{2}\d{3}[A-Z]{2}$/;
        if(!regex.test(targa)) {
            inserisci.disabled = true;
            errorTargaMoto.style.display = "none";
            errorTargaAuto.style.display = "block";
        } else {
            inserisci.disabled = false;
            errorTargaAuto.style.display = "none";
        }
    } else {
        var regex = /^[A-Z]{2}\d{5}$/;
        if(!regex.test(targa)) {
            inserisci.disabled = true;
            errorTargaMoto.style.display = "block";
            errorTargaAuto.style.display = "none";
        } else {
            inserisci.disabled = false;
            errorTargaMoto.style.display = "none";
        }
    }
}

/*Funzione che fa partire un alert di inserimento con successo del veicolo associato
alla targa passata come parametro alla funzione*/
function alertVeicoloInserito(targa) {
    alert("Veicolo "+targa+" inserito con successo!");
    window.location.href = "SingleVehiclePage.php?targa="+targa;
}

/*Funzione che fa partire un alert di rimozione con successo del veicolo associato
alla targa da rimuovere passata come parametro alla funzione*/
function alertVeicoloRimosso(targa) {
    alert("Veicolo "+targa+" rimosso con successo!");
}

/*Funzione che mostra il file inserito dall'admin*/
function updateFileName(input) {
    var fileNameElement = document.getElementById('fileselezionato');
    if (input.files.length > 0) {
      var fileName = input.files[0].name;
      fileNameElement.textContent = 'File selezionato: ' + fileName;
    } else {
      fileNameElement.textContent = '';
    }
}
  
  