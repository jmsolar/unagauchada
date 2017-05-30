<?php

	function ver_listado(){
		include_once 'db_connect.php';

		if ($stmt=$mysqli->prepare("SELECT COUNT(idGauchada) FROM Gauchada WHERE estado = ?")){
			$estado='activa';
			$stmt->bind_param('s', $estado);
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idGauchada);
			$stmt->fetch();
			$stmt->close();
			if ($idGauchada == 0){
				echo '<div class="alert alert-warning"><strong>Información: </strong><span>En este momento no hay publicaciones activas</span></div>';
			}
			else{				
				if ($stmt=$mysqli->prepare("SELECT idGauchada, titulo, descripcion, fechaVencimiento, imagen, ciudad, u.nombre, u.apellido, u.idUsuario FROM Gauchada g INNER JOIN Usuario u ON g.idUsuario=u.idUsuario WHERE estado = ? ORDER BY fechaCreacion")){
					$estado='activa';
					$stmt->bind_param('s', $estado);
					$stmt->execute();    // Ejecuta la consulta preparada.
					$res = $stmt->get_result();
					
					while($row = $res->fetch_assoc())
						$rows[] = $row;

					foreach($rows as $row){
						$idGauchada=$row["idGauchada"];
						$idUsuario=$row["idUsuario"];						
						if ($stmt=$mysqli->prepare("SELECT COUNT(g.idGauchada) as postulantes FROM Postulante p INNER JOIN Gauchada g ON p.idGauchada=g.idGauchada WHERE p.idGauchada = ?")){						
							$stmt->bind_param('i', $idGauchada);
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($postulantes);
							$stmt->fetch();
							echo '<div class="col col-md-12 background-color-grey padding-16 margin-bottom-16">            
		                    		<div class="list-group">
			                        	<h4 class="list-group-item-heading padding-16">'.$row["titulo"].'</h4>
			                       		<div class="list-group-item-text padding-sides-16">
			                            	<div class="row">
			                                	<div class="col col-md-8">
			                                    	<div class="row">Fecha de vencimiento: '.$row["fechaVencimiento"].'</div>
				                                    <div class="row"><div>Postulantes <span class="badge">'.$postulantes.'</span></div></div>
				                                    <div class="row">Publicado por: '.$row["nombre"].''; echo' '.$row["apellido"].'</div>
				                                    <div class="row">Ciudad: '.$row["ciudad"].'</div>
				                                </div>
				                                <div class="col col-md-4 padding-16 padding-top-0">
				                                    <img src="../img/logo.png" class="size-75 img-fluid"/>
				                                </div>
				                            </div>
				                        </div>
				                        <hr class="no-margin">
				                    </div>';
				            echo "
				            <form id='detalle-form' name='detalle_form' action='../html/detalle.php' method='POST'>
				                <button type='submit' class='btn btn-default'>Ver gauchada</button>
				             </form>";
				            echo "</div>";
				        }
			        }
				}
			}
		}
	}	


	/*onclick='enviar_datos(\"$titulo\", \"$descripcion\", \"$fVencimiento\", \"$nombre\", \"$apellido\", \"$ciudad\", \"$postulantes\")'*/
	/*
				            $titulo=$row["titulo"];
				            $descripcion=$row["descripcion"];
				            $fVencimiento=$row["fechaVencimiento"];
				            $nombre=$row["nombre"];
				            $apellido=$row["apellido"];
				            $ciudad=$row["ciudad"];							
							$_SESSION["titulo"]=$titulo;
							$_SESSION["descripcion"]=$descripcion;
							$_SESSION["fVencimiento"]=$fVencimiento;
							$_SESSION["nombre"]=$nombre;
							$_SESSION["apellido"]=$apellido;
							$_SESSION["ciudad"]=$ciudad;
							$_SESSION["postulantes"]=$postulantes;
	*/