<?php
include_once '../includes/db_connect.php';
include_once '../includes/session.php';
Session::init();

$postulante = $mysqli->real_escape_string($_POST['postulante']);
$gauchada = $mysqli->real_escape_string($_POST['gauchada']);
$anunciante = Session::get('userid');

if (!$postulante || !$gauchada) {
	header('HTTP/1.1 500 Internal Server');
	echo "No se pudo aceptar al postulante";
}
else if ($stmt=$mysqli->prepare("UPDATE gauchada
SET estado='cerrada', idCandidato=$postulante
WHERE idGauchada=$gauchada")) {
	$stmt->execute();    // Ejecuta la consulta preparada.
	$stmt->store_result();
	$stmt->close();
	echo "Postulante aceptado con exito";
}

$emailPublicante = Session::get('email'); 

$query = "SELECT * FROM usuario u WHERE u.idUsuario = '".$postulante ."'";

	$postulante = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

$query = "SELECT * FROM usuario u WHERE u.idUsuario = '".$anunciante ."'";

	$anunciante = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

$query = "SELECT * FROM gauchada g WHERE g.idGauchada = '".$gauchada ."'";

	$gauchadaActual = mysqli_fetch_all($mysqli->query($query), MYSQLI_ASSOC);

$emailPostulante = $postulante[0]['email'];

/*SEND EMAILS*/
/*mail a postulante*/
$to = 'akkavoskuil@gmail.com'; 
		$subject = 'Fuiste seleccionado para hacer una Gauchada!'; 
		$message = 'Has sido seleccionado como postulante para la Gauchada '.$gauchadaActual[0]['titulo'].'. \r\n Aca te pasamos los datos de contacto del anunciante: \r\n \r\n'.$anunciante[0]['nombre'].' '.$anunciante[0]['apellido'].'\r\n Telefono:'.$anunciante[0]['telefono'].'\r\n Email: '.$anunciante[0]['email'];
		$headers = 'From: webmaster@example.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); mail($to, $subject, $message, $headers);

/*mail a publicante*/
$to = 'akkavoskuil@gmail.com'; 
		$subject = 'Has seleccionado un postulante para tu Gauchada!'; 
		$message = 'Has seleccionado un postulante para tu Gauchada '.$gauchadaActual[0]['titulo'].'. \r\n Aca te pasamos sus datos de contacto: \r\n \r\n'.$postulante[0]['nombre'].' '.$postulante[0]['apellido'].'\r\n Telefono:'.$postulante[0]['telefono'].'\r\n Email: '.$postulante[0]['email']; 
		$headers = 'From: webmaster@gauchada.com' . "\r\n" . 'Reply-To: webmaster@gauchada.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); mail($to, $subject, $message, $headers);

return true;
?>