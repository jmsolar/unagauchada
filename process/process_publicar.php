<?php

	include_once '../includes/db_connect.php';

	function cant_creditos($email, $mysqli){	
		if ($stmt=$mysqli->prepare("SELECT cantCreditos FROM Usuario WHERE email = ?")){
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
		if ($stmt=$mysqli->prepare("SELECT COUNT(idUsuario) FROM Usuario u INNER JOIN Gauchada g ON g.idUsuario = u.idUsuario INNER JOIN ON Calificacion c ON c.idUsuario <> u.idUsuario WHERE u.email = ? LIMIT 1")) {
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

	function publicar($titulo, $descripcion, $fecVencimiento, $categoria,/*$imagen,*/ $ciudad, $email, $mysqli){
		$fnac=date("Y-m-d", strtotime($fecVencimiento));
		$res=intval(idUsuario($email, $mysqli));
		if ($stmt=$mysqli->prepare("INSERT INTO Gauchada(`titulo`, `descripcion`, `fechaCreacion`, `fechaVencimiento`, `imagen`, `ciudad`, `estado`, `idUsuario`, `idCandidato`) VALUES('$titulo', '$descripcion', CURDATE(), '$fecVencimiento', NULL, '$ciudad', 'activa', ?, NULL)")){
			$stmt->bind_param('i', $res);
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();
			return true;
		} 
		else
			return false;
	}

	function actualizar_creditos($email, $mysqli){
		$cred_act=cant_creditos($email, $mysqli) - 1;		

		if ($stmt=$mysqli->prepare("UPDATE Usuario SET `cantCreditos` = ? WHERE email = ?")){
			$stmt->bind_param('is', $cred_act, $email);  // Une $email al parámetro.
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->close();
			return true;
		}
		return false;
	}

	function existe_categoria($titulo, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT idCategoria FROM Categoria WHERE titulo = ?")){
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
		$stmt=$mysqli->prepare("SELECT idGauchada FROM Gauchada WHERE idUsuario = ? ORDER BY idGauchada DESC LIMIT 1");
		$stmt->bind_param('i', $idUsuario);  // Une $email al parámetro.
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
		$stmt->bind_result($idGauchada);
		$stmt->fetch();
		$stmt->close();
		return $idGauchada;
	}

	function ult_categoria($mysqli){
		$stmt=$mysqli->prepare("SELECT idCategoria FROM Categoria ORDER BY idCategoria DESC LIMIT 1");
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
		$stmt->bind_result($idCategoria);
		$stmt->fetch();
		$stmt->close();
		return $idCategoria;
	}

	function cargar_categoria($titulo, $email, $mysqli){		
		$idUsuario=intval(idUsuario($email, $mysqli));
		$idCategoria=existe_categoria($titulo, $mysqli);		
		if ( $idCategoria < 1){//No existe la categoria			
			if($stmt=$mysqli->prepare("INSERT INTO Categoria(`titulo`) VALUES (?)")){
				$stmt->bind_param('s', $titulo);
				$stmt->execute();    // Ejecuta la consulta preparada
				$stmt->close();
			}
		}
		$idGauchada=ult_gauchada($idUsuario, $mysqli);
		$idCategoria=ult_categoria($mysqli);
		if ($stmt=$mysqli->prepare("INSERT INTO CategoriaGauchada(`idGauchada`, `idCategoria`) VALUES (?, ?)")){
			$stmt->bind_param('ii', $idGauchada, $idCategoria);
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();		
		}
	}

	if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecVencimiento'], $_POST['categoria'], $_POST['ciudad'])) {
		$titulo=trim($_POST['titulo']);
		$descripcion=trim($_POST['descripcion']);
		$fecVencimiento=trim($_POST['fecVencimiento']);
		$ciudad=trim($_POST['ciudad']);
		$categoria=trim($_POST['categoria']);
		/*$imagen = trim($_POST['imagen']);*/		
		
		session_start();
		$email=$_SESSION["email"];

		if (tiene_credito($email, $mysqli)){
			if (!tiene_publicacion_pendiente($email, $mysqli)){
				if (publicar($titulo, $descripcion, $fecVencimiento, $categoria,/*$imagen,*/ $ciudad, $email, $mysqli)){
					actualizar_creditos($email, $mysqli);
					cargar_categoria($categoria, $email, $mysqli);
					echo "Publicacion exitosa";
					return true;
				}
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