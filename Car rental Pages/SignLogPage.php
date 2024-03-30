<!DOCTYPE html>
<html>
<head>
    <script src="SignLogPage.js"></script>
	<link rel="icon" href="Immagini autonoleggio\logo bianco.png">
	<link rel="stylesheet" href="SignLogPage.css">
	<link rel="icon" href="Immagini autonoleggio\logo bianco.png">
	<title>Accedi o Registrati</title>
</head>
<body>
<?php
	/*Quello che questo echo fa è richiamare uno script con un metodo ovvero window.onload 
	che permette di mostrare "showLogin" (div dedicato al login) quando si arriva su questa pagina*/
	echo "<script>
			window.onload = showLogin; 
		</script>";
	//Visibilità degli errori sulla mail e utente inizialmente nascosti
	$errorMail = "hidden";
	$errorUtente = "hidden";
	//Preleviamo le informazioni dall'utente che si sta loggando/registrando
	if(isset($_POST['nome']))
		$nome = $_POST['nome'];
	else
		$nome = "";
    if(isset($_POST['cognome']))
		$cognome = $_POST['cognome'];
	else
		$cognome = "";
    if(isset($_POST['numeroPatente']))
		$numeroPatente = $_POST['numeroPatente'];
	else
		$numeroPatente = "";
    if(isset($_POST['patenteAuto']))
		$patenteAuto = $_POST['patenteAuto'];
	else
		$patenteAuto = "";
    if(isset($_POST['patenteMoto']))
		$patenteMoto = $_POST['patenteMoto'];
	else
		$patenteMoto = "";
	if(isset($_POST['email']))
		$email = $_POST['email'];
	else
		$email = "";
	if(isset($_POST['password']))
		$pass = $_POST['password'];
	else
		$pass = "";
	if(isset($_POST['repassword']))
		$repassword = $_POST['repassword'];
	else
		$repassword = "";

	//Qui effettuiamo il controllo sulla mail
	if($email != "") {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$domain = explode("@", $email)[1];
			if(checkdnsrr($domain, "MX")) {
				//Richiamiamo la funzione userExist() che riceve come parametro la mail dell'utente in sessione
				if(userExist($email)){
					//Se l'utente esiste già viene visualizzato l'errore "errorUtente ricaricando la pagina mostrando il div di registrazione"
					$errorUtente = "visible";
					echo "<script>
							window.onload = showSignUp; 
						</script>";
				} 
				else{
					//Se l'utente non esiste allora viene fatto l'inserimento al db
					if(insertUtente($nome, $cognome, $numeroPatente, $patenteAuto, $patenteMoto, $email, $pass)){
						//Alert di registrazione avvenuta con successo
						echo "<script>
								alertSignUpSucceded(); 
							</script>";
						$nome = "";
						$cognome = "";
						$numeroPatente = "";
						$patenteAuto = "";
						$patenteMoto = "";
						$email = "";
						$pass = "";
						$repassword = "";
					}
					else{
						echo "<p> Errore durante la registrazione. Riprova</p>";
					}
				}
			} else {
				//Se il dominio della mail non esiste viene visualizzato l'errore errorMail e ricaricata la pagina mostrando il div di registrazione"
				$errorMail = "visible";
				echo '<script>';
				echo 'window.onload = showSignUp;'; 
				echo '</script>';
			}
		} else {
			$errorMail = "visible";
			echo '<script>';
			echo 'window.onload = showSignUp;'; 
			echo '</script>';
		}
	}
?>

<?php
	//Sezione dedicata al login con gli errori sul login inizialmente nascosti
	$errorUtenteLogin = "hidden";
	$errorPasswordLogin = "hidden";
	if(isset($_POST['loginEmail']))
		$loginEmail = $_POST['loginEmail'];
	else
		$loginEmail = "";
	if(isset($_POST['loginPassword']))
		$loginPassword = $_POST['loginPassword'];
	else
		$loginPassword = "";
	/*Se loginEmail non è vuoto chiama la funzione get_pwd() che controlla se l'username esiste nel DB. 
	Se esiste, restituisce la password (hash), altrimenti restituisce false.*/
	if($loginEmail != "") {
		$hash = getPwd($loginEmail);
		if(!$hash){
			$errorUtenteLogin = "visible";
		}
		else{
			//Una volta restituita la password, c'è il controllo se l'hash e la loginPassword dell'utente sono corrette
			if(password_verify($loginPassword, $hash)){
				//Ottenimento del nome a partire dalla mail di login dell'utente loggato
				$nome = getName($loginEmail);
				session_start();
				$_SESSION['nome']=$nome;
				$_SESSION['email']=$loginEmail;
				//Una volta che l'utente ha fatto il login viene reindirizzato alla mainpage
				header("Location: "."MainPage.php");
				exit();
			}
			else{
				//Se l'hash e la password non coincidono allora viene visualizzato l'errore
				$errorPasswordLogin = "visible";
			}
		}
	}
