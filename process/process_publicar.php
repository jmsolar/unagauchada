<?php

	include_once '../includes/db_connect.php';
	include '../includes/session.php';

	function cant_creditos($email, $mysqli){	
		if ($stmt=$mysqli->prepare("SELECT cantCreditos FROM usuario WHERE email = ?")){
			$stmt->bind_param('s', $email);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($cantCreditos);
			$stmt->fetch();
			$stmt->close();
			return $cantCreditos;
		}		
	}

    function tiene_credito($email, $mysqli) {
		if (cant_creditos($email, $mysqli) > 0)//Tiene creditos
			return true;
		else //No tiene creditos
			return false;
	}

    function tiene_publicacion_pendiente($email, $mysqli) {
		if ($stmt=$mysqli->prepare("SELECT COUNT(idUsuario) FROM usuario u INNER JOIN Gauchada g ON g.idUsuario = u.idUsuario INNER JOIN ON Calificacion c ON c.idUsuario <> u.idUsuario WHERE u.email = ? LIMIT 1")) {
			$stmt->bind_param('s', $email);  // Une $email al parámetro.
			$stmt=$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			if ($stmt >= 1)
				return true; //Tiene al menos un credito
			else
				return false; //No tiene creditos
		} else 
			return false;
		return true;
	}

	function idUsuario($email, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT idUsuario FROM usuario WHERE email = ? LIMIT 1")) {
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

	function publicar($titulo, $descripcion, $fecVencimiento, $categoria,$imagen, $ciudad, $email, $mysqli){
		$fnac=date("Y-m-d", strtotime($fecVencimiento));
		$res=intval(idUsuario($email, $mysqli));
		if ($stmt=$mysqli->prepare("INSERT INTO gauchada (`titulo`, `descripcion`, `fechaCreacion`, `fechaVencimiento`, `imagen`, `ciudad`, `estado`, `idCategoria`, `idUsuario`, `idCandidato`) VALUES ('$titulo', '$descripcion', CURDATE(), '$fecVencimiento', '$imagen', '$ciudad', 'activa', '$categoria', '$res', NULL)")){
			echo "INSERT INTO Gauchada (`titulo`, `descripcion`, `fechaCreacion`, `fechaVencimiento`, `imagen`, `ciudad`, `estado`, `idUsuario`, `idCandidato`) VALUES ('$titulo', '$descripcion', CURDATE(), '$fecVencimiento', '$imagen', '$ciudad', 'activa', $res, NULL)";
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();
			return true;
		} 
		else
			return false;
	}

	function actualizar_creditos($email, $mysqli){
		$cred_act=cant_creditos($email, $mysqli) - 1;		

		if ($stmt=$mysqli->prepare("UPDATE usuario SET `cantCreditos` = ? WHERE email = ?")){
			$stmt->bind_param('is', $cred_act, $email);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			return true;
		}
		return false;
	}

	function existe_categoria($titulo, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT idCategoria FROM categoria WHERE titulo = ?")){
			$stmt->bind_param('s', $titulo);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idCategoria);
			$stmt->fetch();
			$stmt->close();
			return $idCategoria;
		}
	}

	function ult_gauchada($idUsuario, $mysqli){		
		$stmt=$mysqli->prepare("SELECT idGauchada FROM gauchada WHERE idUsuario = ? ORDER BY idGauchada DESC LIMIT 1");
		$stmt->bind_param('i', $idUsuario);  // Une $email al parámetro.
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
		$stmt->bind_result($idGauchada);
		$stmt->fetch();
		$stmt->close();
		return $idGauchada;
	}

	function ult_categoria($mysqli){
		$stmt=$mysqli->prepare("SELECT idCategoria FROM categoria ORDER BY idCategoria DESC LIMIT 1");
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
		$stmt->bind_result($idCategoria);
		$stmt->fetch();
		$stmt->close();
		return $idCategoria;
	}

	if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecVencimiento'], $_POST['filtrarCategoria'], $_POST['ciudad'])) {
		echo "1";
		if(isset($_POST['image']) && ($_POST['image'] != 'undefined'))
			$imagen = trim($_POST['image']);
		else
			$imagen = '../img/logo.png';
		$titulo=trim($_POST['titulo']);
		$descripcion=trim($_POST['descripcion']);
		$fecVencimiento=trim($_POST['fecVencimiento']);
		$ciudad=trim($_POST['ciudad']);
		$categoria=trim($_POST['filtrarCategoria']);	
		
		Session::init();
		$email=Session::get("email");

		if (tiene_credito($email, $mysqli)){
			echo "4";
			if (!tiene_publicacion_pendiente($email, $mysqli)){
				echo "5";
				if (publicar($titulo, $descripcion, $fecVencimiento, $categoria, $imagen, $ciudad, $email, $mysqli)){
					echo "6";
					actualizar_creditos($email, $mysqli);
					echo "Publicacion exitosa";
				} else
					echo "Ocurrio un error";
			}
			else
				echo "Tiene publicaciones pendientes";
		}
		else
			echo "No tiene credito";
	} 
	else // Las variables POST correctas no se enviaron a esta página.
		echo "Solicitud no válida";
	exit();