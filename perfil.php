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
	$tsesion = $_SESSION['tiposesion'];
	// Incluimos el template engine
	include('includes/templateEngine.inc.php');
	// Incluir Funciones
	include('includes/functions.inc.php');
	//Cargar Datos de Cliente
	$cliente = getCliente($cli);
	$reserva = getReservaCli($cli);
	// Cargamos la plantilla
	$twig->display('perfil_twig.html',array(
	  "titulo" => "Perfil",
	  "usuario" => $cliente,
	  "reserva" => $reserva,
	  "alerta" => $alerta,
	  "codicli" => $cli,
	  "sesion" => $tsesion
	//  "lista_completa" => $listaRegistros_comp
	));
}else{
	//header('Location: ./index.php');
	echo '<script type="text/javascript">
			alert("Area solo para clientes.");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'"</script>';
}
?>