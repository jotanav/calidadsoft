<?php

ini_set("display_errors", false);
// Incluimos nustro script php de funciones y conexión a la base de CDatos
include('CDatos/mainFunctions.inc.php');


if(isset($_POST["loguear"]) and $_POST["loguear"]=="si")
{
    $usuario = $_POST["email"];
    $password = $_POST["password"];
    logueo($mysqli,$usuario,$password);

}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion Pedidos - SUNARP</title>

    <!-- Core CSS - Include with every page -->
    <link href="CPresentacion/css/bootstrap.min.css" rel="stylesheet">
    <link href="CPresentacion/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="CPresentacion/css/sb-admin.css" rel="stylesheet">

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Acceso a Sistema SUNARP</h3>
                    </div>
                    <div class="panel-body">
                        <form  id="login" name="login" method="post" action="">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                              <!--   <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div> -->
								  <input type="hidden" name="loguear" value="si">
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="#" id="btnlogin" onClick="submitDetailsForm();" class="btn btn-lg btn-success btn-block">Ingresar</a>
								<!-- <button id="btnlogin" type="button" class="btn btn-success" onClick="submitDetailsForm();">Ingresar</button> -->
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts - Include with every page -->
    <script src="CPresentacion/js/jquery-1.10.2.js"></script>
    <script src="CPresentacion/js/bootstrap.min.js"></script>
    <script src="CPresentacion/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script language ="javascript" type = "text/javascript" >
 
 function submitDetailsForm()
        {
            $("#login").submit();
        }
    </script>
	
    <!-- SB Admin Scripts - Include with every page -->
    <script src="CPresentacion/js/sb-admin.js"></script>

</body>

</html>
