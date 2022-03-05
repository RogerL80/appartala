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
if(isset ($_SESSION['username']) && $_SESSION['tiposesion']==1 && $_SESSION['codigo']==$_GET['em']) {
  $alerta = utf8_encode($_SESSION['user']);

// Directorio Raíz de la app
// Es utilizado en templateEngine.inc.php
$root = '';
// Incluimos el template engine
include('includes/templateEngine.inc.php');

// Incluir Funciones
include('includes/functions.inc.php');
// Incluir Funciones
include('server/key_captcha.php');
// Idioma del navegador
$leng = getUserLanguage();

// Lista de registros completa
$em = $_GET['em'];
$establecimiento = sel_estable($em); 

// Lista de canchas del Establecimiento
$canchas = getCanchas($em); 

// Cargamos la plantilla
$twig->display('modiestable_twig.html',array(
  "titulo" => "Appartala... la q tu quieras",
  "len" => $leng,
  "estable" => $establecimiento,
  "cancha" => $canchas,
  "em" => $em,
  "publickey" => $publickey,
  "privatekey" => $privatekey,
  "alerta" => $alerta
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