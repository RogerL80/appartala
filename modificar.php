<?php 
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	sleep(2);
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$con2 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con2));
	if(isset($_POST['nombre']) && !empty($_POST['nombre'])&&
		isset($_POST['dir']) && !empty($_POST['dir'])&&
		isset($_POST['nit']) && !empty($_POST['nit'])&&
		isset($_POST['codpais']) && !empty($_POST['codpais'])&&
		isset($_POST['tel']) && !empty($_POST['tel'])&&
		isset($_POST['email']) && !empty($_POST['email'])&&
		isset($_POST['pw']) && !empty($_POST['pw'])&&
		isset($_POST['pw2']) && !empty($_POST['pw2'])&&
		isset($_POST['lat']) && !empty($_POST['lat'])&&
		isset($_POST['lng']) && !empty($_POST['lng']))
	{
		$username = $_POST['usuario'];
		$email = $_POST['email'];
		$idesta = $_POST['idest'];

		$dire = utf8_decode($_POST['dir']);
		$nom = utf8_decode($_POST['nombre']);
		$ciudad = utf8_decode($_POST['ciudad']);
		$estado = utf8_decode($_POST['estado']);
		$des = sanear_string($_POST['descripcion']);
		$pais = utf8_decode($_POST['pais']);
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$des = str_ireplace($buscar,$reemplazar,$des);

		// Insertar registro sin foto
		$ruta = "";
		$tipoimg= "";
		$facebook = "";
		$twitter = "";
		$instagram = "";
		if ($_FILES['foto']['tmp_name']) {
			$fotoant = $_POST['fotoant'];
			if ($fotoant != '') {
				unlink($fotoant);
			}
			$ruta = "imagenest";
			$nombreArchivo = $_FILES['foto']['name'];
			$archivo = $_FILES['foto']['tmp_name'];
			$ext = explode(".", $_FILES['foto']['name']);
			$nuevonombre = $_POST['idest'].".".$ext[1];
			move_uploaded_file($archivo, $ruta."/".$nombreArchivo);
			resizeImagen($ruta.'/', $nombreArchivo, 400, 400,$nuevonombre,$ext[1]);
			unlink($ruta.'/'.$nombreArchivo);
			$ruta = $ruta."/".$nuevonombre;
			$con -> query("UPDATE establecimiento SET nombre = '$nom',nit = '$_POST[nit]',email = '$_POST[email]',
				pw = '$_POST[pw]',direccion = '$dire', tel = '$_POST[tel]', isopais = '$_POST[codpais]',
				ciudad = '$ciudad', estado = '$estado', descripcion = '$des', lat = '$_POST[lat]',
				lng = '$_POST[lng]', foto = '$ruta', fechafin = '$_POST[fechamax]', activo = '$_POST[activo]', iduser= '$username', facebook = '$facebook', twitter = '$twitter', instagram = '$instagram' 
				WHERE idEstablecimiento = '$_POST[idest]'") 
			or die("Error al Actualizar registro con foto" . mysqli_error($con));
			//echo "Actualizado con foto <br/>";
		}else{
			// Actualizar registro sin foto
			$ruta="";
			$con -> query("UPDATE establecimiento SET nombre = '$nom',nit = '$_POST[nit]',email = '$_POST[email]',
				pw = '$_POST[pw]',direccion = '$dire', tel = '$_POST[tel]', isopais = '$_POST[codpais]',
				ciudad = '$ciudad', estado = '$estado', descripcion = '$des', lat = '$_POST[lat]',
				lng = '$_POST[lng]', fechafin = '$_POST[fechamax]', activo = '$_POST[activo]', iduser= '$username', facebook = '$facebook', twitter = '$twitter', instagram = '$instagram' WHERE idEstablecimiento = '$_POST[idest]'")
					or die("Error al Actualizar registro sin foto" . mysqli_error($con));
			//echo "Actualizado sin foto <br/>";
		}
		$con2 -> query("UPDATE cliente SET nombres = '$nom',email = '$_POST[email]',pw = '$_POST[pw]',telefono = '$_POST[tel]', idUsuario= '$username' WHERE idEstablecimiento = '$_POST[idest]'")
		or die("Error al Actualizar cliente" . mysqli_error($con2));
		echo 4;
			
		
	}else{	
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