?>

	<div class="external">
		<!--Inclusione della sidebar-->
		<?php include("leftsidebar.php");?>
		<div class="mainContent">
			<?php 
			    //Creazione del titolo della pagina per poi richiamare l'header
				$pagetitle = "<h1>Registrati o effettua l'accesso</h1>";
				include("header.php");
			?>

			<!--Container dei div di login e registrazione-->
			<div class="container">
				<!--Div dedicato ai pulsanti di login e di registrazione: in entrambi i casi vengono richiamate due funzioni apposite-->
				<div class="selection">
					<button id="showSignUpButton" onclick="showSignUp()">Registrati</button>
					<button id="showLoginButton" onclick="showLogin()">Accedi</button>
				</div>
				<!--Div dedicato alla pagina di registrazione con tutti i campi obbligatori e che conservano il valore 
				se dovessero esserci errori o si cambia div premendo i due pulsanti-->
				<div class="signUpPage" id="signUpPage">
					<form id="signUpForm" method="post" action="SignLogPage.php">
					<p>
						<label for="nome">Nome
							<input type="text" name="nome" id="nome" value="<?php echo $nome?>" required/>
						</label>
					</p>
					<p>
						<label for="cognome">Cognome
							<input type="text" name="cognome" id="cognome" value="<?php echo $cognome?>" required/>
						</label>
					</p>
					<p>
						<!--Se il numero di patente inserito non è corretto, appena si esce fuori dalla casella di testo viene richiamata la funzione apposita-->
						<label for="numeroPatente">N° patente
							<input type="text" minlength="10" maxlength="10" name="numeroPatente" id="numeroPatente" value="<?php echo $numeroPatente?>" onfocusout="checkPatente()" required/>
						</label>
					</p>
					<p>
						<label for="patenteAuto">Patente auto
							<!--Creazione del select con le opzioni per le patenti dell'auto, appena si clicca su un'opzione
							viene richiamata la funzione apposita-->
							<select id="patenteAuto" name="patenteAuto" onchange="checkTipoPatente()" required>
								<option value="B">B</option>
								<option value="Nessuna" <?php if($patenteAuto === "Nessuna") echo "selected";?>>Nessuna</option>
							</select>
						</label>
					</p>
					<p>
						<!--Creazione del select con le opzioni per le patenti della moto, appena si clicca su un'opzione
						viene richiamata la funzione apposita-->
						<label for="patenteMoto">Patente moto
							<select id="patenteMoto" name="patenteMoto" value="<?php echo $patenteMoto?>" onchange="checkTipoPatente()"required>
								<option value="Nessuna">Nessuna</option>
								<option value="AM" <?php if($patenteMoto === "AM") echo "selected";?>>AM</option>
								<option value="A1" <?php if($patenteMoto === "A1") echo "selected";?>>A1</option>
								<option value="A2" <?php if($patenteMoto === "A2") echo "selected";?>>A2</option>
								<option value="A" <?php if($patenteMoto === "A") echo "selected";?>>A</option>
							</select>
						</label>
					</p>	
					<p>
						<label for="email">E-mail
							<input type="email" name="email" id="email" value="<?php echo $email?>" required/>
						</label>
					</p>
					<p>
						<label for="password">Password
							<input type="password" name="password" id="password" value="<?php echo $pass?>" required/>
						</label>
					</p>
					<p>
						<!--Creazione del campo "ripeti password" dove se le due password non coincidono viene richiamata la funzione di controllo apposita-->
						<label for="repassword">Ripeti la password
							<input type="password" name="repassword" id="repassword" value="<?php echo $repassword?>" onmousemove="checkPassword()" onfocusout="checkPassword()" required/>
						</label>
					</p>
					<div class="signUpButton">
						<input type="submit" name="registra" id="signUpButton" value="Registrati"/>
					</div>
					</form>
					<!--Errori di registrazione inizialmente nascosti ma che saranno visibili se si verifica un errore-->
					<p class="errorNumeroPatente" id="errorNumeroPatente">Il N° patente inserito non è corretto!</p>
					<p class="errorTipoPatenti" id="errorTipoPatenti">Devi inserire almeno un tipo di patente!</p>
					<p class="errorEmail" style="visibility: <?php echo $errorMail?>">L'e-mail inserita non è valida!</p>
					<p class="errorPassword" id="errorPassword">Le password inserite non corrispondono!</p>
				</div>
				<p class="errorUtente" style="visibility: <?php echo $errorUtente?>">L'utente con e-mail <?php echo $email?> è già esistente!</p>
				<!--Div dedicato al login-->
				<div class="loginPage" id="loginPage">
					<form method="post" action="SignLogPage.php">
						<p>
							<label for="loginEmail">E-mail
								<input type="text" name="loginEmail" id="loginEmail" value="<?php echo $loginEmail?>" required/>
							</label>
						</p>
						<p>
							<label for="loginPassword">Password
								<input type="password" name="loginPassword" id="loginPassword" required/>
							</label>
						</p>
						<p>
							<input type="submit" name="invia" value="Login"/>
						</p>
					</form>
				</div>
				<!--Errori di login inizialmente nascosti ma che saranno visibili se si verifica un errore-->
				<p class="errorUtenteLogin" style="visibility: <?php echo $errorUtenteLogin?>">L'utente con e-mail <?php echo $loginEmail?> non esiste!</p>
				<p class="errorPasswordLogin" style="visibility: <?php echo $errorPasswordLogin?>">Password errata!</p>
			</div>
			<!--Inclusione del footer-->
			<?php include("footer.php");?>	
		</div>
	</div>
