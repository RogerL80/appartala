<?php 
require ('hosting.php');
require ('conexion.php');
// Incluir Funciones
$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
$can = $_POST['can'];
$query=$con->query("SELECT T1.*, T2.* FROM reservas T1 INNER JOIN cliente T2 ON T1.idCliente = T2.idCliente  
		WHERE (idCancha = '".$can."') AND (fechaIni >= CURDATE())");
$datos=array();
while ($reser=$query->fetch_array())
{
	$datos[]=array(	"idCliente"=>$reser["idCliente"],
					"fechaIni"=>$reser["fechaIni"],
					"hora"=>$reser["hora"],
					"estado"=>$reser["estado"],
					"nombres"=>$reser["nombres"],
					"apellidos"=>$reser["apellidos"],
					"reservaPara"=>$reser["reservaPara"],
					"email"=>$reser["email"],
					"emailR"=>$reser["emailR"],
					"idReserva"=>$reser["idReservas"],
					"telefono"=>$reser["telefono"],
					"telR"=>$reser["telR"],
	);
}
echo json_encode($datos);
?>