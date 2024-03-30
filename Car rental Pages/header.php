<link rel="stylesheet" href="header.css">
<div class="header">
    <img src="Immagini autonoleggio/logo bianco.png" id="logo">
    <!--Inserisco nell'header il $pagetitle proveniente dalla pagina html che include l'header-->
    <?php echo $pagetitle;?>
    <?php
        //Controllo del login dell'utente: se l'utente non è loggato apparirà sul pulsante la scritta "accedi", altrimenti la scritta "ciao $user"
        if(empty($_SESSION["nome"])){
            echo "<div class=\"accedialtodx\"><a href=\"SignLogPage.php\"><button id=\"accedi\"><img src=\"Immagini autonoleggio/accedi.png\"><span>Accedi</span></button></a></div>";
        } else {
            /*Preleviamo il nome dell'utente loggato*/
            $user = $_SESSION["nome"];
            echo "<div class=\"accedialtodx\">
                    <a href=\"ReservedPage.php\"><button id=\"accedi\"><img src=\"Immagini autonoleggio/accedi.png\"><span>ciao $user</span></button></a>
                    <!--Creazione della tendina che contiene i link alle tre pagine di modifica profilo, pagina riservata e logout-->
                    <div class=\"tendina\">        
                        <a href=\"ModifyPage.php\">Modifica profilo</a>
                        <a href=\"ReservedPage.php\">Pagina riservata</a>
                        <a href=\"LogoutPage.php\">Logout</a>
                    </div>
                </div>";
        }
    ?>
</div>