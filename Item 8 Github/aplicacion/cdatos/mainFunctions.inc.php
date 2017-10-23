<?php

session_start();
// Constantes conexión con la base de CDatos
define("server", 'localhost');
define("user", 'root');
define("pass", '12345678');
define("mainDataBase", 'gpsunarp');

// Variable que indica el status de la conexión a la base de CDatos
$errorDbConexion = false;


// Función para extraer el listado de usuarios
//aqui le pasas como parametro el mysqli que es la conexión que esta abajo

//SOLO SI ES CONSULTA A BD SIN PARAMETROS

//CONSULTA MODULO USUARIO


function logueo($linkDB,$usuario,$passphp){



    $query = "SELECT  su.id_usuario,su.usuario,sut.id_tipo_usuario,su.ape_paterno,su.ape_materno,su.nombres,su.dni,su.email  from sunarp_usuarios su INNER JOIN sunar_tipos_usuario sut ON su.fk_Id_Tipo_Usuario = sut.id_tipo_usuario  where su.usuario = '$usuario' and su.passw = '$passphp'";

    $resultadoQuery = $linkDB -> query($query);

    if($linkDB -> affected_rows == 1){
        while($listadoOK = $resultadoQuery -> fetch_assoc())
        {
            $_SESSION["id_tipo_usuario"]=$listadoOK["id_tipo_usuario"];
            $_SESSION["id_usuario"]=$listadoOK["id_usuario"];
            $_SESSION["usuario"]=$listadoOK["usuario"];
            $_SESSION["nombres"]=$listadoOK["nombres"]."".$listadoOK["ape_paterno"]."".$listadoOK["ape_paterno"];
            $_SESSION["ape_paterno"]=$listadoOK["ape_paterno"];
            $_SESSION["dni"]=$listadoOK["dni"];
            $_SESSION["email"]=$listadoOK["email"];
        }

        header("Location: CPresentacion/home.php");

    }else{
       echo  "<script type='text/javascript'>
			alert('datos incorrecto o no existentes');

			  </script>";
        header("Location: index.php");
    }

}

  //SUNARP CONSULTA PEDIDOS EXTERNO
