<?php
	require_once (__DIR__ . '/../../dbinfo/dbconfig.php');
	require_once ('gcm.php');

	function hola($nombre)
	{
		return 'Hola, ' . trim( $nombre );
	}

	function getUbicacion($usuario, $cantidad)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$sql = "SELECT u.hora, u.latitud, u.longitud FROM ubicacion u, vehiculos v WHERE u.vehiculo = v.patente AND v.propietario = '".$usuario."' order by u.id DESC";

		$result = mysql_query($sql)
    		or die('Consulta fallida: ' . mysql_error());       

		$filas = mysql_num_rows($result);


		$List = array();

		if ($filas==0)
		{    
			$List = NULL;

		}

		else
		{
			//while () 
			//$List = array();
		
			for ( $i=1; $i<=$cantidad; $i++ )
			{
				$row=mysql_fetch_assoc($result);

				$List[$i]['Latitud'] = $row['latitud'];
				$List[$i]['Longitud'] = $row['longitud'];
				$List[$i]['Hora'] = $row['hora'];
			}

		}

  		return $List;
	}

	function RegistroUbicacion($patente, $lat, $long, $prec)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$resp = "ON";
		$valor = 0;

		if ($resp == "ON")
		{
			$query  = "INSERT INTO ubicacion (`id`, `vehiculo`, `hora`, `latitud`, `longitud`, `precision`) VALUES (NULL,'".$patente."',NOW(),'".$lat."','".$long."','".$prec."')";
		}

		else return $valor;

		$result = mysql_query($query)
    		or die('Consulta fallida: ' . mysql_error());        

		if ($result)
		{
			$valor=1;
		}

  		return $valor;
	}

	function RegistroAlerta($patente, $tipo)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$resp = "ON";
		$valor = 0;

		$hora = date('Y-m-d H:i:s', time());

		if ($resp == "ON")
		{
			$query  = "INSERT INTO alertas (`id`, `vehiculo`, `hora`, `tipo`) VALUES (NULL,'".$patente."','".$hora."','".$tipo."')";

			EnviarAlerta($patente, $tipo, $hora);
		}

		else return $valor;

		$result = mysql_query($query)
    		or die('Consulta fallida: ' . mysql_error());        

		if ($result)
		{
			$valor=1;
		}

  		return $valor;
	}

	function EnviarAlerta($patente, $tipo, $hora)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$query = "SELECT propietario FROM vehiculos WHERE patente = '".$patente."'";

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
 		 		$valor = $row['propietario'];
  			}
		}

        $codigo = CodigoCliente($valor);

        $mensaje = "Alerta Activada!";

        if ($tipo == "P") $mensaje = "Alarma Activada! PUERTAS";

        if ($tipo == "V") $mensaje = "Alarma Activada! VENTANAS";

        enviar_gcm($codigo, $mensaje, $hora);

  		return $valor;
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
    
    //$patente = "RA7097";
    //$tipo = "P";
    //$hora = date('Y-m-d H:i:s', time());

    //$Lista = array();
    //$Lista = getUbicacion("jmoltc@gmasssl.com",3);


    //echo $Lista[1]['Latitud'];
    //echo $Lista[1]['Longitud'];
    //echo $Lista[1]['Hora'];

    //echo $Lista[3]['Latitud'];
    //echo $Lista[3]['Longitud'];
    //echo $Lista[3]['Hora'];
    //$lat = -0.0022;
    //$long = -73.222;
    //$prec = 24.2;

    //echo RegistroAlerta($patente,$tipo);

    //echo EnviarAlerta($patente,$tipo,$hora);
    //$codigo = "76585390";
	//echo RegistroUbicacion($pat,$lat,$long,$prec);
	//echo "sss";
	//echo CodigoCliente($usuario);
	//if ($null === NULL) echo "devolvio vacio";
?>
