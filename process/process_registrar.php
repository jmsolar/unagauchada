<?php

	include_once '../includes/db_connect.php';

	function existeEmail($email, $mysqli) {
		if ($stmt = $mysqli->prepare("SELECT COUNT(idUsuario) FROM Usuario WHERE email = ? LIMIT 1")) {
			$stmt->bind_param('s', $email);  // Une $email al parámetro.
			$stmt=$stmt->execute();    // Ejecuta la consulta preparada.
			if ($stmt == 1)
				return true; //El email ya existe
			else
				return false; //El email esta disponible
		} else 
			return false; //El usuario no existe
		return true;
	}

	function registrar_usuario($password, $nombre, $apellido, $email, $fnac, $telefono/*, $foto*/, $mysqli) {
		$fnac=date("Y-m-d", strtotime($fnac));
		if ($stmt = $mysqli->prepare("INSERT INTO Usuario(`password`, `nombre`, `apellido`, `email`, `fechaNacimiento`, `telefono`, `fotoPerfil`, `admin`, `reputacion`, `cantCreditos`) VALUES('$password', '$nombre', '$apellido', '$email', '$fnac', '$telefono','',0,1,1)")){
			$stmt->execute();    // Ejecuta la consulta preparada
		} else
			return false;		
		return true;
	}
  
	if (isset($_POST['password'], $_POST['repeat-pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'],  $_POST['fnac'], $_POST['telefono']/*, $_POST['foto']*/)){		
		$password = trim($_POST['password']);
		$repeat_pass = trim($_POST['repeat-pass']);
		$nombre = trim($_POST['nombre']);
		$apellido = trim($_POST['apellido']);
		$email = trim($_POST['email']);
		$fnac = trim($_POST['fnac']);
		$telefono = trim($_POST['telefono']);
		/*$foto = trim($_POST['foto']);*/
		
		session_start();
		$_SESSION["email"]=$email;

		if (!existeEmail($email, $mysqli) == false){ //Registro exitoso
			if ($password != $repeat_pass)
				echo "Las contraseñas son distintas";
			else
				if (registrar_usuario($password, $nombre, $apellido, $email, $fnac, $telefono, $mysqli))
					echo "Usuario registrado exitosamente";
		}
		else // Registro fallido
			echo "Email existente";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();