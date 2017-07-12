<?php

	include('../includes/db_connect.php');
	include('../includes/session.php');

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

	function tipoUsuario($email, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT admin FROM Usuario WHERE email ='$email'")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($admin);
			$stmt->fetch();
			if ($admin == 0)
				Session::set("admin", 0); //No es admin
			else{
				if ($admin == 1)
					Session::set("admin", 1); //Es admin
			}
		}
	}

	if (isset($_POST['email'], $_POST['password'])) {
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);		
		if (login($email, $password, $mysqli) == true){ // Inicio de sesión exitosa
			Session::init();
			Session::set("email", $email);
			Session::set("conectado", 1);
			tipoUsuario($email, $mysqli);
			echo "Log in exitoso";
		}
		else // Inicio de sesión fallida
			echo "Log in fallido";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();