<?php 

if ( !isset($_REQUEST['term']) )
    exit;

$dblink = mysql_connect('localhost', 'root', '') or die( mysql_error() );
mysql_select_db('gpsunarp');


$rs = mysql_query("SELECT tit.id_titulo,tit.numero,(SELECT CONCAT_WS(' ',UPPER(usu.ape_paterno),UPPER(usu.ape_materno),',',UPPER(usu.nombres))) as nombres,(SELECT CONCAT_WS(' ',tit.mes,'/',tit.anio)) as mesanio  FROM titulos tit INNER JOIN sunarp_usuarios usu ON usu.id_usuario = tit.fk_usuario_propietario WHERE CONCAT_WS(' ',UPPER(usu.ape_paterno),UPPER(usu.ape_materno),',',UPPER(usu.nombres)) like UPPER('%".($_REQUEST['term'])."%') ORDER BY nombres asc limit 0,10", $dblink);

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            //'label' => $row['id_articulo'] .', '. $row['nombre_articulo'] .' '. $row['serie'] ,
            //'value' => $row['id_articulo']
            'value' => $row['nombres'] ,
			'idtit' => $row['id_titulo'] ,
			'numero' => $row['numero'],
            'mesanio' => $row['mesanio']
        );
    }
}

echo json_encode($data);
flush();

?>

