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

	function idUsuario($email, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT idUsuario FROM Usuario WHERE email = ? LIMIT 1")) {
			$stmt->bind_param('s', $email);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idUsuario);
			$stmt->fetch();
			$stmt->close();
			return $idUsuario;			
		} else 
			return -1;
	}

	function esValida($tarjeta, $idUsuario, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT COUNT(numero) FROM Tarjeta WHERE idUsuario = '$idUsuario' LIMIT 1")){
				$stmt = $stmt->execute();			
				if ($stmt == 1){
					return true;
				} else {
					if ($stmt=$mysqli->prepare("INSERT INTO tarjeta(`numero`, `saldo`, `idusuario`) VALUES('$tarjeta', 5000, '$idUsuario')")) {
					$stmt->execute();    // Ejecuta la consulta preparada.
					$stmt->store_result();
					$stmt->bind_result($idUsuario);
					$stmt->fetch();
					$stmt->close();
					return true;
				}
			}
		} 
	}

	function tieneSaldo($tarjeta, $credito, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT saldo FROM Tarjeta WHERE numero = ?")){
			$stmt->bind_param('d', $tarjeta);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($saldo);
			$stmt->fetch();
			$stmt->close();
			if ($saldo < ($credito * 10))
				return false;
			else
				return true;
		}
	}

	function saldo_actual($tarjeta, $mysqli){	
		if ($stmt=$mysqli->prepare("SELECT saldo FROM Tarjeta WHERE numero = ?")){
			$stmt->bind_param('s', $tarjeta);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($saldo);
			$stmt->fetch();
			$stmt->close();
			return $saldo;
		}
	}

	function actualizar_saldo($tarjeta, $credito, $mysqli){
		$saldo=saldo_actual($tarjeta, $mysqli);
		$saldo=$saldo - ($credito * 10);
		if ($stmt=$mysqli->prepare("UPDATE Tarjeta SET `saldo` = ? WHERE numero = ?")){
			$stmt->bind_param('ds', $saldo, $tarjeta);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			return true;
		}
		return false;
	}

	function actualizar_credito($credito, $idUsuario, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT cantCreditos FROM Usuario WHERE idUsuario = ?")){
			$stmt->bind_param('s', $idUsuario);
			$stmt = $stmt->execute();    // Ejecuta la consulta preparada.
			$credito = $credito + $stmt;
			if ($stmt=$mysqli->prepare("UPDATE Usuario SET `cantCreditos` = ? WHERE idUsuario = ?")){
				$stmt->bind_param('ds', $credito, $idUsuario);
				$stmt->execute();    // Ejecuta la consulta preparada.
				$stmt->close();
				return true;
			}
			return false;
		}
		return false;
	}

	if (isset($_POST['tarjeta'], $_POST['credito'])) {
		$tarjeta = trim($_POST['tarjeta']);
		$credito = trim($_POST['credito']);

		session_start();
		$email=$_SESSION["email"];
		$idUsuario=intval(idUsuario($email, $mysqli));
		if (esValida($tarjeta, $idUsuario, $mysqli)){ // Tarjeta valida
			if (tieneSaldo($tarjeta, $credito, $mysqli)){
				if(actualizar_saldo($tarjeta, $credito, $mysqli)){
					actualizar_credito($credito, $idUsuario, $mysqli);
					echo "Compra exitosa";
				}
			}
			else
				echo "No tiene saldo suficiente";
		}
		else // Tarjeta inválida
			echo "Número de tarjeta no válido";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();