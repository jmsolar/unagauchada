<?php

	include_once '../includes/db_connect.php';
	include '../includes/session.php';

	function idUsuario($email, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT idUsuario FROM Usuario WHERE email ='$email'")) {
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idUsuario);
			$stmt->fetch();
			$stmt->close();
			return $idUsuario;			
		} else 
			return -1;
	}

	function creditosActual($idUsuario, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT cantCreditos FROM Usuario WHERE idUsuario = '$idUsuario'")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($cantCreditos);
			$stmt->fetch();
			$stmt->close();
			return $cantCreditos;
		}
	}

	Session::init();
	$email=Session::get("email");
	$idUsuario=intval(idUsuario($email, $mysqli));	
	if (creditosActual($idUsuario, $mysqli) > 0){
		echo "Tiene creditos";
		return true;
	}
	else
		echo "No tiene creditos";
	exit();