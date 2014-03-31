<?php
	require_once (__DIR__ . '/../../dbinfo/config.php');
	require_once (__DIR__ . '/../../dbinfo/dbconfig.php');
	

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

	function getAlertas($usuario, $cantidad)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$sql = "SELECT a.id, a.hora, a.tipo FROM alertas a, vehiculos v WHERE a.vehiculo = v.patente AND v.propietario = '".$usuario."' order by a.id DESC";

		$result = mysql_query($sql)
    		or die('Consulta fallida: ' . mysql_error()); 

		$filas = mysql_num_rows($result);

		$List = array();


		$total = $cantidad;
		if ($filas < $cantidad) $total = $filas;


		if ($filas==0)
		{    
			$List = NULL;

		}

		else
		{
			//while () 
			//$List = array();
		
			for ( $i=1; $i<=$total; $i++ )
			{
				$row=mysql_fetch_assoc($result);

				$List[$i]['Id'] = $row['id'];
				$List[$i]['Hora'] = $row['hora'];
				$List[$i]['Tipo'] = $row['tipo'];
			}

		}

  		return $List;
	}


	function LoginCliente($usuario, $password)
	{
		global $db;

        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM users 
            WHERE 
                username = :username 
        "; 

        $query_params = array( 
            ':username' => $usuario 
        );
         
        try{ 
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch();
        if($row){ 
            $check_password = hash('sha256', $password . $row['salt']); 
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['salt']);
            } 
            if($check_password === $row['password']){
                $login_ok = true;
            } 
        }

        $valor = 0;

        if($login_ok){ 
            $valor=1;
        } 
        else{ 
            $valor=0; 
        }

  		return $valor;
	}

	function hola($nombre)
	{
		return 'Hola, ' . trim( $nombre );
	}

	function RegistroCliente($usuario, $regGCM)
	{
    	$link = mysql_connect(DBURL, DBUSER, DBPASS)
   	    or die('No se pudo conectar: ' . mysql_error());

		mysql_select_db(DBNAME)
    	or die('No se pudo seleccionar la base de datos');

		mysql_query('SET CHARACTER SET utf8');

		$resp = CodigoCliente($usuario);

		if ($resp === NULL)
		{
			$query  = "INSERT INTO usuarios (NombreUsuario, CodigoC2DM) VALUES ('".$usuario."','".$regGCM."')";
		}
		else
		{
			$query = "UPDATE usuarios SET CodigoC2DM = '".$regGCM."' WHERE NombreUsuario = '".$usuario."'";
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


		$query = "SELECT CodigoC2DM FROM usuarios WHERE NombreUsuario = '".$usuario."'";

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
    
    //$Lista = array();
    //$Lista = getAlertas("jmoltc@gmail.com",3);


    //echo $Lista[1]['Id'];
    //echo $Lista[1]['Hora'];
    //echo $Lista[1]['Hora'];

    //$usuario = "prueba2";
    //$codigo = "76585390";
	//echo RegistroCliente($usuario,$codigo);
	//echo "sss";
	//echo CodigoCliente($usuario);
	//if ($null === NULL) echo "devolvio vacio";
	//echo LoginCliente("prueba@molt.cl","09137");
?>