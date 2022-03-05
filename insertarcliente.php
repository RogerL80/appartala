<?php 
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	sleep(2); 
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	if(isset($_POST['nombres']) && !empty($_POST['nombres'])&&
		isset($_POST['apellidos']) && !empty($_POST['apellidos'])&&
		isset($_POST['usuario']) && !empty($_POST['usuario'])&&
		isset($_POST['nacimiento']) && !empty($_POST['nacimiento'])&&
		isset($_POST['email']) && !empty($_POST['email'])&&
		isset($_POST['pw']) && !empty($_POST['pw'])&&
		isset($_POST['pw2']) && !empty($_POST['pw2'])&&
		isset($_POST['creado']) && !empty($_POST['creado'])&&
		isset($_POST['tel']) && !empty($_POST['tel']))
	{
		$nom = ucwords(strtolower(utf8_decode($_POST['nombres'])));
		$apell = ucwords(strtolower(utf8_decode($_POST['apellidos'])));			
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		// Selecciona todos los registros en la tabla cliente
		$username = $_POST['usuario'];
		$email = $_POST['email'];
		
		$result = $con -> query("SELECT * FROM cliente WHERE email = '".$email."'")
		or die("Error en la consulta" . mysqli_error($result));
	    if ($result -> num_rows > 0){
	        echo 4;
	    }
	    else{
	        $result = $con -> query("SELECT * FROM cliente WHERE idUsuario = '".$username."'")
			or die("Error en la consulta" . mysqli_error($result));
			if ($result -> num_rows > 0){
			    echo 0;
		    }else{
			// Insertar registro sin foto
			$ruta = "";
			$tipoimg= "";
			$facebook = "";
			$twitter = "";
			$instagram = "";
			$creado = $_POST['creado'];
			
			$con->query("INSERT INTO cliente(nombres, apellidos, fechaNac, email, pw, telefono, fotoc, idUsuario,facebook,twitter,instagram,fechaReg)
				VALUES('$nom','$apell','$_POST[nacimiento]','$_POST[email]','$_POST[pw]','$_POST[tel]','$ruta','$_POST[usuario]','$facebook','$twitter','$instagram','$creado')")
				or die ("Error al insertar... ". mysqli_error($con));			
			//insertar codigo para guardar imagen en carpeta imagenest si hay foto			
			if ($_FILES['foto']['tmp_name']) {
				$result  = $con -> query("SELECT idCliente FROM cliente WHERE email='$_POST[email]'")
				or die("Error en la consulta" . mysqli_error($result));
				$row= $result->fetch_assoc();
				$ruta = "imagenc";
				$nombreArchivo = $_FILES['foto']['name'];
				$archivo = $_FILES['foto']['tmp_name'];
				$ext = explode(".", $_FILES['foto']['name']);
				$nuevonombre = $row['idCliente'].".".$ext[1];
				//$nuevonombre = 'res_'.$nombreArchivo;
				move_uploaded_file($archivo, $ruta."/".$nombreArchivo);
				resizeImagen($ruta.'/', $nombreArchivo, 200, 200,$nuevonombre,$ext[1]);
            	unlink($ruta.'/'.$nombreArchivo);    
				$ruta = $ruta."/".$nuevonombre;
				$con -> query("UPDATE cliente SET fotoc ='$ruta' WHERE idCliente = '$row[idCliente]'") 
				or die("Error al Actualizar" . mysqli_error($con));
			}			
			
			//ENVIAR CORREO AL CLIENTE ------------------------------------------------
			$nombres = $nom;
			$apellidos = $apell;
			$destinatario = $_POST['email'];
			$asunto = "Bienvenido a Appartala";
			$pw = $_POST['pw'];			
			$cuerpo = ' 
			<html> 
			<head> 
			   <title>Bienvenido a Appartala</title> 
			</head> 
			<body> 
			<h1>Hola '.$nombres.' '.$apellidos.'!</h1> 
			<p> 
			<b>Bienvenido a Appartala</b>; a partir de este momento tu has ingresado al sitio para reservar canchas de futbol en linea.<br/>Tu contrase&ntilde;a es: <b>'.$pw.'</b>.
			<br/>ingresa a nuestro sitio <a href="'.$hosting.'">appartala.com</a><br/> y comienza a reservar la cancha que tu quieras.
			</p> 
			</body> 
			</html> 
			'; 
			//para el envío en formato HTML 
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			//dirección del remitente 
			$headers .= "From: Appartala <".$mailpri.">\r\n"; 
			//dirección de respuesta, si queremos que sea distinta que la del remitente 
			$headers .= "Reply-To: donotreply@gmail.com\r\n"; 
			//ruta del mensaje desde origen a destino 
			$headers .= "Return-path: ".$mailpri."\r\n"; 
			//direcciones que recibián copia 
			//$headers .= "Cc: roger_lozada@hotmail.com\r\n"; 
			//direcciones que recibirán copia oculta 
			//$headers .= "Bcc: roglozada@gmail.com\r\n";  
			if(mail($destinatario,$asunto,$cuerpo,$headers)){
				echo 1;
			}else{
				echo 2;
			}
			}
			//AL CLIENTE ------------------------------------------------------------------
	    }
	}	
	else{
		echo 3;
	}

	function resizeImagen($ruta, $nombre, $alto, $ancho,$nombreN,$extension){
	    $rutaImagenOriginal = $ruta.$nombre;
	    if($extension == 'GIF' || $extension == 'gif'){
	    	$img_original = imagecreatefromgif($rutaImagenOriginal);
	    }
	    if($extension == 'jpg' || $extension == 'JPG'){
	    	$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	    }
	    if($extension == 'jpeg' || $extension == 'JPEG'){
	    	$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	    }
	    if($extension == 'bmp' || $extension == 'BMP'){
	    	$img_original = imagecreatefrombmp($rutaImagenOriginal);
	    }
	    if($extension == 'png' || $extension == 'PNG'){
	    	$img_original = imagecreatefrompng($rutaImagenOriginal);
	    }
	    $max_ancho = $ancho;
	    $max_alto = $alto;
	    list($ancho,$alto)=getimagesize($rutaImagenOriginal);
	    $x_ratio = $max_ancho / $ancho;
	    $y_ratio = $max_alto / $alto;
	    if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
	  		$ancho_final = $ancho;
			$alto_final = $alto;
		} elseif (($x_ratio * $alto) < $max_alto){
			$alto_final = ceil($x_ratio * $alto);
			$ancho_final = $max_ancho;
		} else{
			$ancho_final = ceil($y_ratio * $ancho);
			$alto_final = $max_alto;
		}
	    $tmp=imagecreatetruecolor($ancho_final,$alto_final);
	    imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
	    imagedestroy($img_original);
	    $calidad=70;
	    imagejpeg($tmp,$ruta.$nombreN,$calidad);	    
	}

	function imagecreatefrombmp($filename) {
		$f = fopen($filename, "rb");		
	    //read header    
	    $header = fread($f, 54);
	    $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
	    'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
	    'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);
		if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
			return false;			
		if ($header['bits_per_pixel'] != 24)
			return false;		
		$wid2 = ceil((3 * $header['width']) / 4) * 4;		
		$wid = $header['width'];
		$hei = $header['height'];
		$img = imagecreatetruecolor($header['width'], $header['height']);		
		//read pixels
		for ($y = $hei - 1; $y >= 0; $y--) {
			$row = fread($f, $wid2);
			$pixels = str_split($row, 3);			
			for ($x = 0; $x < $wid; $x++) {
				imagesetpixel($img, $x, $y, dwordize($pixels[$x]));
			}
		}
		fclose($f);
		return $img;
	}

	function dwordize($str) {
		$a = ord($str[0]);
		$b = ord($str[1]);
		$c = ord($str[2]);
		return $c * 256 * 256 + $b * 256 + $a;
	}
?>