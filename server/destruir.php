<?php
	require ('./hosting.php');
	session_start();
	session_destroy();
	echo '<script type="text/javascript">
			if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'"</script>'

?>