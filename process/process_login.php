<?php

	include_once '../includes/db_connect.php';

    function login($email, $password, $mysqli) {
		if ($stmt = $mysqli->prepare("SELECT idUsuario, email, password FROM Usuario WHERE email = ? LIMIT 1")) {
			$stmt->bind_param('s', $email);  // Une “$email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
		
			// Obtiene las variables del resultado.
			$stmt->bind_result($idUsuario, $email, $pass);
			$stmt->fetch();
			if ($password == $pass)
				return true;
			else
				return false;
		} else
			return false;
		return true;
	}

	if (isset($_POST['email'], $_POST['password'])) {
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);		
		if (login($email, $password, $mysqli) == true){ // Inicio de sesión exitosa
			session_start();
			$_SESSION["email"]=$email;
			echo "Log in exitoso";
		}
		else // Inicio de sesión fallida
			echo "Log in fallido";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();