<?php
//Reportar errores PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;
$alerta="";
$tsesion="";
// Iniciar Sesion
session_start();
include("server/conexion.php");
if(isset ($_SESSION['username'])) {
  $alerta = utf8_encode($_SESSION['user']);
  $tsesion = $_SESSION['tiposesion'];
}

// Directorio Raíz de la app
// Es utilizado en templateEngine.inc.php
$root = '';
// Incluimos el template engine
include('includes/templateEngine.inc.php');

// Cargamos la plantilla
$twig->display('index_twig.html',array(
  "titulo" => "Appartala... la q tu quieras Git",
  "sesion" => $tsesion,
  "alerta" => $alerta
));

?>