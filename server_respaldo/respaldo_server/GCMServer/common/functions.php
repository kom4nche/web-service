<?php
	require_once (__DIR__ . '/../../dbinfo/dbconfig.php');

	function hola($nombre)
	{
		return 'Hola, ' . trim( $nombre );
	}

	function RegistroCliente($usuario, $regGCM, $model, $imei)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$resp = CodigoCliente($usuario);

		if ($resp === NULL)
		{
			$query  = "INSERT INTO dispositivos (NombreUsuario, marca, CodigoC2DM, imei) VALUES ('".$usuario."','".$model."','".$regGCM."','".$imei."')";
		}
		else
		{
			$query = "UPDATE dispositivos SET CodigoC2DM = '".$regGCM."', marca = '".$model."', imei = '".$imei."' WHERE NombreUsuario = '".$usuario."'";
		}


		$result = mysql_query($query)
    		or die('Consulta fallida: ' . mysql_error());

        $valor = 0;

		if (!$result)
		{
			$valor=0;
		}
		else
		{
			$valor=1;
		}

  		return $valor;
	}

	function CodigoCliente($usuario)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');


		$query = "SELECT CodigoC2DM FROM dispositivos WHERE NombreUsuario = '".$usuario."'";

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

  		return $valor;
	}

	function IniciarSorteo($persona) 
	{
	    	$Saludo = 'Hola, ' . trim( $persona['Nombre'] ) . '. ';
		$Saludo .= 'Usted tiene ' . $persona['Edad'] . ' ';
		$Saludo .= 'años y es ' . trim( $persona['Sexo'] ) . '. ';

	    	return array(
			'saludo' => $Saludo,
			'ganador' => (bool) rand(0, 1)
		);
	}

	function GetPersonas()
	{
		$List = array();

		$Sexo = array(
			0 => "Hombre",
			1 => "Mujer"
		);
		
		for ( $i=1; $i<11; $i++ )
		{
			$List[$i]['Nombre'] = "Persona " . $i;
			$List[$i]['Edad'] = rand(1, 100); 
			$List[$i]['Sexo'] = $Sexo[rand(0, 1)];
		}

		return $List;
	}
    
    //$usuario = "prueba2";
    //$codigo = "76585390";
	//echo RegistroCliente($usuario,$codigo);
	//echo "sss";
	//echo CodigoCliente($usuario);
	//if ($null === NULL) echo "devolvio vacio";
?>
