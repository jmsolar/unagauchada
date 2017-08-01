<?php

	include_once '../includes/db_connect.php';

	function existeEmail($email, $mysqli) {
		if ($stmt=$mysqli->prepare("SELECT COUNT(idUsuario) AS cantidad FROM Usuario WHERE email LIKE '$email'")) {
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($cantidad);
			$stmt->fetch();
			if ($cantidad == 0){
				return false;
			} else
				return true;
		}
	}

	function registrar_usuario($password, $nombre, $apellido, $email, $fnac, $telefono, $img, $mysqli) {
		$fnac=date("Y-m-d", strtotime($fnac));
		if ($stmt = $mysqli->prepare("INSERT INTO Usuario(`password`, `nombre`, `apellido`, `email`, `fechaNacimiento`, `telefono`, `fotoPerfil`, `admin`, `reputacion`, `cantCreditos`) VALUES('$password', '$nombre', '$apellido', '$email', '$fnac', '$telefono', '$img',0,1,1)")){
			$stmt->execute();    // Ejecuta la consulta preparada
		} else
			return false;		
		return true;
	}
  
	if (isset($_POST['password'], $_POST['repeat-pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'],  $_POST['fnac'], $_POST['telefono'])){		
		if(isset($_POST['image']) && ($_POST['image'] != 'undefined'))
			$img = trim($_POST['image']);
		else
			$img = '../img/logo.png';
		$password = trim($_POST['password']);
		$repeat_pass = trim($_POST['repeat-pass']);
		$nombre = trim($_POST['nombre']);
		$apellido = trim($_POST['apellido']);
		$email = trim($_POST['email']);
		$fnac = trim($_POST['fnac']);
		$telefono = trim($_POST['telefono']);

		if (!existeEmail($email, $mysqli)){ //Registro exitoso
			if ($password != $repeat_pass)
				echo "Las contraseñas son distintas";
			else
				if (registrar_usuario($password, $nombre, $apellido, $email, $fnac, $telefono, $img, $mysqli))
					echo "Usuario registrado exitosamente";
		}
		else // Registro fallido
			echo "Email existente";
	}
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();