function ConsultaPedidoExterno($linkDB){

// estados: 1: Enviado  2:En proceso 3: Atendido 4: Anulado
$statusTipo = array("1" => "btn btn-info","2" => "btn-warning","3" => "btn btn-success","4" => "btn btn-danger");
$statusTiponombre = array("1" => "Solicitado","2" => "En Proceso","3" => "Atendido","4" => "Anulado");

//si usuario es externo
if($_SESSION["id_tipo_usuario"] ==3){
 $consulta = $linkDB -> query("
SELECT
 pt.id_pedido_titulo
,tit.numero
,tit.anio
,(SELECT CONCAT_WS(' ',UPPER(sup.ape_paterno),UPPER(sup.ape_materno),',',UPPER(sup.nombres)) FROM sunarp_usuarios sup INNER JOIN titulos titu ON titu.fk_usuario_propietario = sup.id_usuario where titu.id_titulo = pt.fk_Id_Titulo) as nocomp
,ped.nro_atencion
,DATE(ped.fecha_pedido) as fechapedido
,pt.estado
,tit.nombrearchivo

FROM pedidos_titulo pt
INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo
INNER JOIN sunarp_usuarios su ON su.id_usuario = pt.fk_Id_Usuario
INNER JOIN sunar_tipos_usuario tusu ON su.fk_Id_Tipo_Usuario = tusu.id_tipo_usuario 
INNER JOIN pedidos ped ON ped.id_pedido_tit = pt.id_pedido_titulo

where pt.estado <> 4 and tusu.id_tipo_usuario = 3 and pt.fk_Id_Usuario =".$_SESSION["id_usuario"]);

}

//usuario interno
if($_SESSION["id_tipo_usuario"] ==2){
 $consulta = $linkDB -> query("
SELECT
 pt.id_pedido_titulo
,tit.numero
,tit.anio
,(SELECT CONCAT_WS(' ',UPPER(sup.ape_paterno),UPPER(sup.ape_materno),',',UPPER(sup.nombres)) FROM sunarp_usuarios sup INNER JOIN titulos titu ON titu.fk_usuario_propietario = sup.id_usuario where titu.id_titulo = pt.fk_Id_Titulo) as nocomp
,ped.nro_atencion
,DATE(ped.fecha_pedido) as fechapedido
,pt.estado
,tit.nombrearchivo

FROM pedidos_titulo pt
INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo
INNER JOIN sunarp_usuarios su ON su.id_usuario = pt.fk_Id_Usuario
INNER JOIN sunar_tipos_usuario tusu ON su.fk_Id_Tipo_Usuario = tusu.id_tipo_usuario 
INNER JOIN pedidos ped ON ped.id_pedido_tit = pt.id_pedido_titulo

where pt.estado <> 4 ");

}

//si es administrador
if($_SESSION["id_tipo_usuario"] ==1){
 $consulta = $linkDB -> query("
SELECT
 pt.id_pedido_titulo
,tit.numero
,tit.anio
,(SELECT CONCAT_WS(' ',UPPER(sup.ape_paterno),UPPER(sup.ape_materno),',',UPPER(sup.nombres)) FROM sunarp_usuarios sup INNER JOIN titulos titu ON titu.fk_usuario_propietario = sup.id_usuario where titu.id_titulo = pt.fk_Id_Titulo) as nocomp
,ped.nro_atencion
,DATE(ped.fecha_pedido) as fechapedido
,pt.estado
,tit.nombrearchivo

FROM pedidos_titulo pt
INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo
INNER JOIN sunarp_usuarios su ON su.id_usuario = pt.fk_Id_Usuario
INNER JOIN sunar_tipos_usuario tusu ON su.fk_Id_Tipo_Usuario = tusu.id_tipo_usuario 
INNER JOIN pedidos ped ON ped.id_pedido_tit = pt.id_pedido_titulo");

}






  if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		   if($listadoOK['nombrearchivo'] == "" || $listadoOK['estado'] !=3 ){
             $val = '<img src="images/nopdf.png" alt="descargar"/>';
             $ref = '#';
		   }
           else{
              $val = '<img src="images/pdf.png" alt="descargar"/>';
              $ref = 'Titulospdf'.'/'.$listadoOK['nombrearchivo'];

           }


        //armas los registros
			$salida .= '
				<tr>
                    <td>'.$listadoOK['numero'].'</td>
					<td>'.$listadoOK['anio'].'</td>
                    <td>'.$listadoOK['nocomp'].'</td>
                    <td>'.$listadoOK['nro_atencion'].'</td>
                    <td>'.$listadoOK['fechapedido'].'</td>
                    <td><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</span></td>
					<td id="Ver"><button type="button" name="botoncito"  value="'.$listadoOK['id_pedido_titulo'].'" class="btn btn-warning btn-circle" data-toggle="modal"data-target="#myModal" ><i class="fa fa-list"></i>
                            </button></td>
                    <td id="Pdf"><a href="'.$ref.'">'.$val.'</a></td>
				<tr>
			';
		}

	}

    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

    return $salida;
 }


 //SUNARP CONSULTA DETALLE PEDIDO EXTERNO



function consultaUsers($linkDB){

   //array para cambiar el boton de lo estados
	$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador
    $estado_per = array("1"=> "Administrador","2"=> "Almacen","3"=> "Logistica");
        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_user,usr_nombre,usr_password,usr_status,usr_permisos
								  FROM tbl_usuarios ORDER BY usr_nombre ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
					<td>'.$listadoOK['usr_nombre'].'</td>
					<td>'.$listadoOK['usr_password'].'</td>
                    <td>'.$estado_per[$listadoOK['usr_permisos']].'</td>
					<td class="centerTXT"><span class="btn btn-mini '.$statusTipo[$listadoOK['usr_status']].'">'.$statusTiponombre[$listadoOK['usr_status']].'</span></td>
					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_user'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_user'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}

    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}


//FUNCIONES HECHAR POR RAUL

 function consultaArea($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_area,codigo,nombre,anexo,piso,direccion,descripcion
								  FROM sunarp_area ORDER BY codigo ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
					<td>'.$listadoOK['codigo'].'</td>
					<td>'.$listadoOK['nombre'].'</td>
					<td>'.$listadoOK['anexo'].'</td>
					<td>'.$listadoOK['piso'].'</td>

					<td>'.$listadoOK['direccion'].'</td>
					<td>'.$listadoOK['descripcion'].'</td>

					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_area'].'"><img src="images/editar.png" alt="editar"/></a></td>
                     <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_area'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}

    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultacomboArea($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_area,nombre FROM sunarp_area ORDER BY id_area ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_area"].'" >'.$listadoOK["nombre"].'</option>
			';
		}

	}

    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>
		';
	}

	return $salida;
}

  function consultacomboTipoUsuario($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_tipo_Usuario,Descripcion FROM sunar_tipos_usuario ORDER BY id_tipo_Usuario ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_tipo_Usuario"].'" >'.$listadoOK["Descripcion"].'</option>
			';
		}

	}

    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>
		';
	}

	return $salida;
}


function consultacomboTipoOficina($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_oficina,nombre FROM sunarp_oficina ORDER BY id_oficina ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_oficina"].'" >'.$listadoOK["nombre"].'</option>
			';
		}

	}

    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>
		';
	}

	return $salida;
}


