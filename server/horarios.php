<?php 
require ('hosting.php');
require ('conexion.php');
// Incluir Funciones
$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
$neg = $_POST['negocio'];
$query=$con->query("SELECT * FROM atencion WHERE idEstablecimiento = '".$neg."'");
$datos=array();
while ($horar=$query->fetch_array())
{
	$datos[]=array(	"horaIni"=>$horar["horaIni"],
					"horaFin"=>$horar["horaFin"]
	);
}
echo json_encode($datos);

?>