<?php 
require ('hosting.php');
require ('conexion.php');
// Incluir Funciones
$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
$query=$con->query("SELECT * FROM estado WHERE 1");
$datos=array();
while ($estado=$query->fetch_array())
{
	$datos[]=array(	"codEstado"=>$estado["codEstado"],
					"estadoReserva"=>$estado["estadoReserva"]
	);
}
echo json_encode($datos);

?>