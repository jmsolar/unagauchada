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

	function esValida($tarjeta, $idUsuario, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT numero FROM Tarjeta WHERE idUsuario = '$idUsuario'")){
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($numero);
				$stmt->fetch();
				$stmt->close();			
				if ($numero == $tarjeta)
					return true;
				else
					return false;
		}
	}

	function codigoValido($codigo, $idUsuario, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT codSeguridad FROM Tarjeta WHERE idUsuario = '$idUsuario'")){
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($codSeguridad);
				$stmt->fetch();
				$stmt->close();			
				if ($codSeguridad == $codigo)
					return true;
				else
					return false;
		}
	}

	function datosValidos($tarjeta, $codigo, $idUsuario, $mysqli){
		if (esValida($tarjeta, $idUsuario, $mysqli)){
			if (codigoValido($codigo, $idUsuario, $mysqli))
				return true;
		}
		return false;
	}


	function tieneSaldo($tarjeta, $credito, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT saldo FROM Tarjeta WHERE numero = '$tarjeta'")){
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
		if ($stmt=$mysqli->prepare("SELECT saldo FROM Tarjeta WHERE numero = '$tarjeta'")){
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
		if ($stmt=$mysqli->prepare("UPDATE Tarjeta SET `saldo` = '$saldo' WHERE numero = '$tarjeta'")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			return true;
		}
		return false;
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

	function actualizar_credito($credito, $idUsuario, $mysqli){
		$creditosAct=creditosActual($idUsuario, $mysqli);
		$total=$credito + $creditosAct;
		if ($stmt=$mysqli->prepare("UPDATE Usuario SET `cantCreditos` = $total WHERE idUsuario = '$idUsuario'")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			return true;
		}
		return false;
	}

	function agregar_ingreso($credito, $idUsuario, $mysqli){
		$monto=$credito * 10;
		if ($stmt=$mysqli->prepare("INSERT INTO Ingreso (`fecha`, `monto`, `idUsuario`) VALUES (CURDATE(), '$monto','$idUsuario')")){
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();
			return true;
		} 
		else
			return false;
	}

	if (isset($_POST['tarjeta'], $_POST['credito'], $_POST['codigo'])) {
		$tarjeta = trim($_POST['tarjeta']);
		$credito = trim($_POST['credito']);
		$codigo = trim($_POST['codigo']);

		Session::init();
		$email=Session::get("email");
		$idUsuario=intval(idUsuario($email, $mysqli));
		if (datosValidos($tarjeta, $codigo, $idUsuario, $mysqli)){
			if (tieneSaldo($tarjeta, $credito, $mysqli)){
				if(actualizar_saldo($tarjeta, $credito, $mysqli)){
					if (actualizar_credito($credito, $idUsuario, $mysqli)){
						if (agregar_ingreso($credito, $idUsuario, $mysqli)){
							echo "Compra exitosa";
						}
					}
				}
			}
			else
				echo "No tiene saldo suficiente";
		}
		else
			echo "Verifique los datos ingresados";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();
