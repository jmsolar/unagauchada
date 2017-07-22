<?php

	include_once '../includes/db_connect.php';
	include '../includes/session.php';

	function existe($nombre, $mysqli){
		$stmt=$mysqli->prepare("SELECT COUNT(titulo) AS cant FROM Categoria WHERE titulo = '$nombre'");
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($cant);
		$stmt->fetch();
		if ($cant == 0)
			return false;
		else
			return true;
	}

	function agregar($nombre, $mysqli){
		$stmt=$mysqli->prepare("INSERT INTO Categoria (`titulo`) VALUES ('$nombre')");
		$stmt->execute();    // Ejecuta la consulta preparada
		$stmt->close();
	}

	if (isset($_POST['nombreAgregar'])) {
		$nombre = trim($_POST['nombreAgregar']);
		if (!existe($nombre, $mysqli)){
			agregar($nombre, $mysqli);
			echo "Categoria agregada";
		}
		else
			echo "Categoria duplicada";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();