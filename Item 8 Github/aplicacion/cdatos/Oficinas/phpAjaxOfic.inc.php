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
			case 'addOfi':
				// Armamos el query

                                
                            
				$query = sprintf("INSERT INTO sunarp_oficina
								 SET codigo='%s', fk_area=%d, nombre='%s', anexo='%s', piso='%s', descripcion='%s'",
								 $_POST['of_codigo'],$_POST['of_codigoa'],$_POST['of_nombre'],$_POST['of_anexo'],$_POST['of_piso'],$_POST['of_descripcion']);
                                
                                

				// Ejecutamos el query
				$resultadoQuery = $mysqli -> query($query);

                                    
				// Obtenemos el id de user para edición
				$id_ofiOK = $mysqli -> insert_id;

				if($resultadoQuery == true){
                      $respuestaOK = true;
                     $mensajeError = "Se ha agregado el registro correctamente";
                      $contenidoOK = consultaOficinas($mysqli);
                     
                                        
					
				}
				else{
					$mensajeError = "No se puede guardar el registro en la base de Datos";
				}

			break;
			//Editar Usuario
			case 'editOfi':
				// Armamos el query
                        
				$query = sprintf("UPDATE sunarp_oficina
								 SET codigo='%s', fk_area=%d, nombre='%s',anexo='%s', piso='%s', descripcion='%s' WHERE id_oficina=%d LIMIT 1",
								  $_POST['of_codigo'],$_POST['of_codigoa'],$_POST['of_nombre'],$_POST['of_anexo'],$_POST['of_piso'],$_POST['of_descripcion']);
								
				// Ejecutamos el query
                            
                                
                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaOficinas($mysqli);

				}else{
					$mensajeError = 'No se ha actualizado el registro';
				}


			break;
                        
                        //Borrar Usuario
			case 'delOfi':
				// Armamos el query
                $query = sprintf("DELETE FROM sunarp_oficina
								  WHERE id_oficina = %d LIMIT 1",
                     $_POST['id_oficina']);

                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha borrado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaOficinas($mysqli);

				}else{
					$mensajeError = 'No se ha borrado el registro';
				}


			break;

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