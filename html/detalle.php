<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Detalle de gauchada - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
    </head>
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
                  <a class="navbar-brand" href="home.php">Una Gauchada</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- <li><a href="login.html">Login</a></li> -->
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col col-md-8 offset-md-2">
                    <?php 
                        require( "../process/process_detallar.php" );
                        detalle_gauchada();
                    ?>
                </div>
            </div>
        </div>
    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/detallar.js"></script>
    </body>
</html>