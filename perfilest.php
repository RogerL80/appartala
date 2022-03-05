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
if(isset ($_SESSION['username'])) {
  $alerta = utf8_encode($_SESSION['user']);
}
// Directorio Raíz de la app
// Es utilizado en templateEngine.inc.php
$root = '';
// Incluimos el template engine
include('includes/templateEngine.inc.php');
// Incluir Funciones
include('includes/functions.inc.php');
// Lista de registros completa
$em = $_GET['em'];
$establecimiento = sel_estable($em); 
// Lista de canchas del Establecimiento
$canchas = getCanchas($em);
// Fotos Galeria
$galerias = getFoto($em);
// Cargamos la plantilla
$twig->display('perfilest_twig.html',array(
  "titulo" => "Appartala... la q tu quieras",
  "estable" => $establecimiento,
  "cancha" => $canchas,
  "em" => $em,
  "galeria" => $galerias,
  "alerta" => $alerta
));
?>