function consultaUsuarios($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT u.id_usuario,u.codigo,u.usuario,u.passw,u.ape_paterno,u.ape_materno,u.nombres,u.dni,u.direccion,u.celular,u.telefono,TU.Descripcion,OF.Nombre FROM sunarp_usuarios u INNER JOIN sunar_tipos_usuario TU ON u.fk_Id_Tipo_Usuario = TU.id_tipo_Usuario INNER JOIN sunarp_oficina OF ON u.fk_Id_Tipo_Oficina=OF.id_oficina");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>

										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['Nombre'].'</td>
										<td>'.$listadoOK['Descripcion'].'</td>
                                        <td>'.$listadoOK['usuario'].'</td>
                                        <td>'.$listadoOK['passw'].'</td>
                                        <td>'.$listadoOK['ape_paterno'].'</td>
                                        <td>'.$listadoOK['ape_materno'].'</td>
                                        <td>'.$listadoOK['nombres'].'</td>
                                        <td>'.$listadoOK['dni'].'</td>
                                        <td>'.$listadoOK['direccion'].'</td>
                                        <td>'.$listadoOK['celular'].'</td>
                                        <td>'.$listadoOK['telefono'].'</td>

                                        <td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_usuario'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_usuario'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}

    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaOficinas($linkDB){


	$salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT c.id_oficina,c.codigo,c.nombre,c.anexo,c.piso,c.descripcion,OF.Nombre FROM sunarp_oficina c INNER JOIN sunarp_area OF ON c.fk_area = OF.id_area");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>

										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['Nombre'].'</td>
										<td>'.$listadoOK['nombre'].'</td>
                                        <td>'.$listadoOK['anexo'].'</td>
                                        <td>'.$listadoOK['piso'].'</td>
                                        <td>'.$listadoOK['descripcion'].'</td>
                                        <td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_oficina'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_oficina'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}

    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}





//FIN FUNCIONES RAUL









