<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar la aplicación";
$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
include('../mainFunctions.inc.php');

// Validar conexión con la base de CDatos
if($errorDbConexion == false){
	// Validamos qe existan las variables post
	if(isset($_POST) && !empty($_POST)){
		// Verificamos las variables de acción
                        //AQUI ESTARAN LAS FUNCIONES DE EDITAR Y BORRAR
		switch ($_POST['accion']) {
			case 'addUsu':
				// Armamos el query

        				$query = sprintf("INSERT INTO sunarp_usuarios
								 SET codigo='%s', fk_Id_Tipo_Usuario=%d, fk_Id_Tipo_Oficina=%d, usuario='%s', passw='%s', ape_paterno='%s', ape_materno='%s', nombres='%s', dni='%s', direccion='%s', celular=%d, telefono=%d",
								 $_POST['usu_codigo'],$_POST['usu_tipousuario'],$_POST['usu_tipooficina'],$_POST['usu_usuario'],$_POST['usu_passw'],$_POST['usu_ape_pa'],$_POST['usu_ape_ma'],$_POST['usu_nom'],$_POST['usu_dni'],$_POST['usu_direc'],$_POST['usu_cel'],$_POST['usu_te']);


				// Ejecutamos el query
				$resultadoQuery = $mysqli -> query($query);

                                    
				// Obtenemos el id de user para edición
				$id_ofiOK = $mysqli -> insert_id;

				if($resultadoQuery == true){
                      $respuestaOK = true;
                     $mensajeError = "Se ha agregado el registro correctamente";
                      $contenidoOK = consultaUsuarios($mysqli);
                     
                                        
					
				}
				else{
					$mensajeError = "No se puede guardar el registro en la base de Datos";
				}

			break;
			//Editar Usuario
			case 'editUsu':
				// Armamos el query
                        
				$query = sprintf("UPDATE sunarp_usuarios
								 SET codigo='%s', fk_Id_Tipo_Usuario=%d, fk_Id_Tipo_Oficina=%d, usuario='%s', passw='%s', ape_paterno='%s', ape_materno='%s', nombres='%s', dni='%s', direccion='%s', celular=%d, telefono=%d
								 WHERE id_usuario=%d LIMIT 1",
 							$_POST['usu_codigo'],$_POST['usu_tipousuario'],$_POST['usu_tipooficina'],$_POST['usu_usuario'],$_POST['usu_passw'],$_POST['usu_ape_pa'],$_POST['usu_ape_ma'],$_POST['usu_nom'],$_POST['usu_dni'],$_POST['usu_direc'],$_POST['usu_cel'],$_POST['usu_te'],$_POST['id_usuario']);
				// Ejecutamos el query
                            
                                
                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaUsuarios($mysqli);

				}else{
					$mensajeError = 'No se ha actualizado el registro';
				}


			break;
                        
                        //Borrar Usuario
			case 'delUsu':
				// Armamos el query
             	$query = sprintf("DELETE FROM sunarp_usuarios
								  WHERE id_usuario=%d LIMIT 1",
								 $_POST['id_usuario']);
                        
                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha borrado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaUsuarios($mysqli);

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