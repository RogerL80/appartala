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
if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==1) {
  $alerta = utf8_encode($_SESSION['user']);
  // Directorio Raíz de la app
  // Es utilizado en templateEngine.inc.php
  $root = '';
  $tsesion = $_SESSION['tiposesion'];
  // Incluimos el template engine
  include('includes/templateEngine.inc.php');
  // Incluir Funciones
  include('includes/functions.inc.php');
  // Lista de registros completa
  $em = $_SESSION['codigo'];
  $establecimiento = sel_estable($em); 
  // Idioma del navegador
  $leng = getUserLanguage();
  // Lista de canchas del Establecimiento
  $canchas = getCanchas($em); 
  // Lista de canchas del Establecimiento
  $horarios = getHorarios($em);
  // Lista de canchas del Establecimiento
  $superficie = getSuper(); 
  // Hora Inicio y Hora Fin
  $horari = getMinMax($em); 
  // Fotos Galeria
  $galerias = getFoto($em);
  // Cargamos la plantilla
  $twig->display('estable_twig.html',array(
    "titulo" => "Appartala... la q tu quieras",
    "estable" => $establecimiento,
    "cancha" => $canchas,
    "horario" => $horarios,
    "hora_ini" => $horari['MIN(horaIni)'],
    "hora_fin" => $horari['MAX(horaFin)'],
    "em" => $em,
    "galeria" => $galerias,
    "super" => $superficie,
    "len" => $leng,
    "alerta" => $alerta,
    "sesion" => $tsesion
  ));
}else{
  echo '<script type="text/javascript">
        alert("Area No permitida para clientes");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
  //echo '<script>window.history.go(-1)</script>';
}
?>