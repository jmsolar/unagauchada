<?php

	include_once '../includes/db_connect.php';

	function filtrar_por_categoria(){
		
	}

	$filtro = trim($_POST['checkbox']);
	if ($filtro == "categoria")
		filtrar_por_categoria();
	else
		filtrar_por_ciudad();
?>