<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar la aplicación";
$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
include('../mainFunctions.inc.php');

$statusTipoOK =  array("1" => "btn-success","0" => "btn-warning");
$statusTiponombreOK = array("1" => "Activo","0" => "Suspendido");
 $estado_perOK = array("2"=> "Administrador","1"=> "Usuario");
 
 
// Validar conexión con la base de CDatos
if($errorDbConexion == false){
	// Validamos qe existan las variables post
	if(isset($_POST) && !empty($_POST)){
		// Verificamos las variables de acción
                        //AQUI ESTARAN LAS FUNCIONES DE EDITAR Y BORRAR
		switch ($_POST['accion']) {
			case 'addUser':
				// Armamos el query
				$query = sprintf("INSERT INTO tbl_usuarios
								 SET usr_nombre='%s', usr_password='%s', usr_status='%s', usr_permisos='%s'",
								 $_POST['usr_nombre'],$_POST['usr_password'],$_POST['usr_status'],$_POST['usr_permisos']);

				// Ejecutamos el query
				$resultadoQuery = $mysqli -> query($query);


				// Obtenemos el id de user para edición
				$id_userOK = $mysqli -> insert_id;

				if($resultadoQuery == true){
					$respuestaOK = true;
					$mensajeError = "Se ha agregado el registro correctamente";
					$contenidoOK = '
						<tr>
							<td>'.$_POST['usr_nombre'].'</td>
							<td>'.$_POST['usr_password'].'</td>
							<td>'.$estado_perOK[$_POST['usr_permisos']].'</td>
							<td class="centerTXT"><span class="btn btn-mini '.$statusTipoOK[$_POST['usr_status']].'">'.$statusTiponombreOK[$_POST['usr_status']].'</span></td>
							<td class="centerTXT"><a href="'.$id_userOK.'"><img src="images/editar.png" alt="editar"/></a></td>
                            <td class="centerTXT"><a href="'.$id_userOK.'"><img src="images/borrar.png" alt="borrar"/></a></td>
						<tr>
					';

				}
				else{
					$mensajeError = "No se puede guardar el registro en la base de CDatos";
				}

			break;
			//Editar Usuario
			case 'editUser':
				// Armamos el query
                        
				$query = sprintf("UPDATE tbl_usuarios
								 SET usr_nombre='%s', usr_password='%s', usr_status='%s', usr_permisos='%s'
								 WHERE id_user=%d LIMIT 1",
								 $_POST['usr_nombre'],$_POST['usr_password'],$_POST['usr_status'],$_POST['usr_permisos'],$_POST['id_user']);
                        
				// Ejecutamos el query
                            
                                
                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaUsers($mysqli);

				}else{
					$mensajeError = 'No se ha actualizado el registro';
				}


			break;
                        
                        //Borrar Usuario
			case 'delUser':
				// Armamos el query
                        
				$query = sprintf("DELETE FROM tbl_usuarios
								  WHERE id_user=%d LIMIT 1",
								 $_POST['id_user']);
                        
				// Ejecutamos el query
                            
                                
                                
				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha borrado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaUsers($mysqli);

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