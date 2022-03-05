<?php
require ('server/hosting.php');
//Reportar errores PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;
$alerta="";
// Iniciar Sesion
session_start();
include("server/conexion.php");
if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==3) {
	$alerta = utf8_encode($_SESSION['user']);
	// Directorio Raíz de la app
	// Es utilizado en templateEngine.inc.php
	$root = '';
	$cli = $_SESSION['idclient'];
	// Incluimos el template engine
	include('includes/templateEngine.inc.php');
	// Incluir Funciones
	include('includes/functions.inc.php');
	include('server/key_captcha.php');
	// Idioma del navegador
  	$leng = getUserLanguage();
	//Cargar Datos de Cliente
	$cliente = getCliente($cli);

	// Cargamos la plantilla
	$twig->display('modicliente_twig.html',array(
	  "titulo" => "Modificar Usuario",
	  "len" => $leng,
	  "cliente" => $cliente,
	  "publickey" => $publickey,
	  "alerta" => $alerta,
	));
}else{
	//header('Location:' . getenv('HTTP_REFERER'));
	echo '<script type="text/javascript">
        alert("Debe iniciar sesion como cliente");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
}
?>