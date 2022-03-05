<?php 
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));

	if(isset($_POST['idestab']) && !empty($_POST['idestab']))
	{

		$descripcion = utf8_decode($_POST['descrifoto']);
		$descripcion = sanear_string($descripcion);
		/*echo "<br/>Nomb: ".$_FILES['fotog']['tmp_name'];
		echo "<br/>descripcion: ".$descripcion;
		echo "<br/>idest: ".$_POST['idest'];
		echo "<br/>largo: ".$_POST['largo'];
		echo "<br/>ancho: ".$_POST['ancho'];
		echo "<br/>jugadores: ".$_POST['jugadores'];
		echo "<br/>superficie: ".$_POST['superficie'];
		*/
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$descripcion=str_ireplace($buscar,$reemplazar,$descripcion);
		//insertar codigo para guardar imagen en carpeta fotosestable si hay foto
		
		if ($_FILES['fotog']['tmp_name']) {
			$result  = $con -> query("SELECT COUNT(*) FROM foto")
			or die("Error en la consulta" . mysqli_error($result));
			//$row= $result->fetch_assoc();
			$ruta = "fotosestable";
			$nombreArchivo = $_FILES['fotog']['name'];
			$archivo = $_FILES['fotog']['tmp_name'];
			$ext = explode(".", $_FILES['fotog']['name']);
			$random = rand();
			$nuevonombre = $_POST['idestab']."_".$random.".".$ext[1];
			//$nuevonombre = 'res_'.$nombreArchivo;
			move_uploaded_file($archivo, $ruta."/".$nombreArchivo);
			resizeImagen($ruta.'/', $nombreArchivo, 500, 500,$nuevonombre,$ext[1]);
        	unlink($ruta.'/'.$nombreArchivo);    
			$ruta = $ruta."/".$nuevonombre;
			//echo "ruta: ".$ruta;
			// Insertar registro
			$con->query("INSERT INTO foto(idEstablecimiento,ruta,descripcion)
				VALUES('$_POST[idestab]','$ruta','$descripcion')")
				or die ("<br/>Error al insertar... ". mysqli_error($con));		
			//header('Location: ./estable.php?em='.$_POST['idest']);
			echo '<script>window.history.go(-1)</script>';
		}
		else{
			echo '<script type="text/javascript">
	        alert("Debes seleccionar una foto valida");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
		}
	}	
	else{
		echo '<script type="text/javascript">
        alert("Debes llenar todos los campos para subir la foto");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';

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