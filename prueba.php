<?php 

require_once('includes/geoplugin.class.php');
  $currencycode = "";
  $currencysymbol = "";
  $geoplugin = new geoPlugin();
  //locate the IP
  $geoplugin->locate();
  $currencycode = $geoplugin->currencyCode;
  $currencysymbol = $geoplugin->currencySymbol;
  $textcurrency = "texto ". $currencysymbol;
  echo ("Moneda: ". $currencycode."<br/>");
  echo ("Simbolo: ". $textcurrency);
?>