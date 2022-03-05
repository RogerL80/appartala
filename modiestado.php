<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));

	if(isset($_GET['em']) && !empty($_GET['em']))
	{
		
		if ($_GET['est']=='1') {
			$con->query("UPDATE establecimiento SET activo = '0' WHERE idEstablecimiento='$_GET[em]'")
					or die ("<br/>Error al insertar... ". mysqli_error($con));			
		}else{
			$con->query("UPDATE establecimiento SET activo = '1' WHERE idEstablecimiento='$_GET[em]'")
					or die ("<br/>Error al insertar... ". mysqli_error($con));
		}
		//header('Location: ./estable.php?em='.$_POST['idest']);
		echo '<script>window.history.go(-1)</script>';
	}	
	else{		
		echo '<script type="text/javascript">
        alert("Area no permitida");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}
?>