</body>
</html>

<?php
	//Questa funzione controlla se l'utente con la mail passata come parametro esiste già nel db (già è avvenuta la registrazione con quella mail)
	function userExist($email){
		require "db.php";
		//Connessione al db
		$db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
		//Query al db per selezionare la mail dalla tabella utente dove la mail è proprio quella passata come parametro alla funzione
		$sql = "SELECT email FROM UTENTE WHERE email=$1";
		$prep = pg_prepare($db, "sqlEmail", $sql); 
		$ret = pg_execute($db, "sqlEmail", array($email));
		if(!$ret) {
			echo "ERRORE QUERY: " . pg_last_error($db);
			return false; 
		}
		else{
			//Se si entra in questo if significa che l'utente esiste già
			if ($row = pg_fetch_assoc($ret)){ 
				return true;
			}
			else{
				return false;
			}
		}
	}

	//Funzione che permette l'inserimento dell'utente nel db con i dati inseriti nei vari input
	function insertUtente($nome, $cognome, $numeroPatente, $patenteAuto, $patenteMoto, $email, $pass){
		require "db.php";
		$db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$sql = "INSERT INTO UTENTE(nome, cognome,  numero_patente, patente_auto, patente_moto, email, password) VALUES($1, $2, $3, $4, $5, $6, $7)";
		$prep = pg_prepare($db, "insertUser", $sql); 
		$ret = pg_execute($db, "insertUser", array($nome, $cognome, $numeroPatente, $patenteAuto, $patenteMoto, $email, $hash));
		if(!$ret) {
			echo "$hash ERRORE QUERY: " . pg_last_error($db);
			return false; 
		}
		else{
			return true;
		}
	}
	//Funzione che data la mail restituisce la password dell'utente
	function getPwd($email){
		require "db.php";
		$db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
		$sql = "SELECT password FROM UTENTE WHERE email=$1;";
		$prep = pg_prepare($db, "sqlPassword", $sql); 
		$ret = pg_execute($db, "sqlPassword", array($email));
		if(!$ret) {
			echo "ERRORE QUERY: " . pg_last_error($db);
			return false; 
		}
		else{
			if ($row = pg_fetch_assoc($ret)){ 
				$pass = $row['password'];
				return $pass;
			}
			else{
				return false;
			}
		}
	}	

	//Funzione che data la mail restituisce il nome dell'utente
	function getName($email){
		require "db.php";
		$db = pg_connect($connectionString) or die('Impossibile connetersi al database: ' . pg_last_error());
		$sql = "SELECT nome FROM UTENTE WHERE email=$1;";
		$prep = pg_prepare($db, "sqlNome", $sql); 
		$ret = pg_execute($db, "sqlNome", array($email));
		if(!$ret) {
			echo "ERRORE QUERY: " . pg_last_error($db);
			return false; 
		}
		else{
			if ($row = pg_fetch_assoc($ret)){ 
				$nome = $row['nome'];
				return $nome;
			}
			else{
				return false;
			}
		}
	}	
?>