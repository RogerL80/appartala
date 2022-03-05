<?php 
	require ('server/conexion.php');
	sleep(2);
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	
	$username = $_POST['usuario'];
	$email = $_POST['email'];
	//$idesta = $_POST['idest'];
	$result = $con -> query("SELECT * FROM establecimiento WHERE email = '".$email."'")
	or die("Error en la consulta" . mysqli_error($result));
    if ($result -> num_rows > 0){
        echo 0;
    }
    else{
    	$result = $con -> query("SELECT * FROM cliente WHERE email = '".$email."'")
		or die("Error en la consulta" . mysqli_error($result));
	    if ($result -> num_rows > 0){
	        echo 0;
	    }
		else{
			$result = $con -> query("SELECT * FROM establecimiento WHERE iduser = '".$username."'")or die("Error en la consulta" . mysqli_error($result));
			if ($result -> num_rows > 0){
			    echo 1;
			}else{
				$result = $con -> query("SELECT * FROM cliente WHERE idUsuario = '".$username."'")or die("Error en la consulta" . mysqli_error($result));
				if ($result -> num_rows > 0){
				    echo 1;
				}else{
					echo 2;
				}
			}
		}
	}
?>