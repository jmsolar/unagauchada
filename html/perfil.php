<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Detalle de gauchada - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">

        <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/detallar.js"></script>
    </head>
    <?php require_once( "../includes/functions.php" );?>
    <?php require( "../process/process_perfil.php" );?>    
    <?php Session::init(); ?>
    <?php $idPerfil = $_GET['id']; ?>


    <body>
        <nav class="navbar navbar-inverse no-border-radius">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                     </button>
                  <a class="navbar-brand" href="home.php">Una Gauchada</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php 
                            usuario_logueado();
                            ver_opcion_usuario(); 
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row"> 
                <div class="col col-md-3">
                    <img src="../img/<?=$image ?>" class="img-fluid" alt="Responsive image" width="100%">
                </div>
                <div class="col col-md-9">
                    <h4>Datos Personales</h4>
                    <div class="row"><b>Nombre: </b><?= $perfil->nombre ?></div>
                    <div class="row"><b>Apellido: </b><?= $perfil->apellido ?></div>
                    <div class="row"><b>Fecha de Nacimiento: </b><?= $perfil->fechaNacimiento?></div>
                    <div class="row"><b>Ranking: </b><?=calulateRanking($perfil->reputacion, $mysqli)?> </div>
                    <hr>
                    <?php if (Session::get('conectado') == 1 && $perfil->email == Session::get('email')): ?>
                    <h4>Datos de Contacto</h4>
                    <div class="row"><b>Email: </b><?= $perfil->email ?></div>
                    <div class="row"><b>Telefono: </b><?= $perfil->telefono ?></div>
                <?php endif;?>
                </div>
            </div>
            <hr>



            <div class="row">
                <h4>Calificaciones</h4>
                <hr>
            </div>
            <?php if(sizeof($calificacionesRes)): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="25%">Titulo Gauchada</th>
                        <th width="15%">Calificacion</th>
                        <th width="50%">Comentario</th>
                        <th width="10%">Fecha Cierre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($calificacionesRes as $calificacion): ?>
                    <tr>
                        <td><?=$calificacion['titulo']?></td>
                        <td><?=$calificacion['puntos']?></td>
                        <td><?=$calificacion['comentario']?></td>
                        <td><?=$calificacion['fecha']?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div>No hay calificaciones para este usuario</div>
                <br/>
            <?php endif; ?>

        </div>
    </body>
</html>