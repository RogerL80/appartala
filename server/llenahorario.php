<?php 
require ('hosting.php');
require ('conexion.php');
// Incluir Funciones
$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
$neg = $_POST['neg'];
if (isset($neg) && !empty($neg))
{
	$query=$con->query("SELECT * FROM atencion WHERE idEstablecimiento = '".$neg."'");
	$datos=array();
	while ($atencion=$query->fetch_array())
	{
		$datos[]=array(	"idDia"=>$atencion["idDia"],
						"horaIni"=>$atencion["horaIni"],
						"horaFin"=>$atencion["horaFin"]
		);
	}
	echo json_encode($datos);
}
?>