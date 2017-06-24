<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inicio - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">

    </head>
    <?php require( "../includes/functions.php" );?>
    <body class="home-container">
        <nav class="navbar navbar-inverse no-border-radius">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="home.php">Una gauchada</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="login.html">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
				<div class="col col-md-12">
					<h4><b>Filtrar por</b></h4>
					<form id="filtrar-form" class="row" name="filtar_form">
						<div class="float-left">
							<label class="checkbox-inline"><input type="checkbox" id="opcion1" name="checkbox" value="categoria">Categoría</label>
							<label class="checkbox-inline"><input type="checkbox" id="opcion2" name="checkbox" value="ciudad">Ciudad</label>
						</div>
						<div class="float-right">
							<button type="submit" class="btn btn-primary" id="botonFiltrar">Filtrar</button>
						</div>
						<div id="error-filtrar"></div>
					</form>
					<hr>
					<div class="row">
						<?php verResultados($_GET["busqueda"]); ?>
            		</div>
            		<hr>
            		<form action="home.php">
    					<input type="submit" class="btn btn-warning float-right" value="Volver al inicio">
					</form>
					<form id="busqueda-form" class="float-left" method="GET" name="buscar_form" action="../html/busqueda.php">
                        <div class="row">
                            <div class="col col-md-9">
                                <input type="text" class="form-control" id="buscar" name="busqueda" placeholder="Buscar..">
                            </div>
                        	<div class="col col-md-3">
                            	<input type="submit" class="btn btn-success margin-sides-16" id="botonBuscar" value="Buscar">
                        	</div>
                        </div> 
					</form>
            	</div>
            </div>
        </div>
    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/login.js"></script>
    <script src="../js/filtrar.js"></script>
    </body>
</html>