<?php
	require ('server/hosting.php');
	//Reportar errores PHP
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$alerta="";
	$tsesion="";
	// Evitar los warnings the variables no definidas!!!
	$err = isset($_GET['error']) ? $_GET['error'] : null ;
	// Iniciar Sesion
	session_start();
	if(isset ($_SESSION['username'])) {
		$alerta = utf8_encode($_SESSION['user']);
		$tsesion = $_SESSION['tiposesion'];
		echo '<script type="text/javascript">
		alert("Debes cerrar la sesion actual para poder registrar un nuevo usuario");
		if (window.history.length>1){
		  window.history.go(-1)
		}else window.location="'.$hosting.'/inisesion.php"</script>';
	}else{
		// Directorio Raíz de la app
		// Es utilizado en templateEngine.inc.php
		$root = '';
		// Incluimos el template engine
		include('includes/templateEngine.inc.php');
		// Incluir Funciones
		include('includes/functions.inc.php');
		include('server/key_captcha.php');
		$leng = getUserLanguage();
		// Cargamos la plantilla
		$twig->display('registro_twig.html',array(
		  "titulo" => "Registrate",
		  "len" => $leng,
		  "publickey" => $publickey,
		  "sesion" => $tsesion,
		  "alerta" => $alerta
		));
	}
?>