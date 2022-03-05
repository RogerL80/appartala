<?php
require ('server/hosting.php');
//Reportar errores PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;
$alerta="";
$tipos ="";
// Iniciar Sesion
session_start();
include("server/conexion.php");
if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==1) {
  $alerta = utf8_encode($_SESSION['user']);
  $tipos = "1";
  // Directorio Raíz de la app
  // Es utilizado en templateEngine.inc.php
  $root = '';
  // Incluimos el template engine
  include('includes/templateEngine.inc.php');
  // Incluir Funciones
  include('includes/functions.inc.php');
  include('server/key_captcha.php');
  // Idioma del navegador
  $leng = getUserLanguage();
  $em = $_GET['idneg'];
  $can = $_GET['idcan'];
  $clien = getcodCliente($em);
  // Hora Inicio y Hora Fin
  $horari = getMinMax($em);
  // Horas de atención del Establecimiento
  //$horarios = getHorarios($em);
  // Reservadas
  //$reservadas = getReservas2($can);
  // Tarifas
  $tarifas = getTarifas($can);
  if(empty($tarifas)) {
      echo '<script>alert("Debe colocar tarifas para poder realizar reservas en esta cancha");</script>';
      //ENVIAR CORREO AL NEGOCIO AVISANDO QUE DEJO DE APARTAR UNA HORA POR NO TENER TARIFAS
      echo '<script>window.location="'.$hosting.'/tarifas.php?idneg='.$em.'&idcan='.$can.'"</script>';
  }
  //Fecha Maxima de reserva
  $fin = fechafinal($em);
  //$estado = getEstado();
  $nomcan = get_Cancha($can);
  if(!$nomcan['activa']) {
      //echo '<script>alert("Esta cancha aun no tiene tarifas definidas, intentelo mas tarde");</script>';
      //ENVIAR CORREO AL NEGOCIO AVISANDO QUE DEJO DE APARTAR UNA HORA POR NO TENER TARIFAS
      echo '<script>window.history.go(-1)</script>';
    }
  $nomestable = sel_negocio($em);
  // Cargamos la plantilla
  $twig->display('reservaes_twig.html',array(
    "titulo" => "Appartala... la q tu quieras",
    "len" => $leng,
    //"tarifa" => $tarifas,
    "hora_ini" => $horari['MIN(horaIni)'],
    "hora_fin" => $horari['MAX(horaFin)'], 
    "fecha_fin" => $fin['fechafin'],
    //"reservada" => $reservadas,
    //"horario" => $horarios,
    "em" => $em,
    //"publickey" => $publickey,
    "cliente" => $clien['idCliente'],
    "cancha" => $can,
    //"estado" => $estado,
    "nombre_cancha" => $nomcan['nombreCancha'],
    "nombre_establ" => $nomestable['nombre'],
    "fotoesta" => $nomestable['foto'],
    "alerta" => $alerta,
    "sesion" => $tipos
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