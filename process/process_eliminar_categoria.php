<?php

	include_once '../includes/db_connect.php';
	include '../includes/session.php';

	function existe($nombre, $mysqli){
		$stmt=$mysqli->prepare("SELECT COUNT(c.titulo) AS cant FROM Categoria c INNER JOIN Gauchada g ON c.idCategoria = g.idCategoria WHERE c.titulo = '$nombre'");
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($cant);
		$stmt->fetch();
		if ($cant == 0)
			return false;
		else
			return true;
	}

	function eliminar($nombre, $mysqli){
		if($stmt=$mysqli->prepare("DELETE FROM Categoria WHERE titulo = '$nombre'")){
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();
			return true;
		}
		return false;
	}

	if (isset($_POST['nombre'])) {
		$nombre = trim($_POST['nombre']);
		if (!existe($nombre, $mysqli)){
			if (eliminar($nombre, $mysqli))
				echo "Categoria eliminada";
		}
		else
			echo "Categoria existente";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();