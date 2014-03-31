<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');


$link = mysql_connect('localhost', 'root', 'samsung')
    or die('No se pudo conectar: ' . mysql_error());

mysql_select_db('carwatch')
    or die('No se pudo seleccionar la base de datos');

mysql_query('SET CHARACTER SET utf8');


$query = "SELECT CodigoC2DM FROM usuarios WHERE NombreUsuario = 'prueb9a'";

$result = mysql_query($query)
    or die('Consulta fallida: ' . mysql_error());

$filas = mysql_num_rows($result);  

$valor = NULL;

if ($filas==0)
{    
	$valor = NULL;

}
else
{
	while ($row=mysql_fetch_assoc($result)) 
  	{ 
 		 $valor = $row['CodigoC2DM'];
  	}
}

  if($valor === NULL) {
      echo 'is null';
  }

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
Header("Content-type: application/json");
?>