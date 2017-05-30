<?php

	include_once '../includes/db_connect.php';
	
	function detalle_gauchada(){
		$tit=$_SESSION["titulo"];
		$desc=$_SESSION["descripcion"];
		$fVenc=$_SESSION["fVencimiento"];
		$nom=$_SESSION["nombre"];
		$ape=$_SESSION["apellido"];
		$nombreCompleto=$nom + " " + $ape;
		$ciu=$_SESSION["ciudad"];
		$post=$_SESSION["postulantes"];

		echo '
        <div class="list-group ">
            <h4 class="list-group-item-heading padding-16">'.$tit.'</h4>
				<p class="list-group-item-text">
					<div class="row">
						<div class="col col-md-10">
							<div class="row">'.$desc.'</div>
							<div class="row">Fecha de vencimiento: '.$fVenc.'</div>
							<div class="row"><div role="presentation">Postulantes <span class="badge">'.$post.'</span></div> </div>
							<div class="row">'.$nombreCompleto.'</div>
							<div class="row">'.$ciu.'</div>
						</div>
						<div class="col col-md-2">
							<img src="../img/logo.png" class="img-fluid"/>
						</div>
					</div>
				</p>
				<form action="../html/home.php">
    				<input type="submit" class="btn btn-warning" value="Volver al listado"/>
				</form>
		</div>';
	}