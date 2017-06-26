<?php

	include_once '../includes/db_connect.php';
	
	$detalleId = $_GET['id'];

	if (empty($detalleId)) {
		die('Pagina incorrecta');
	}

	$detalleId = $mysqli->real_escape_string($detalleId);

	//gauchadas
	$stmt=$mysqli->prepare("SELECT * FROM gauchada INNER JOIN usuario USING (idUsuario)  WHERE idGauchada = '".$detalleId ."'");
	$stmt->execute();
	$gauchadasRes = $stmt->get_result();
	$gauchadasRes = $gauchadasRes->fetch_object();

	$nombreCompleto=$gauchadasRes->nombre . " " . $gauchadasRes->apellido;

	$image = empty($gauchadasRes->imagen) ? 'logo.png' : $gauchadasRes->imagen;

	//postulantes
	$query = "SELECT p.*, u.*, count(*) as nroPostulantes FROM postulante p 
	INNER JOIN gauchada g INNER JOIN usuario u
	ON p.idGauchada = g.idGauchada AND u.idUsuario = p.idUsuario
	WHERE g.idGauchada = '".$detalleId ."'";

	$postulantesRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

	$nroPostulantes = sizeof($postulantesRes > 0) ? $postulantesRes[0]['nroPostulantes'] : 0;

	//comentarios

	$query = "SELECT c.*, u.* FROM comentario c 
	INNER JOIN gauchada g INNER JOIN usuario u
	ON c.idGauchada = g.idGauchada AND u.idUsuario = c.idUsuario
	WHERE g.idGauchada = '".$detalleId ."'";

	$comentariosRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);