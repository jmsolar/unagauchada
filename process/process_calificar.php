<?php
include_once '../includes/db_connect.php';
include_once '../includes/session.php';
Session::init();

$calificacion = $mysqli->real_escape_string($_POST['calificacion']);
$gauchada = $mysqli->real_escape_string($_POST['gauchada']);
$comentario = $mysqli->real_escape_string($_POST['comentario']);

if(!$calificacion || !$gauchada || !$comentario){
	header('HTTP/1.1 500 Internal Server');
	echo "Por favor complete todos los campos";
};

$query = "SELECT * FROM gauchada g WHERE g.idGauchada = '".$gauchada ."'";

$gauchada = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);
$idGauchada = $gauchada[0]['idGauchada'];
$idUsuarioAnunciante = $gauchada[0]['idUsuario'];
$idPostulante = $gauchada[0]['idCandidato'];

//update user postulante
if($calificacion == '1'){
	$query = "UPDATE usuario SET reputacion = reputacion + 1, cantCreditos = cantCreditos + 1 WHERE idUsuario = $idPostulante";
} else if ($calificacion == '-1'){
	$query = "UPDATE usuario SET reputacion = reputacion - 2 WHERE idUsuario = $idPostulante";
}

if($stmt = $mysqli->prepare($query)){
	$stmt->execute();    // Ejecuta la consulta preparada
	$stmt->close();

	if($stmt=$mysqli->prepare("UPDATE gauchada SET estado='calificada' WHERE idGauchada=$idGauchada")){
		$stmt->execute();    // Ejecuta la consulta preparada
		$stmt->close();
		if($stmt=$mysqli->prepare("INSERT INTO calificacion (`fecha`, `comentario`, `puntos`, `idGauchada`, `idUsuarioAnunciante`, `idUsuarioCandidato`) VALUES (NOW(), '".$comentario."', $calificacion, $idGauchada, $idUsuarioAnunciante, $idPostulante)")){
			$stmt->execute();    // Ejecuta la consulta preparada
			$stmt->close();


			/*mail a postulante*/
		$to = 'akkavoskuil@gmail.com'; 
		$subject = 'Te calificaron!'; 
		$message = 'Completaste la Gauchada '.$gauchada[0]['titulo'].' y el anunciante califico tu ayuda. Entra a tu perfil para ver la calificacion';
		$headers = 'From: webmaster@example.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); mail($to, $subject, $message, $headers);
			return true; 
		}
	}

}
return false; 