<?php
	// Definición de tipos en nuestro servicio web -------------------------

	$server->wsdl->addComplexType(
		'TWsUbicacion',
		'complexType',
		'struct',
		'all',
		'',
		array(
			'Latitud' => array('name' => 'Latitud', 'type' => 'xsd:double'),
			'Longitud' => array('name' => 'Longitud', 'type' => 'xsd:double'),
			'Hora' => array('name' => 'Hora', 'type' => 'xsd:string')
		)
	);

	$server->wsdl->addComplexType(
		'TWsArrayOfUbicacion',
		'complexType',
		'array',
		'',
		'SOAP-ENC:Array',
		array(),
		array(
			array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:TWsUbicacion[]')			 	
		),
		'tns:TWsUbicacion'
	);


	$server->wsdl->addComplexType(
		'TWsAlerta',
		'complexType',
		'struct',
		'all',
		'',
		array(
			'Id' => array('name' => 'Id', 'type' => 'xsd:int'),
			'Hora' => array('name' => 'Hora', 'type' => 'xsd:string'),
			'Tipo' => array('name' => 'Tipo', 'type' => 'xsd:string'),
		)
	);

	$server->wsdl->addComplexType(
		'TWsArrayOfAlerta',
		'complexType',
		'array',
		'',
		'SOAP-ENC:Array',
		array(),
		array(
			array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:TWsAlerta[]')	 	
		),
		'tns:TWsAlerta'
	);


	$server->wsdl->addComplexType(
		'TWsPersona',
		'complexType',
		'struct',
		'all',
		'',
		array(
			'Nombre' => array('name' => 'Nombre', 'type' => 'xsd:string'),
			'Edad' => array('name' => 'Edad', 'type' => 'xsd:int'),
			'Sexo' => array('name' => 'Sexo', 'type' => 'xsd:string')
		)
	);

	$server->wsdl->addComplexType(
		'TWsArrayOfPersona',
		'complexType',
		'array',
		'',
		'SOAP-ENC:Array',
		array(),
		array(
			array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:TWsPersona[]')			 	
		),
		'tns:TWsPersona'
	);

	$server->wsdl->addComplexType(
	    'TWsResultadoSorteo',
	    'complexType',
	    'struct',
	    'all',
	    '',
	    array(
		'saludo' => array('name' => 'saludo', 'type' => 'xsd:string'),
		'ganador' => array('name' => 'ganador', 'type' => 'xsd:boolean')
	    )
	);

	// Definición de métodos en nuestro servicio web -------------------------------------------

	$server->register(
	    'LoginCliente',                		                          // Nombre del método
	    array('usuario' => 'xsd:string', 'password' => 'xsd:string'),      // Parámetros de entrada
	    array('return' => 'xsd:int'),                                     // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                                          // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'LoginCliente',                              // Acción soap
	    'rpc',                                                    // Estilo
	    'encoded',                                                  // Uso
	    'verifica login del cliente contra la base de datos, 1 si es ok'       	  // Documentación
	);

	$server->register(
	    'CodigoCliente',                		  // Nombre del método
	    array('usuario' => 'xsd:string'),      // Parámetros de entrada
	    array('return' => 'xsd:string'),      // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'CodigoCliente',        // Acción soap
	    'rpc',                                // Estilo
	    'encoded',                            // Uso
	    'retorna el codigo GCM del cliente pasado como parametro'       	  // Documentación
	);

	$server->register(
	    'RegistroCliente',                		                          // Nombre del método
	    array('usuario' => 'xsd:string', 'regGCM' => 'xsd:string'),      // Parámetros de entrada
	    array('return' => 'xsd:int'),                                     // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                                          // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'RegistroCliente',                              // Acción soap
	    'rpc',                                                    // Estilo
	    'encoded',                                                  // Uso
	    'registra o actualiza un cliente junto a su codigo CGM'       	  // Documentación
	);

	$server->register(
	    'getUbicacion',           		  	 // Nombre del método
	    array('usuario' => 'xsd:string', 'cantidad' => 'xsd:int'),    	 			 // Parámetros de entrada
	    array('return' => 'tns:TWsArrayOfUbicacion'),  // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                	 // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'getUbicacion',        // Acción soap
	    'rpc',                                	 // Estilo
	    'encoded',                            	 // Uso
	    'Devuelve un array de ubicaciones' 		 // Documentación
	);

	$server->register(
	    'hola',                		  // Nombre del método
	    array('nombre' => 'xsd:string'),      // Parámetros de entrada
	    array('return' => 'xsd:string'),      // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'#hola',        // Acción soap
	    'rpc',                                // Estilo
	    'encoded',                            // Uso
	    'Saluda a la persona'       	  // Documentación
	);

	$server->register(
	    'IniciarSorteo',           		  	 // Nombre del método
	    array('Persona' => 'tns:TWsPersona'),    	 // Parámetros de entrada
	    array('return' => 'tns:TWsResultadoSorteo'), // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                	 // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'#IniciarSorteo',      // Acción soap
	    'rpc',                                	 // Estilo
	    'encoded',                            	 // Uso
	    'Saludar y devuelve el resultado del sorteo' // Documentación
	);

	$server->register(
	    'GetPersonas',           		  	 // Nombre del método
	    array(),    	 			 // Parámetros de entrada
	    array('return' => 'tns:TWsArrayOfPersona'),  // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                	 // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'#GetPersonas',        // Acción soap
	    'rpc',                                	 // Estilo
	    'encoded',                            	 // Uso
	    'Devuelve un array de personas' 		 // Documentación
	);

	$server->register(
	    'getAlertas',           		  	 // Nombre del método
	    array('usuario' => 'xsd:string', 'cantidad' => 'xsd:string'),    	 			 // Parámetros de entrada
	    array('return' => 'tns:TWsArrayOfAlerta'),  // Parámetros de salida
	    SOAP_SERVER_NAMESPACE,                	 // Nombre del workspace
	    SOAP_SERVER_NAMESPACE.'getAlertas',        // Acción soap
	    'rpc',                                	 // Estilo
	    'encoded',                            	 // Uso
	    'Devuelve un array de alertas' 		 // Documentación
	);
?>
