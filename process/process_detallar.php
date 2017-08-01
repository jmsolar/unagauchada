<?php

	include_once '../includes/db_connect.php';
	
	$detalleId = $_GET['id'];

	if (empty($detalleId)) {
		die('Pagina incorrecta');
	}

	$detalleId = $mysqli->real_escape_string($detalleId);

	//gauchadas
	$stmt=$mysqli->prepare("SELECT gauchada.*, usuario.*, categoria.titulo AS categoria FROM gauchada INNER JOIN usuario ON gauchada.idUsuario = usuario.idUsuario INNER JOIN categoria ON categoria.idCategoria = gauchada.idCategoria WHERE idGauchada = '".$detalleId ."'");
	$stmt->execute();
	$gauchadasRes = $stmt->get_result();
	$gauchadasRes = $gauchadasRes->fetch_object();

	$nombreCompleto=$gauchadasRes->nombre . " " . $gauchadasRes->apellido;

	$image = empty($gauchadasRes->imagen) ? 'logo.png' : $gauchadasRes->imagen;

	//postulantes
	$query = "SELECT p.*, u.*, count(*) as nroPostulantes FROM postulante p 
	INNER JOIN gauchada g INNER JOIN usuario u
	ON p.idGauchada = g.idGauchada AND u.idUsuario = p.idPostulante
	WHERE g.idGauchada = '".$detalleId ."'";

	$postulantesRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

	$nroPostulantes = sizeof($postulantesRes > 0) ? $postulantesRes[0]['nroPostulantes'] : 0;

	//comentarios

	$query = "SELECT c.*, u.* FROM comentario c 
	INNER JOIN gauchada g INNER JOIN usuario u
	ON c.idGauchada = g.idGauchada AND u.idUsuario = c.idUsuarioComentario
	WHERE g.idGauchada = '".$detalleId ."'";

	$comentariosRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);
	
	//Postulantes
	$query = "SELECT p.*, u.* FROM postulante p 
	INNER JOIN usuario u
	ON p.idPostulante = u.idUsuario
	WHERE p.idGauchada = '".$detalleId ."'";

	$postulanteRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

	//Rangos
	function getRanking($reputacion, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT r.nombre FROM rangoreputacion r
		WHERE r.puntosIni <= $reputacion AND r.puntosFin >= $reputacion")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($rankingRes);
			$stmt->fetch();
			return $rankingRes;
		}
		return 'Sin definir';
	}