<?php
  //Reportar errores PHP
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  // Evitar los warnings the variables no definidas!!!
  $err = isset($_GET['error']) ? $_GET['error'] : null ;
  $alerta="";
  $umail = "";
  $apeusu = "";
  $fbloqueo = "";
  $blok = "";
  // Iniciar Sesion
  session_start();
  include("server/conexion.php");
  if (!isset($_SESSION['tiposesion']) || $_SESSION['tiposesion']!=1) {
    
    if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==3) {
      $alerta = utf8_encode($_SESSION['user']);
      $umail = $_SESSION['username'];
      $apeusu = $_SESSION['apellidos'];
    }
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
    $clien = "";
    if(isset($_SESSION['idclient'])){
      $clien = $_SESSION['idclient'];
      // Si el cliente puede hacer reservas o no
      $fbloqueo = getBloqueo($clien);
      $blok = $fbloqueo['bloqueado'];
    } 
    // Hora Inicio y Hora Fin
    $horari = getMinMax($em);
    // Horas de atención del Establecimiento
    //$horarios = getHorarios($em);
    // Reservadas
    //$cancha = Cancha seleccionada
    //$reservadas = getReservas($can);
    // Tarifas
    $tarifas = getTarifas($can);
    if(empty($tarifas)) {
      echo '<script>alert("Esta cancha aun no tiene tarifas definidas, intentelo mas tarde");</script>';
      //ENVIAR CORREO AL NEGOCIO AVISANDO QUE DEJO DE APARTAR UNA HORA POR NO TENER TARIFAS
      echo '<script>window.history.go(-1)</script>';
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
    $twig->display('reserva_twig.html',array(
      "titulo" => "Appartala... la q tu quieras",
      "len" => $leng,
      //"tarifa" => $tarifas,
      "hora_ini" => $horari['MIN(horaIni)'],
      "hora_fin" => $horari['MAX(horaFin)'], 
      "fecha_fin" => $fin['fechafin'],
      //"reservada" => $reservadas,
      //"horario" => $horarios,
      "em" => $em,
      "publickey" => $publickey,
      "cliente" => $clien,
      "cancha" => $can,
      //"estado" => $estado,
      "nombre_cancha" => $nomcan['nombreCancha'],
      "nombre_establ" => $nomestable['nombre'],
      "fotoesta" => $nomestable['foto'],
      "mail_estable" => $nomestable['email'],
      "mail_user" => $umail,
      "bloqueo" => $blok,
      "apell" => $apeusu,
      "alerta" => $alerta
    ));
  }
  else{
    //echo '<div class="alert alert-warning alert-dismissable">Area Exclusiva para clientes</div>';
    echo '<script>alert("Area Exclusiva para clientes, Debe cerrar sesion de negocio");</script>';
    echo '<script>window.history.go(-1)</script>';
  }
?>