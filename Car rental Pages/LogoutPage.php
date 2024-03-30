<html>
<head>
	<title>Logout</title>
</head>
<body>
<?php
 	/* attiva la sessione */
	session_start();
	/* sessione attiva, la distrugge */
	$sname=session_name();
	session_destroy();
	/* ed elimina il cookie corrispondente */
	if (isset($_COOKIE[session_name()])) { 
		setcookie($sname,'', time()-3600,'/');
	}
	header("Location: "."MainPage.php");
	exit();
?>
</body>
</html>