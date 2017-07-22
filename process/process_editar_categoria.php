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

	function editar($nombre, $nombreNuevo, $mysqli){
		if($stmt=$mysqli->prepare("UPDATE Categoria SET titulo = '$nombreNuevo' WHERE titulo = '$nombre'")){
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();
			return true;
		}
		return false;
	}

	if (isset($_POST['nombre'], $_POST['nombreNuevo'])) {
		if (!empty($_POST['nombre'])){
			$nombre = trim($_POST['nombre']);
			$nombreNuevo = trim($_POST['nombreNuevo']);
			if (!existe($nombreNuevo, $mysqli)){
				if (editar($nombre, $nombreNuevo, $mysqli))
					echo "Categoria editada";
			}
			else
				echo "Categoria duplicada";
		}
		else
			echo "Campo vacio";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();