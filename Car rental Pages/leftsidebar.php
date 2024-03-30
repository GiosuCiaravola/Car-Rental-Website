<!--Includo il file css-->
<link rel="stylesheet" href="leftsidebar.css">
<!--Creazione della sidebar a sinistra delle pagine web con quattro pulsanti disposti in verticale-->
<section class="leftsidebar">
    <a href="MainPage.php"><button id="menu"><img src="Immagini autonoleggio/home.png"></button></a>
    <?php
        //Controllo del login dell'utente: se l'utente non è loggato il pulsante "Area riservata" lo porterà nella pagina del login, altrimenti nell'area riservata
        if(empty($_SESSION["nome"])){
            echo "<a href=\"SignLogPage.php\"><button id=\"accedi\"><img src=\"Immagini autonoleggio/accedi.png\"><p>Area riservata</p></button></a>";
        } else {
            $user = $_SESSION["nome"];
            echo "<a href=\"ReservedPage.php\"><button id=\"accedi\"><img src=\"Immagini autonoleggio/accedi.png\"><p>Area riservata</p></button></a>";
        }
    ?>
    <!--Pulsanti che portano alla pagina delle auto e delle moto-->
    <a href="VehiclePage.php?tipoVeicolo=Auto"><button id="auto"><img src="Immagini autonoleggio/auto.png"><p>Auto</p></button></a>
    <a href="VehiclePage.php?tipoVeicolo=Moto"><button id="moto"><img src="Immagini autonoleggio/moto.png"><p>Moto</p></button></a>
</section>