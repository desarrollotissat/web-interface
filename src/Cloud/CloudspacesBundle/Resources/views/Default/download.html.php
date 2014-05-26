<?php
	//echo $mimetype;
	set_time_limit(0);
	
	//header("Content-Type: ".$mimetype);
    header('content-type:');
    header('Content-Description: File Transfer');
    header('Expires: 0');

	header("Content-Type: application/force-download");	 
	header("Content-Disposition: attachment; filename=".$nombre);
	header("Content-Transfer-Encoding: binary");
	//header("Content-Length: " . $length);	
	
	$fd = fopen($nombreFichero, "rb");
	
	 while(!feof($fd)) {
        $buffer = fread($fd, 15*(1024*1024));
        echo $buffer;
        ob_flush();
        flush();    //These two flush commands seem to have helped with performance
     }
	
	
	//readfile($nombreFichero);
	
	//Borrar fichero despues de la descarga	
	unlink($nombreFichero);
?>