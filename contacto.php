<?php
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
}

// Directorio Raíz de la app
// Es utilizado en templateEngine.inc.php
$root = '';
// Incluimos el template engine
include('includes/templateEngine.inc.php');

// Incluir Funciones
//include('includes/functions.inc.php');

// Lista de registros completa
//$listaRegistros_comp = lista_completa(); 

// Cargamos la plantilla
$twig->display('contacto_twig.html',array(
  "titulo" => "Contacto",
//  "lista_completa" => $listaRegistros_comp
  "alerta" => $alerta
));

?>