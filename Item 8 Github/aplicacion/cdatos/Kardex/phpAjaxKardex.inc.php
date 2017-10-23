<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar la aplicación";
$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
include('../mainFunctions.inc.php');

//$statusTipoOK =  array("1" => "btn-success","0" => "btn-warning");
//$statusTiponombreOK = array("1" => "Activo","0" => "Suspendido");
 //$estado_perOK = array("2"=> "Administrador","1"=> "Usuario");
 
 
// Validar conexión con la base de CDatos
if($errorDbConexion == false){
	// Validamos qe existan las variables post
	if(isset($_POST) && !empty($_POST)){
		// Verificamos las variables de acción
                        //AQUI ESTARAN LAS FUNCIONES DE EDITAR Y BORRAR
		switch ($_POST['accion']) {

			case 'KardexProducto':
				// Armamos el query
                  $fechainicio = $_POST["fechainicio"];
                  $fechafin = $_POST["fechafin"];
                  $id_prod = $_POST["id_prod"];

                $contenidoOK = consultaKardexProducto($mysqli,$id_prod,$fechainicio,$fechafin);

                 
                 $respuestaOK = true;
                 $mensajeError = "Kardex ok";
                                                           
				
				

			break;
			//Editar Usuario
			

			default:
				$mensajeError = 'Esta acción no se encuentra disponible';
			break;
		}
	}
	else{
		$mensajeError = 'No se puede ejecutar la aplicación';
	}


}
else{
	$mensajeError = 'No se puede establecer conexión con la base de CDatos';
}

// Armamos array para convertir a JSON Y ENVIARLO AL MAINJavascript
$salidaJson = array("respuesta" => $respuestaOK,
					"mensaje" => $mensajeError,
					"contenido" => $contenidoOK);

echo json_encode($salidaJson);
?>