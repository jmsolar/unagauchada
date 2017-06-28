<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>Inicio - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
    </head>
    <?php require_once( "../includes/functions.php" );?>
    <body class="home-container">
        <?php error_reporting(0); ?>
        <!-- Modal -->
        <form id="register-form" method="POST" name="register_form" enctype="multipart/form-data">
            <div class="modal fade" id="registrar" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Registrar</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                                    </div>
                                    <div id="error-format-email"></div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellido">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                                    </div>
                                </div>
                                <div class="col col-md-6 col-sm-6 align-center">
                                    <div class="form-group">
                                        <label for="foto">Foto de perfil</label>
                                        <input name="foto" id="foto" type="file">
                                    </div>
                                </div>                            
                            </div>
                            <div class="row">
                                <div class="col col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                    </div>
                                </div>
                                <div class="col col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="repeat-pass">Repetir contraseña</label>
                                        <input type="password" class="form-control" id="repeat-pass" name="repeat-pass" placeholder="Repetir contraseña" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="fnac">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fnac" value="2017-01-04" name="fnac" required>
                                    </div>
                                </div>
                                <div class="col col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="N° de teléfono" onkeypress="return validar_telefono(event)" required>
                                    </div>
                                    <div id="error-register"></div>
                                </div>
                            </div>
                        </div>                   
                        <div class="modal-footer">
                            <button type="button" id="btn-cancelar" class="btn btn-secondary" data-dismiss="modal" onclick="limpiar_campos()">Cancelar</button>
                            <button type="submit" id="btn-registrar" class="btn btn-warning">Registrar</button>
                        </div>                
                    </div>
                </div>
            </div>
        </form>

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
                <div class="col col-md-5">
                    <?php ver_listado();?>
                </div>
                <div class="col col-md-7">
                    <div class="row">
                        <div class="col col-md-12">
                            <h1>Haciendo Gauchadas</h1>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
                            <hr>
                            <?php if(Session::get('conectado') !== 1): ?>
                                    <button type="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#registrar">Registrar ahora</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12">
                            <h3>Herramientas de búsqueda</h3>
                            <form id="busqueda-form" method="GET" name="buscar_form" action="../html/busqueda.php">
                                <div class="row">
                                    <div class="col col-md-5">
                                        <input type="text" class="form-control" id="buscar" name="busqueda" placeholder="Buscar..">
                                    </div>
                                    <div class="col col-md-7">
                                        <input type="submit" class="btn btn-success" id="botonBuscar" value="Buscar">
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="col col-md-12">
                                        <h4>Filtros</h4>
                                        <div class="row">
                                            <div class="col col-md-12">
                                                <div class="row">
                                                    <div class="col col-md-12">
                                                        <div class="form-group">
                                                            <label for="cate">Categoría:</label>
                                                            <div class="row">
                                                                <div class="col col-md-5">
                                                                    <?php mostrar_categorias(); ?>
                                                                </div>
                                                                <div class="col col-md-7">
                                                                    <input type="checkbox" id="cate" name="checkboxCategoria" value="categoria"/>
                                                                    <p class="text-danger">* habilita el check para filtrar por categoría</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row margin-top-20">
                                            <div class="col col-md-12">
                                                <div class="row">
                                                    <div class="col col-md-12">
                                                        <div class="form-group">
                                                            <label for="ciu">Ciudad:</label>
                                                            <div class="row">
                                                                <div class="col col-md-5">
                                                                    <?php mostrar_ciudades(); ?>
                                                                </div>
                                                                <div class="col col-md-7">
                                                                    <input type="checkbox" id="ciud" name="checkboxCiudad" value="ciudad"/>
                                                                    <p class="text-danger">* habilita el check para filtrar por ciudad</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/registrar.js"></script>
    <script src="../js/detallar.js"></script>
    <script src="../js/cerrarSesion.js"></script>
    <script src="../js/filtrar.js"></script>
    </body>
</html>