<?php 
	// Evitar los warnings the variables no definidas!!!
	$err = isset($_GET['error']) ? $_GET['error'] : null ; 	
 	include("server/key_captcha.php");	
	// Validaciones de sesion
	if($err==4){
		echo '<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			E-mail no existe para recuperar contraseña  <br /></div>';
	}
	// Directorio Raíz de la app
	// Es utilizado en templateEngine.inc.php
	$root = '';
	// Incluimos el template engine
	include('includes/templateEngine.inc.php');
	// Incluir Funciones
	include('server/key_captcha.php');
	// Cargamos la plantilla
	$twig->display('recordares_twig.html',array(
		"titulo" => "Recordar Contraseña",
		"publickey" => $publickey
	));
?>