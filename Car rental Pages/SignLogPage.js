/*Funzione che controlla se la patente inserita è corretta rispettando il regex: 
se questo non dovesse avvenire viene mostrato l'errore "errorNumeroPatente" 
e disabilitato il pulsante di aggiornamento delle modifiche*/
function checkPatente() {
    var numeroPatente = document.getElementById("numeroPatente").value;
    var signUpButton = document.getElementById("signUpButton");
    signUpButton.disabled = false;

    var regexPatente = /^([A-Z]{2}[0-9]{7}[A-Z])|(^[U]1[A-Z0-9]{7}[A-Z])$/;
    if(!regexPatente.test(numeroPatente)){
        document.getElementById("errorNumeroPatente").style.visibility = "visible";
        signUpButton.disabled = true;
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
    var signUpButton = document.getElementById("signUpButton");
    signUpButton.disabled = false;

    if (patenteAuto === patenteMoto) {
        document.getElementById("errorTipoPatenti").style.visibility = "visible";
        signUpButton.disabled = true;
    } else {
        document.getElementById("errorTipoPatenti").style.visibility = "hidden";
    }
}

/*Controllo se la password e il reinserimento della password sono corretti.
Se così non fosse vengono visualizzati gli errori appositi*/
function checkPassword() {
    var password = document.getElementById("password").value;
    var repassword = document.getElementById("repassword").value;
    var signUpButton = document.getElementById("signUpButton");
    signUpButton.disabled = false;

    if(password != repassword) {
        document.getElementById("errorPassword").style.visibility = "visible";
        signUpButton.disabled = true;
    } else {
        document.getElementById("errorPassword").style.visibility = "hidden";
    }
}

//Alert che viene attivato se la registrazione del profilo è avvenuto con successo
function alertSignUpSucceded() {
    alert("Utente registrato con successo. Effettua il login!");
}

/*Funzione che permette di mostrare il div di registrazione e nascondere quello di login 
se si clicca sul pulsante "Registrati", inoltre il colore del pulsante cambia per indicare
che ci troviamo in quella sezione*/
function showSignUp(){
    var register = document.getElementById('signUpPage');
    var buttonregister = document.getElementById("showSignUpButton");
    buttonregister.style.background = "#434382";
    buttonregister.style.color = "white";
    register.style.visibility = "visible";
    var login = document.getElementById('loginPage');
    var buttonlogin = document.getElementById("showLoginButton");
    buttonlogin.style.background = "white";
    buttonlogin.style.color = "#172a1f";
    login.style.visibility = "hidden";
}

/*Funzione che permette di mostrare il div di login e nascondere quello di registrazione 
se si clicca sul pulsante "Login", inoltre il colore del pulsante cambia per indicare
che ci troviamo in quella sezione*/
function showLogin(){
    var login = document.getElementById('loginPage');
    var buttonlogin = document.getElementById("showLoginButton");
    buttonlogin.style.background = "#434382";
    buttonlogin.style.color = "white";
    login.style.visibility = "visible";
    var register = document.getElementById('signUpPage');
    var buttonregister = document.getElementById("showSignUpButton");
    buttonregister.style.background = "white";
    buttonregister.style.color = "#172a1f";
    register.style.visibility = "hidden";
}