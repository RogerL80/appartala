<?php 
require ('hosting.php');
require ('conexion.php');
// Incluir Funciones
$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
$can = $_POST['can'];
if (isset($can) && !empty($can))
{
	$query=$con->query("SELECT idDia, hora, valor FROM tarifas WHERE idCancha = '".$can."'");
	$datos=array();
	while ($tari=$query->fetch_array())
	{
		$datos[]=array(	"idDia"=>$tari["idDia"],
						"hora"=>$tari["hora"],
						"valor"=>$tari["valor"]
		);
	}
	echo json_encode($datos);
}
?>