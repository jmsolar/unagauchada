<?php
include_once '../includes/db_connect.php';
	
	$perfilId = $_GET['id'];

	if (empty($perfilId)) {
		die('Pagina incorrecta');
	}

	$userId = $mysqli->real_escape_string($perfilId);

	//gauchadas
	$stmt=$mysqli->prepare("SELECT * FROM usuario WHERE idUsuario = '".$userId ."'");
	$stmt->execute();
	$perfilRes = $stmt->get_result();
	$perfilRes = $perfilRes->fetch_object();

	$perfil = $perfilRes;
	$nombreCompleto=$perfil->nombre . " " . $perfil->apellido;

	$image = empty($perfil->fotoPerfil) ? 'profile_placeholder.jpg' : $perfil->fotoPerfil;

	/*Calificaciones*/
	$query = "SELECT c.*, g.titulo FROM calificacion c INNER JOIN gauchada g ON g.idGauchada = c.idGauchada WHERE c.idUsuarioCandidato = '".$userId ."'";

	$calificacionesRes = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

?>