function consultaFamilia($linkDB){

   //array para cambiar el boton de lo estados
	$statusTipo = array("1" => "btn-success","0" => "btn-danger");
	$statusTiponombre = array("1" => "Activo","0" => "Inactivo");
        //defines los estados del trabajador

        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT nombre,estado,id_familia
								  FROM tbl_familias ORDER BY nombre ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
					<td>'.strtoupper($listadoOK['nombre']).'</td>
					<td class="centerTXT"><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</span></td>
					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_familia'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_familia'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaProveedores($linkDB){

   //array para cambiar el boton de lo estados
	$statusTipo = array("1" => "btn-success","0" => "btn-danger");
	$statusTiponombre = array("1" => "Activo","0" => "Inactivo");
        //defines los estados del trabajador

        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_proveedor,nombre,ruc,direccion,telefono,pais,contacto,correo,estado FROM tbl_proveedores ORDER BY nombre ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
					<td>'.$listadoOK['ruc'].'</td>
					<td>'.$listadoOK['nombre'].'</td>
					<td>'.$listadoOK['pais'].'</td>
					<td>'.$listadoOK['telefono'].'</td>
					<td>'.$listadoOK['contacto'].'</td>
					<td>'.$listadoOK['correo'].'</td>
					<td>'.$listadoOK['direccion'].'</td>
					<td class="centerTXT"><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</span></td>
					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_proveedor'].'"><img src="images/editar.png" alt="editar"/></a></td>
                    <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_proveedor'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}



function consultaMarcas($linkDB){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-danger");
	//$statusTiponombre = array("1" => "Activo","0" => "Inactivo");
        //defines los estados del trabajador

        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT a.id_marca,a.nombre,b.nombre as nomfamilia FROM tbl_marcas a INNER JOIN tbl_familias b ON a.id_familia = b.id_familia  ORDER BY nombre ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
					<td>'.strtoupper($listadoOK['nombre']).'</td>
					<td>'.strtoupper($listadoOK['nomfamilia']).'</td>
					
					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_marca'].'"><img src="images/editar.png" alt="editar"/></a></td>
                    <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_marca'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}


//---CONSULTA MODULO PRODUCTOS---
function consultaProductos($linkDB){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	//$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador
        $Tipo_art = array("0"=> "Compatible","1"=> "Original");
        $moneda = array("1"=> "S/.","0"=> "$");
        $tipogarantia = array("1"=> "Si","0"=> "No");
        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT ART.id_articulo,ART.nombre_articulo,ART.unidades,ART.codigo,ART.serie,ART.precio_costo,ART.precio_venta,ART.tipo_articulo,ART.moneda,ART.stock_min,ART.stock_max,ART.stock_act,ART.garantia,FAM.id_familia,FAM.nombre as Nombre_Fam,MARC.id_marca as IdMarca,MARC.nombre as Nombre_Marc  FROM tbl_articulos ART
INNER JOIN tbl_familias FAM ON FAM.id_familia = ART.id_familia INNER JOIN tbl_marcas MARC ON MARC.id_marca = ART.id_marca");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
			 													
				<tr>
                                
										<td>'.$listadoOK['nombre_articulo'].'</td>
										<td>'.$listadoOK['codigo'].'</td>
                                        <td>'.$Tipo_art[$listadoOK['tipo_articulo']].'</td>
                                        <td>'.$listadoOK['Nombre_Fam'].'</td>
                                        <td>'.$listadoOK['Nombre_Marc'].'</td>
                                           
                                        <td>'.$listadoOK['serie'].'</td>
                                        <td>'.$listadoOK['unidades'].'</td>
                                        <td>'.$moneda[$listadoOK['moneda']].'</td>    
                                        <td>'.$listadoOK['precio_costo'].'</td>
                                        <td>'.$listadoOK['precio_venta'].'</td>
                                        <td>'.$listadoOK['stock_min'].'</td>    
                                        <td>'.$listadoOK['stock_max'].'</td>  
                                        <td>'.$listadoOK['stock_act'].'</td>     
                                        <td>'.$tipogarantia[$listadoOK['garantia']].'</td>
                                        <td style="display:none;">'.$listadoOK['IdMarca'].'</td>    
					<td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_articulo'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_articulo'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaProductosStock($linkDB){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	//$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador
        $Tipo_art = array("0"=> "Compatible","1"=> "Original");
        $moneda = array("1"=> "S/.","0"=> "$");
        $tipogarantia = array("1"=> "Si","0"=> "No");
        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT ART.id_articulo,ART.nombre_articulo,ART.unidades,ART.codigo,ART.serie,ART.precio_costo,ART.precio_venta,ART.tipo_articulo,ART.moneda,ART.stock_min,ART.stock_max,ART.stock_act,ART.garantia,FAM.id_familia,FAM.nombre as Nombre_Fam,MARC.id_marca as IdMarca,MARC.nombre as Nombre_Marc  FROM tbl_articulos ART
INNER JOIN tbl_familias FAM ON FAM.id_familia = ART.id_familia INNER JOIN tbl_marcas MARC ON MARC.id_marca = ART.id_marca");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{


				if($listadoOK['stock_act'] >= 10){
					//$clase = "danger";
					$clase = "#d7dcf0";
				}

				else if($listadoOK['stock_act'] > 5 && $listadoOK['stock_act'] < 10 ){
					//$clase = "danger";
					$clase = "#f7f487";
				}

				else if($listadoOK['stock_act'] <= 0){
					//$clase = "danger";
					$clase = "#f07474";
				}
				else{

					$clase = "#ffd8f9";
				}




		     //armas los registros
			$salida .= '
			 													
				<tr bgcolor="'.$clase.'">
                                
										<td>'.$listadoOK['nombre_articulo'].'</td>
										<td>'.$listadoOK['codigo'].'</td>
                                        <td>'.$Tipo_art[$listadoOK['tipo_articulo']].'</td>
                                        <td>'.$listadoOK['Nombre_Fam'].'</td>
                                        <td>'.$listadoOK['Nombre_Marc'].'</td>
                                           
                                        <td>'.$listadoOK['serie'].'</td>
                                        <td>'.$listadoOK['unidades'].'</td>
                                        <td>'.$moneda[$listadoOK['moneda']].'</td>    
                                        <td>'.$listadoOK['precio_costo'].'</td>
                                        <td>'.$listadoOK['precio_venta'].'</td>
                                        <td>'.$listadoOK['stock_min'].'</td>    
                                        <td>'.$listadoOK['stock_max'].'</td>  
                                        <td>'.$listadoOK['stock_act'].'</td>     
                                        <td>'.$tipogarantia[$listadoOK['garantia']].'</td>
                                        <td style="display:none;">'.$listadoOK['IdMarca'].'</td>    
					
                                       
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultacomboFamilia($linkDB){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	//$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador
        $Tipo_art = array("0"=> "Compatible","1"=> "Original");
        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_familia,nombre FROM tbl_familias ORDER BY id_familia ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_familia"].'" >'.$listadoOK["nombre"].'</option>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>	
		';
	}

	return $salida;
}

function consultacomboProveedor($linkDB){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	//$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador
       
        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_proveedor,nombre FROM tbl_proveedores ORDER BY id_proveedor ASC");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_proveedor"].'" >'.$listadoOK["nombre"].'</option>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>	
		';
	}

	return $salida;
}

//---FIN MODULO PRODUCTOS---

//---CONSULTA MODULO CLIENTES---
function consultaClientes($linkDB){
            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT C.id_cliente,C.nombre,C.ruc,C.direccion,C.representante,C.telefono,C.correo,P.provincia,DE.departamento,DI.distrito FROM tbl_clientes C INNER JOIN distritos DI ON DI.idDistr = C.id_distrito INNER JOIN provincias P ON P.idProv = DI.idProv INNER JOIN departamentos DE ON DE.idDpto = P.idDpto");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<tr>
                                
										<td>'.$listadoOK['nombre'].'</td>
										<td>'.$listadoOK['ruc'].'</td>
                                        <td>'.$listadoOK['direccion'].'</td>
                                        <td>'.$listadoOK['representante'].'</td>
                                        <td>'.$listadoOK['telefono'].'</td>
                                        <td>'.$listadoOK['correo'].'</td>
                                        <td>'.$listadoOK['departamento'].'</td>
                                        <td>'.$listadoOK['provincia'].'</td>    
                                        <td>'.$listadoOK['distrito'].'</td>
                                        <td class="centerTXT" id="Editar"><a href="'.$listadoOK['id_cliente'].'"><img src="images/editar.png" alt="editar"/></a></td>
                                        <td class="centerTXT" id="Borrar"><a  href="'.$listadoOK['id_cliente'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

//---CONSULTA MODULO CLIENTES---
function consultacomboDepartamentos($linkDB){
            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT idDpto,departamento FROM departamentos");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["idDpto"].'" >'.$listadoOK["departamento"].'</option>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>	
		';
	}

	return $salida;
}

//MODULO DE MOVIMIENTOS

//CONSULTA COMBO CLIENTES

function consultacomboClientes($linkDB){
            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT id_cliente,nombre FROM tbl_clientes");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
				<option value= "'.$listadoOK["id_cliente"].'" >'.$listadoOK["nombre"].'</option>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<option value="">< Seleccione Opcion ></option>	
		';
	}

	return $salida;
}


function consultaMovimientos($linkDB){

	$statusTipo = array("0" => "btn-success","1" => "btn-warning");
	$statusTiponombre = array("0" => "Confirmado","1" => "Pendiente");
	$concepto = array("1" => "AJUSTE DE INVENTARIO","2" => "DESPACHO","3" => "PRESTAMOS","4" => "REPARACIÓN","5" => "OTRAS SALIDAS");
	$areap = array("0" => "LOGÍSTICA","1" => "TIENDA");

            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos
    
    		$consulta = $linkDB -> query("SELECT a.num_doc,a.fecha,a.con_mov,a.num_guia,b.nombre as nombrecli,a.area,a.estado FROM tbl_movimientos a INNER JOIN tbl_clientes b ON a.id_cliente = b.id_cliente WHERE a.tipo_mov = 0 ORDER BY a.num_doc ASC");
    

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
				if($listadoOK['estado'] == 0){

					$val = '<img src="images/ver.png" alt="ver"/>';
					$val2 =  '<img src="images/cerrado.png" alt="cerrado"/>';
					//$var2 = '';
					$idtd ='onlyVer';
                    $idtd1 ='pdfsalida';
					//$var2 = '';
                    $val3 =  '<img src="images/pdf.png" alt="confirmar"/>';
					
				}

				else{

					$val = '<img src="images/detalle.png" alt="confirmar"/>';
					$val2 =  '<img src="images/confirmar.png" alt="confirmar"/>';
                    $val3 =  '<img src="images/nopdf.png" alt="confirmar"/>';
                    $idtd1 ='';
					$idtd ='Ver';

					//$var2 = $listadoOK['id_cliente'];

				}

			$salida .= '
				<tr>
                                
										<td>'.$listadoOK['num_doc'].'</td>
										<td>'.invierte_fecha($listadoOK['fecha']).'</td>
                                        <td>'.$concepto[$listadoOK['con_mov']].'</td>
                                        <td>'.$listadoOK['num_guia'].'</td>
                                        <td>'.$listadoOK['nombrecli'].'</td>
                                        <td>'.$areap[$listadoOK['area']].'</td>
                                        <td><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</td>    
                                        
                                        <td class="centerTXT" id="'.$idtd.'"><a href="'.$listadoOK['id_cliente'].'">'.$val.'</a></td>
                                        <td class="centerTXT" id="'.$idtd1.'"><a href="">'.$val3.'</a></td>

                                    
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaMovimientosbusqueda($linkDB,$numdoc,$fechaini,$fechafin,$estado){

	/*if(isset($numdoc))
		$numdoc = 'NULL';
	if(isset($fechaini))
		$fechaini = 'NULL';
	if(isset($fechafin))
		$fechafin = 'NULL';
	if(isset($estado))	
		$estado = 'NULL';*/


	$statusTipo = array("0" => "btn-success","1" => "btn-warning","2" => "btn-danger");
	$statusTiponombre = array("0" => "Confirmado","1" => "Pendiente","2"=> "Anulado");
	$concepto = array("1" => "AJUSTE DE INVENTARIO","2" => "DESPACHO","3" => "PRESTAMOS","4" => "REPARACIÓN","5" => "OTRAS SALIDAS");
	$areap = array("0" => "LOGÍSTICA","1" => "TIENDA");

            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos
    
   $consulta = $linkDB -> query("SELECT a.num_doc,a.fecha,a.con_mov,a.num_guia,b.nombre as nombrecli,a.area,a.estado FROM tbl_movimientos a INNER JOIN tbl_clientes b ON a.id_cliente = b.id_cliente WHERE a.tipo_mov = 0 AND a.num_doc LIKE '%$numdoc%' AND a.fecha >= '$fechaini' AND a.fecha <= '$fechafin' AND estado = $estado ORDER BY a.num_doc ASC");
    

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
				if($listadoOK['estado'] == 0){

					$val = '<img src="images/ver.png" alt="ver"/>';
					$val2 =  '<img src="images/cerrado.png" alt="cerrado"/>';
					//$var2 = '';
					$idtd ='onlyVer';
				}

				else{

					$val = '<img src="images/detalle.png" alt="confirmar"/>';	
					$val2 =  '<img src="images/confirmar.png" alt="confirmar"/>';

					$idtd ='Ver';

					//$var2 = $listadoOK['id_cliente'];

				}

			$salida .= '
				<tr>
                                
										<td>'.$listadoOK['num_doc'].'</td>
										<td>'.invierte_fecha($listadoOK['fecha']).'</td>
                                        <td>'.$concepto[$listadoOK['con_mov']].'</td>
                                        <td>'.$listadoOK['num_guia'].'</td>
                                        <td>'.$listadoOK['nombrecli'].'</td>
                                        <td>'.$areap[$listadoOK['area']].'</td>
                                        <td><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</td>    
                                        
                                        <td class="centerTXT" id="'.$idtd.'"><a href="'.$listadoOK['id_cliente'].'">'.$val.'</a></td>

                                    
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaMovimientosin($linkDB){

    $statusTipo = array("0" => "btn-success","1" => "btn-warning","2" => "btn-danger");
    $statusTiponombre = array("0" => "Confirmado","1" => "Pendiente","2"=> "Anulado");
    $concepto = array("1" => "AJUSTE DE INVENTARIO","6" => "CAMBIO","7" => "COMPRA MERCADERIA","8" => "DEVOLUCIÓN","9" => "PRODUCCIÓN","10" => "OTRAS ENTRADAS");



    $salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

    $consulta = $linkDB -> query("SELECT a.num_doc,a.fecha,a.con_mov,a.num_guia,b.nombre as nombreprov,a.nro_ordencompra,a.estado FROM tbl_movimientos a INNER JOIN tbl_proveedores b ON a.id_prov = b.id_proveedor WHERE a.tipo_mov = 1 ORDER BY a.num_doc ASC");


    //si hay registros entonces...
    if($consulta -> num_rows != 0){

        // convertimos el objeto
        while($listadoOK = $consulta -> fetch_assoc())
        {
            //armas los registros
            if($listadoOK['estado'] == 0){

                $val = '<img src="images/ver.png" alt="ver"/>';
                $val2 =  '<img src="images/cerrado.png" alt="cerrado"/>';
                //$var2 = '';
                $idtd ='onlyVer';
                //$var2 = '';
                $idtd1 ='pdfsalida';
                //$var2 = '';
                $val3 =  '<img src="images/pdf.png" alt="confirmar"/>';
            }

            else{

                $val = '<img src="images/detalle.png" alt="confirmar"/>';
                $val2 =  '<img src="images/confirmar.png" alt="confirmar"/>';
                $idtd ='Ver';
                //$var2 = $listadoOK['id_cliente'];
                $val3 =  '<img src="images/nopdf.png" alt="confirmar"/>';
                $idtd1 ='';
            }

            $salida .= '
				<tr>

										<td>'.$listadoOK['num_doc'].'</td>
										<td>'.invierte_fecha($listadoOK['fecha']).'</td>
                                        <td>'.$concepto[$listadoOK['con_mov']].'</td>

                                        <td>'.$listadoOK['nro_ordencompra'].'</td>
                                        <td>'.$listadoOK['nombreprov'].'</td>
                                        <td><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</td>

                                        <td class="centerTXT" id="'.$idtd.'"><a href="'.$listadoOK['id_cliente'].'">'.$val.'</a></td>
                                        <td class="centerTXT" id="'.$idtd1.'"><a href="">'.$val3.'</a></td>

				<tr>
			';
        }

    }

    //sino
    else{
        $salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
    }

    return $salida;
}





function consultacorrelativo($linkDB){
            
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos
    
    		$consulta = $linkDB -> query("SELECT codigo,anio,correlativo FROM tbl_movimientos WHERE tipo_mov = 0 ORDER BY correlativo DESC LIMIT 1");
    

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{

		     //armas los registros
			$salida .= '
				
                                 <td>
                                        <p id="a">#</p>
                                                  
                                        <input type="text"  id="mdoc" name="mdoc" value="'.$listadoOK['codigo'].'"/>
                                                   
                                  </td>
                                  <td>
                                         <p id="b">Num </p>
                                          <input type="text"  id="numdoc1" name="numdoc1" value="'.$listadoOK['anio'].'" />

                                   </td>
                                    <td>
                                          <p id="pdoc">Documento</p>
                                           <input type="text"  id="numdoc2" name="numdoc2" value="'.str_pad($listadoOK['correlativo'], 5, "0", STR_PAD_LEFT).'" />
                                   </td>
										
				
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<td>
                                        <p>#</p>
                                                  
                                        <input type="text"  id="mdoc" name="mdoc" value="BS"/>
                                                   
                                  </td>
                                  <td>
                                         <p>Num </p>
                                          <input type="text"  id="numdoc1" name="numdoc1" value="'.$fecha = date("Y").'" />

                                   </td>
                                    <td>
                                          <p>Documento</p>
                                           <input type="text"  id="numdoc2" name="numdoc2" value="'.str_pad(1, 5, "0", STR_PAD_LEFT).'" />
                                   </td>
		';
	}

	return $salida;
}

function consultacorrelativoin($linkDB){

    $salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

    $consulta = $linkDB -> query("SELECT codigo,anio,correlativo FROM tbl_movimientos WHERE tipo_mov = 1 ORDER BY correlativo DESC LIMIT 1");


    //si hay registros entonces...
    if($consulta -> num_rows != 0){

        // convertimos el objeto
        while($listadoOK = $consulta -> fetch_assoc())
        {

            //armas los registros
            $salida .= '

                                 <td>
                                        <p id="a">#</p>

                                        <input type="text"  id="mdoc" name="mdoc" value="'.$listadoOK['codigo'].'"/>

                                  </td>
                                  <td>
                                         <p id="b">Num </p>
                                          <input type="text"  id="numdoc1" name="numdoc1" value="'.$listadoOK['anio'].'" />

                                   </td>
                                    <td>
                                          <p id="pdoc">Documento</p>
                                           <input type="text"  id="numdoc2" name="numdoc2" value="'.str_pad($listadoOK['correlativo'], 5, "0", STR_PAD_LEFT).'" />
                                   </td>


			';
        }

    }

    //sino
    else{
        $salida = '
			<td>
                                        <p>#</p>

                                        <input type="text"  id="mdoc" name="mdoc" value="BE"/>

                                  </td>
                                  <td>
                                         <p>Num </p>
                                          <input type="text"  id="numdoc1" name="numdoc1" value="'.$fecha = date("Y").'" />

                                   </td>
                                    <td>
                                          <p>Documento</p>
                                           <input type="text"  id="numdoc2" name="numdoc2" value="'.str_pad(1, 5, "0", STR_PAD_LEFT).'" />
                                   </td>
		';
    }

    return $salida;
}

function consultaDetmovs($linkDB,$corr){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	//$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
        //defines los estados del trabajador

        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	$consulta = $linkDB -> query("SELECT a.id_det_mov,b.codigo,b.nombre_articulo,a.cantidad,a.stock_act,a.id_articulo FROM tbl_detalle_movimiento a INNER JOIN tbl_articulos b ON a.id_articulo = b.id_articulo INNER JOIN tbl_movimientos c ON c.num_doc = a.id_movimiento WHERE a.id_movimiento = '$corr'");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
			 												
				<tr>
                                
										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['nombre_articulo'].'</td>
                                        <td>'.$listadoOK['cantidad'].'</td>
                                        <td id="sactocultar" style="display:none">'.$listadoOK['stock_act'].'</td>
                                        <td id="sactocultarid" style="display:none">'.$listadoOK['id_articulo'].'</td>
                                       
                                        

                                        <td class="centerTXT" id="Borrardm"><a  href="'.$listadoOK['id_det_mov'].'"><img id="imagen" src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS</td>
	   		</tr>
		';
	}

	return $salida;
}

function consultaDetmovsver($linkDB,$corr){

    //array para cambiar el boton de lo estados
    //$statusTipo = array("1" => "btn-success","0" => "btn-warning");
    //$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
    //defines los estados del trabajador


    $salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

    $consulta = $linkDB -> query("SELECT a.id_det_mov,b.codigo,b.nombre_articulo,a.cantidad,a.stock_act,a.id_articulo FROM tbl_detalle_movimiento a INNER JOIN tbl_articulos b ON a.id_articulo = b.id_articulo INNER JOIN tbl_movimientos c ON c.num_doc = a.id_movimiento WHERE a.id_movimiento = '$corr'");

    //si hay registros entonces...
    if($consulta -> num_rows != 0){

        // convertimos el objeto
        while($listadoOK = $consulta -> fetch_assoc())
        {
            //armas los registros
            $salida .= '

				<tr>

										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['nombre_articulo'].'</td>
                                        <td>'.$listadoOK['cantidad'].'</td>
                                        <td id="sactocultar" style="display:none">'.$listadoOK['stock_act'].'</td>
                                        <td id="sactocultarid" style="display:none">'.$listadoOK['id_articulo'].'</td>




				<tr>
			';
        }

    }

    //sino
    else{
        $salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS</td>
	   		</tr>
		';
    }

    return $salida;
}
function consultaDetmovsin($linkDB,$corr){

    //array para cambiar el boton de lo estados
    //$statusTipo = array("1" => "btn-success","0" => "btn-warning");
    //$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
    //defines los estados del trabajador


    $salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

    $consulta = $linkDB -> query("SELECT a.id_det_mov,b.codigo,b.nombre_articulo,a.cantidadin,a.stock_act,a.id_articulo FROM tbl_detalle_movimiento a INNER JOIN tbl_articulos b ON a.id_articulo = b.id_articulo INNER JOIN tbl_movimientos c ON c.num_doc = a.id_movimiento WHERE a.id_movimiento = '$corr'");

    //si hay registros entonces...
    if($consulta -> num_rows != 0){

        // convertimos el objeto
        while($listadoOK = $consulta -> fetch_assoc())
        {
            //armas los registros
            $salida .= '

				<tr>

										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['nombre_articulo'].'</td>
                                        <td>'.$listadoOK['cantidadin'].'</td>
                                        <td id="sactocultar" style="display:none">'.$listadoOK['stock_act'].'</td>
                                        <td id="sactocultarid" style="display:none">'.$listadoOK['id_articulo'].'</td>



                                        <td class="centerTXT" id="Borrardm"><a  href="'.$listadoOK['id_det_mov'].'"><img src="images/borrar.png" alt="borrar"/></a></td>
				<tr>
			';
        }

    }

    //sino
    else{
        $salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS</td>
	   		</tr>
		';
    }

    return $salida;
}
function consultaDetmovsinver($linkDB,$corr){

    //array para cambiar el boton de lo estados
    //$statusTipo = array("1" => "btn-success","0" => "btn-warning");
    //$statusTiponombre = array("1" => "Activo","0" => "Suspendido");
    //defines los estados del trabajador


    $salida = '';

    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

    $consulta = $linkDB -> query("SELECT a.id_det_mov,b.codigo,b.nombre_articulo,a.cantidadin,a.stock_act,a.id_articulo FROM tbl_detalle_movimiento a INNER JOIN tbl_articulos b ON a.id_articulo = b.id_articulo INNER JOIN tbl_movimientos c ON c.num_doc = a.id_movimiento WHERE a.id_movimiento = '$corr'");

    //si hay registros entonces...
    if($consulta -> num_rows != 0){

        // convertimos el objeto
        while($listadoOK = $consulta -> fetch_assoc())
        {
            //armas los registros
            $salida .= '

				<tr>

										<td>'.$listadoOK['codigo'].'</td>
										<td>'.$listadoOK['nombre_articulo'].'</td>
                                        <td>'.$listadoOK['cantidadin'].'</td>
                                        <td id="sactocultar" style="display:none">'.$listadoOK['stock_act'].'</td>
                                        <td id="sactocultarid" style="display:none">'.$listadoOK['id_articulo'].'</td>



				<tr>
			';
        }

    }

    //sino
    else{
        $salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS</td>
	   		</tr>
		';
    }

    return $salida;
}
function consultaKardexProducto($linkDB,$id_prod,$fechainicio,$fechaFin){

   //array para cambiar el boton de lo estados
	//$statusTipo = array("1" => "btn-success","0" => "btn-warning");
	$statusMov = array("0" => "Salida","1" => "Entrada");
	$concepto = array("1" => "AJUSTE DE INVENTARIO","2" => "DESPACHO","3" => "PRESTAMOS","4" => "REPARACIÓN","5" => "OTRAS SALIDAS","6" => "CAMBIO","7" => "COMPRA MERCADERIA","8" => "DEVOLUCIÓN","9" => "PRODUCCIÓN","10" => "OTRAS ENTRADAS");
        //defines los estados del trabajador

        
	$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	/*$consulta = $linkDB -> query("SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo INNER JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = 28 AND a.fecha >= '2013-09-21' AND a.fecha <= '2013-09-23' ORDER BY b.fechareg ASC
");*/

$consulta = $linkDB -> query("SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo LEFT JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE a.estado = 0 AND c.id_articulo = $id_prod AND a.fecha >= '$fechainicio' AND a.fecha <= '$fechaFin' ORDER BY b.fechareg ASC
");

	//si hay registros entonces...
    if($consulta -> num_rows != 0){
		
		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		     //armas los registros
			$salida .= '
			 												
				<tr>
                                
										<td>'.invierte_fecha($listadoOK['fecha']).'</td>
										<td>'.$statusMov[$listadoOK['tipo_mov']].'</td>
                                        <td>'.$concepto[$listadoOK['con_mov']].'</td>
                                        <td>'.$listadoOK['proveedor'].'</td>
                                        <td>'.$listadoOK['cliente'].'</td>
                                        <td>'.$listadoOK['num_doc'].'</td>
                                        <td>'.$listadoOK['codigo'].'</td>
                                        <td>'.$listadoOK['Entrada'].'</td>
                                        <td>'.$listadoOK['Salida'].'</td>
                                        <td>'.$listadoOK['stock_act'].'</td>

                                        
										
				<tr>
			';
		}

	}
    
    //sino
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS</td>
	   		</tr>
		';
	}

	return $salida;
}


function invierte_fecha($fecha){
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);
	$correcta=$dia."-".$mes."-".$anio;
	return $correcta;
	}

function invierte_fecha_mysql($fecha){
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);
	$correcta=$anio."-".$mes."-".$dia;
	return $correcta;
	}	
//aqui envias el $mysqli con la conexion que esta en usuarios.php
// Verificar constantes para conexión al servidor
if(defined('server') && defined('user') && defined('pass') && defined('mainDataBase'))
{
	// Conexión con la base de CDatos

	$mysqli = new mysqli(server, user, pass, mainDataBase);

	// Verificamos si hay error al conectar
	if (mysqli_connect_error()) {
	    $errorDbConexion = true;
	}

	// Evitando problemas con acentos
	$mysqli -> query('SET NAMES "utf8"');
}


?>