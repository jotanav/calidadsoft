<?php
session_start();
if(@session_start() == false){session_destroy();session_start();}



class Conectar
{
	public static function con()
	{
		$con=mysql_connect("localhost","root","12345678");
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db("gpsunarp");
		return $con;
	}

  
//**********************************************************************************************
//Funci�n para invertir fecha
public static function invierte_fecha($fecha){
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);
	$correcta=$dia."-".$mes."-".$anio;
	return $correcta;
	}
	
	public static function invierte_fecha_bd($fecha){
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);
	$correcta=$anio."-".$mes."-".$dia;
	return $correcta;
	}

	
}
class Trabajo 

{
	 	
	private $kardex;
	
	public function __construct()
	{
		$this->kardex=array();
	
	}

public function registra_pedido_externo() {
   //$id_titulo = POST["id_titulo"];  
$mysqli = new mysqli("localhost", "root","", "gpsunarp");
	// Verificamos si hay error al conectar
if (mysqli_connect_error()) {
	 $errorDbConexion = true;
}

// Evitando problemas con acentos
$mysqli -> query('SET NAMES "utf8"');
$id_usuario = $_SESSION["id_usuario"];
$id_titulo = $_POST["id_titulo"];
$fechapedido =  date("Y-m-d");
   
$tipped = $_POST["cb_tiped"];
$datbouch = $_POST["txt_datbouch"];
$coment = $_POST["txt_coment"];
$id_salida="@x";
   
$result = mysqli_query($mysqli, "call pedido_externo_v2(@x".",".$id_titulo.",".$id_usuario.",".$tipped.",'".$fechapedido."','".$datbouch."','".$coment."');");
 $r = mysqli_query($mysqli,"SELECT @x as id");    
	  if (!$result)
      {
         echo "Insert failed";
      }
      else
      {
	    while($row = mysqli_fetch_assoc($r))
       {
        //print_r($row);
		//exit;
		  $id_usu_externo = $row["id"];
        }
	    
		 
date_default_timezone_set('America/Toronto');

require_once('class.phpmailer.php');
include("class.smtp.php"); 

$mail = new PHPMailer();               

$nombres = $_SESSION["nombres"];
$dni = $_SESSION["dni"];
$numtit = $_POST["txt_numtit"];
$titular =  $_POST["txt_nomprop"];

//envio de correo electronico
 $atencion = "p";           
$url = "http://perusecretarias.com/ccjy/CPresentacion/";

$body ="<div align='left'>
            El usuario <b> $nombres </b> con documento de identidad <b>$dni</b>
			Ha solicicitado el titulo <b>$numtit</b> que tiene como titular a <b>$titular</b>  .
            </br>
            Numero de Atención: <b>$datbouch</b>
            <br>
                      
            <br>
            Mensaje: $mensaje;
			<a href='.$url.home.php?token=$id_usuario&r=$atencion&id_usuext=$id_usu_externo'>http://perusecretarias.com/ccjy/CPresentacion/home.php?token=$id_usuario&r=$atencion&id_usuext=$id_usu_externo</a>
            <br>
            
            </div>";
            
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = "mail.supremecluster.com"; // SMTP server
            $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Host       = "mail.supremecluster.com"; // sets the SMTP server
            $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
            $mail->Username   = "depositobanco@perusecretarias.com"; // SMTP account username
            $mail->Password   = "70056766.@";        // SMTP account password


            $mail->SetFrom('depositobanco@perusecretarias.com', 'Datos Boucher Secretarias');
            $mail->Subject    = "Pruebas";
            $mail->MsgHTML($body);
            $address = "alextorres_v@hotmail.com";
            $mail->AddAddress($address, 'Alex');

            if(!$mail->Send()) {
                 echo "<script type='text/javascript'>
                       
                        alert('error al enviar correo');
                         
                        </script>
                        ";
                } else {
                    //echo "Message sent!";
                    echo "<script type='text/javascript'>
                       
                         document.location=('http://www.perusecretarias.com/ccjy/CPresentacion/home.php');
                         
                        </script>
                        ";
                       } 
		 
		 
		 
		 
		 
		 
		 
		 
        echo "<script>
		alert('Registrado Correctamente')
		</script>";
      }

   }

