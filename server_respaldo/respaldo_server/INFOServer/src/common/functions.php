<?php
	function hola($nombre) 
	{
		return 'Hola, ' . trim( $nombre );
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
?>
