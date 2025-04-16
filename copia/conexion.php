<?php

	$mysqli = mysqli_connect("localhost", "root", "", "sistema");
		if (!$mysqli) {
    		die("ERROR: No se pudo conectar a la base de datos." . mysqli_connect_error());
	}

?>