	public function get_kardex($id_prod,$fechainicio,$fechafin)
	{
/*		$sql = "SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo INNER JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = 28 AND a.fecha >= '2013-09-20' AND a.fecha <= '2013-09-23' ORDER BY b.fechareg ASC
";*/
	     //$id_prod = $GLOBALS['A'];
		//$id_prod = 28;
		
	    //$fechainicio = '2013-09-20';
	    //$fechafin = '2013-09-23';
		//$id_prod = $_POST['id_prod'];
		//$fechainicio = $_POST['fechainicio'];
		//$fechafin = $_POST['fechafin'];

	   $sql = "SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo LEFT JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = $id_prod AND a.fecha >= $fechainicio AND a.fecha <= '$fechafin' ORDER BY b.fechareg ASC
";

/*$sql = "select "
		." a.fecha,a.tipo_mov,a.con_mov,e.nombre as proveedor,d.nombre as cliente,a.num_doc,c.codigo,b.cantidadin as Entrada,b.cantidad as Salida,b.stock_act,c.nombre_articulo "
		." from "
		." tbl_movimientos a inner join tbl_detalle_movimiento b on a.num_doc = b.id_movimiento inner join tbl_articulos c on b.id_articulo = c.id_articulo inner join tbl_clientes d on  d.id_cliente = a.id_cliente left join tbl_proveedores e on e.id_proveedor = a.id_prov "
		." where "
		." c.id_articulo ="8".
		."and a.fecha >= '2013-09-20' and a.fecha <= '2013-09-23' order by b.fechareg ASC ";*/

		$res=mysql_query($sql,Conectar::con());	
		while ($reg=mysql_fetch_assoc($res))
		{
			$this->kardex[]=$reg;
		}
			return $this->kardex;
	}

    public function get_salida($corr)
    {
        /*		$sql = "SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo INNER JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = 28 AND a.fecha >= '2013-09-20' AND a.fecha <= '2013-09-23' ORDER BY b.fechareg ASC
        ";*/
        //$id_prod = $GLOBALS['A'];
        //$id_prod = 28;

        //$fechainicio = '2013-09-20';
        //$fechafin = '2013-09-23';
        //$id_prod = $_POST['id_prod'];
        //$fechainicio = $_POST['fechainicio'];
        //$fechafin = $_POST['fechafin'];

        $sql1 = "SELECT a.num_doc,a.fecha,a.con_mov,a.num_guia,c.nombre as nombrecli,a.area,d.codigo,d.nombre_articulo,d.moneda,d.precio_venta,b.cantidad FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON  a.num_doc = b.id_movimiento INNER JOIN tbl_clientes c ON a.id_cliente = c.id_cliente INNER JOIN tbl_articulos d ON d.id_articulo = b.id_articulo WHERE a.num_doc = '$corr'";


        /*$sql = "select "
                ." a.fecha,a.tipo_mov,a.con_mov,e.nombre as proveedor,d.nombre as cliente,a.num_doc,c.codigo,b.cantidadin as Entrada,b.cantidad as Salida,b.stock_act,c.nombre_articulo "
                ." from "
                ." tbl_movimientos a inner join tbl_detalle_movimiento b on a.num_doc = b.id_movimiento inner join tbl_articulos c on b.id_articulo = c.id_articulo inner join tbl_clientes d on  d.id_cliente = a.id_cliente left join tbl_proveedores e on e.id_proveedor = a.id_prov "
                ." where "
                ." c.id_articulo ="8".
                ."and a.fecha >= '2013-09-20' and a.fecha <= '2013-09-23' order by b.fechareg ASC ";*/

        $res=mysql_query($sql1,Conectar::con());
        while ($reg=mysql_fetch_assoc($res))
        {
            $this->kardex[]=$reg;
        }
        return $this->kardex;
    }

    public function get_entrada($corr)
    {
        /*		$sql = "SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo INNER JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = 28 AND a.fecha >= '2013-09-20' AND a.fecha <= '2013-09-23' ORDER BY b.fechareg ASC
        ";*/
        //$id_prod = $GLOBALS['A'];
        //$id_prod = 28;

        //$fechainicio = '2013-09-20';
        //$fechafin = '2013-09-23';
        //$id_prod = $_POST['id_prod'];
        //$fechainicio = $_POST['fechainicio'];
        //$fechafin = $_POST['fechafin'];

        $sql2 = "SELECT a.num_doc,a.fecha,a.con_mov,a.num_guia,c.nombre as nombrecli,a.area,d.codigo,d.nombre_articulo,d.moneda,d.precio_costo,b.cantidadin,a.nro_ordencompra FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON  a.num_doc = b.id_movimiento INNER JOIN tbl_proveedores c ON a.id_prov = c.id_proveedor INNER JOIN tbl_articulos d ON d.id_articulo = b.id_articulo WHERE a.num_doc = '$corr'";


        /*$sql = "select "
                ." a.fecha,a.tipo_mov,a.con_mov,e.nombre as proveedor,d.nombre as cliente,a.num_doc,c.codigo,b.cantidadin as Entrada,b.cantidad as Salida,b.stock_act,c.nombre_articulo "
                ." from "
                ." tbl_movimientos a inner join tbl_detalle_movimiento b on a.num_doc = b.id_movimiento inner join tbl_articulos c on b.id_articulo = c.id_articulo inner join tbl_clientes d on  d.id_cliente = a.id_cliente left join tbl_proveedores e on e.id_proveedor = a.id_prov "
                ." where "
                ." c.id_articulo ="8".
                ."and a.fecha >= '2013-09-20' and a.fecha <= '2013-09-23' order by b.fechareg ASC ";*/

        $res=mysql_query($sql2,Conectar::con());
        while ($reg=mysql_fetch_assoc($res))
        {
            $this->kardex[]=$reg;
        }
        return $this->kardex;
    }
}

?>