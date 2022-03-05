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
if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==1 && $_SESSION['codigo']==$_GET['idneg']) {
  $alerta = utf8_encode($_SESSION['user']);
  // Directorio Raíz de la app
  // Es utilizado en templateEngine.inc.php
  $root = '';
  // Incluimos el template engine
  include('includes/templateEngine.inc.php');
  // Incluir Funciones
  include('includes/functions.inc.php');
  // Lista de registros completa
  $can = $_GET['idcan'];
  $em = $_GET['idneg'];
  $nombre = getCancha($can);
  // Idioma del navegador
  $leng = getUserLanguage();
  // Hora Inicio y Hora Fin
  $horari = getMinMax($em);
  // Tarifas
  $tarifas = getTarifas($can);
  // Horas de atención del Establecimiento
  //$horarios = getHorarios($em);
  //Datos del Negocio 
  $nomestable = sel_negocio($em);
  // Cargamos la plantilla
  $twig->display('tarifas_twig.html',array(
    "titulo" => "Appartala... la q tu quieras",  
    "cancha" => $can,
    "nc" => $nombre,
    //"tarifa" => $tarifas,
    "hora_ini" => $horari['MIN(horaIni)'],
    "hora_fin" => $horari['MAX(horaFin)'],
    //"horario" => $horarios,
    "em" => $em,
    "nombre_establ" => $nomestable['nombre'],
    "fotoesta" => $nomestable['foto'],
    "len" => $leng,
    "alerta" => $alerta
  ));
}else{
  echo '<script>window.history.go(-1)</script>';
}
?>