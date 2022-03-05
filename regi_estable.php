<?php
  require ('server/hosting.php');
  //Reportar errores PHP
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once('includes/geoplugin.class.php');
  $currencycode = "";
  $currencysymbol = "";
  $geoplugin = new geoPlugin();
  //locate the IP
  $geoplugin->locate();
  $currencycode = $geoplugin->currencyCode;
  $currencysymbol = html_entity_decode($geoplugin->currencySymbol);//CONVIRITENDO A TEXTO LOS HTML CODE
  //
  $alerta="";
  $tsesion="";
  // Evitar los warnings the variables no definidas!!!
  $err = isset($_GET['error']) ? $_GET['error'] : null ;
  // Iniciar Sesion
  session_start();
  if(isset ($_SESSION['username'])) {
    $alerta = utf8_encode($_SESSION['user']);
    $tsesion = $_SESSION['tiposesion'];
    echo '<script type="text/javascript">
        alert("Debes cerrar la sesion actual para poder registrar tu negocio");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
  }else{
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
    // Cargamos la plantilla
    $twig->display('regi_estable_twig.html',array(
      "titulo" => "Registrate con Nosotros",
      "len" => $leng,
      "publickey" => $publickey,
      "sesion" => $tsesion,
      "simbolo" => $currencysymbol,
      "moneda" => $currencycode,
      "alerta" => $alerta
    ));
  }
?>