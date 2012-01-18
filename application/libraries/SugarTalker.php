<?php
require_once("nusoap-0.9.5/lib/nusoap.php");
function debug($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
	return true;
}

class InstanciaSugar{
	var $url;
	var $wsdl;
    var $sesion;
	var $usuario;
	var $clave;
	var $modulos = array();
    
	public function __construct($url='http://sugarcrm.amaipu.com.ar/soap.php?wsdl',
								$usuario,
								$clave,
								$modulos){

		$this->usuario = $usuario;
		$this->url = $url;
		$this->clave = $clave;

        //$soapclient = new soapclient($soap_url);//,true);
        $soapclient = new nusoap_client($url,true);
        $this->wsdl = $soapclient->getProxy();

        $user_name = $usuario;
        $user_password = $clave;
        $app_name = 'myniceprogram';
        $key = 'abc123';  // LDAP Key as entered in Sugar
        $key = substr(md5($key),0,24);
        $iv = 'password';  // note that this is the word password, not the user's password 

		$ldap_hash = bin2hex(mcrypt_cbc(MCRYPT_3DES, $key, $user_password, MCRYPT_ENCRYPT, $iv));

		$params = array(
            'user_name' => $user_name,
            'password'  => $ldap_hash,
            'version'   => '.1'
            );

        $result = $this->wsdl->login($params,$app_name);

        $this->sesion = $result['error']['number']==0 ? $result['id'] : null;


		foreach ($modulos as $modulo){
			$this->modulos[$modulo] = new ModuloSugar($this, $modulo);
		}

        return $this->sesion;
    }

	function relacionar( $principal, $secundario){
		$rel = array();
		$rel['module1'] = $principal->modulo;
		$rel['module1_id'] = $principal->id;
		$rel['module2'] = $secundario->modulo;
		$rel['module2_id'] = $secundario->id;
		
		$result = $this->wsdl->set_relationship( $this->sesion, $rel);

		return $result;
	}

}


class ModuloSugar{
	var $nombre_modulo;
	var $instancia;
	var $campos = array() ;
	var $campos_requeridos = array();
	
	public function __construct($instancia, $modulo){
		$this->instancia = $instancia;
		$this->nombre_modulo = $modulo;

		$resultado = $this->instancia->wsdl->get_module_fields($this->instancia->sesion,
																$this->nombre_modulo);

		foreach($resultado['module_fields'] as $campo){
			// Agrego el campo a la lista de campos del modulo
			$this->campos[] = $campo['name'];
		
			// Si es requerido, lo agrego a la lista de campos requeridos
			if ($campo['required'] != 0){
				$this->campos_requeridos[] = $campo['name'];
			}
		}
	}

	public function buscar( $inicio=0, $cantidad=20, $consulta){

        $result = $this->instancia->wsdl->get_entry_list(
            $this->instancia->sesion,
            $this->nombre_modulo,
            $consulta,
            '',					// order by
            $inicio,
            array(),			// campos
            $cantidad,
            false				// eliminados
        );

        $lista = array();
        for ( $i =0; $i < $result['result_count']; $i++){
            $valores_iniciales = array();

            foreach( $result['entry_list'][$i]['name_value_list'] as $atributo){
                $valores_iniciales[$atributo['name']] = $atributo['value'];
				//echo $atributo['name']." -> ".$atributo['value']."<br >";
            }
            $objeto = new ObjetoSugar($this, $valores_iniciales);
            $lista[] = $objeto;
        }


        return $lista;
    }

	
}

class ObjetoSugar{
	var $modulo;
	var $campos = array();
	var $campos_sucios = array();

	public function __construct( $modulo, $valores_iniciales = array() ){
		$this->modulo = $modulo;
		
		foreach ( $this->modulo->campos as $campo){

			// Si el campo esta en los valores iniciales,
			// entonces lo inicializo con ese valor
			if ( array_key_exists($campo, $valores_iniciales) ){
				$this->campos[$campo] = $valores_iniciales[$campo];
			}else{
				$this->campos[$campo] = null;
			}

		}		
	}

	public function obtener_campo($nombre_campo){
		return $this->campos[$nombre_campo];
	}


	public function modificar_campo($nombre_campo, $valor){

		// Asigno nuevo valor
		$this->campos[$nombre_campo] = $valor;

		// Marco el campo como sucio, si no estaba antes
		if ( ! in_array($nombre_campo, $this->campos_sucios)){
			$this->campos_sucios[] = $nombre_campo;
		}
	}

	public function grabar(){

		// Si ID no es null, entonces lo agrego a campos sucios
		// para que sugar haga un update y no insert.
		if ( $this->obtener_campo('id') != null){
			$this->campos_sucios[] = 'id';
		}
		
		// Armo el array name_value_list
		$nvl = array();
		foreach ( $this->campos_sucios as $campo){
			$nv = array();
			$nv['name'] = $campo;
			$nv['value'] = $this->obtener_campo($campo);
			$nvl[] = $nv;
		}

		$resultado = $this->modulo->instancia->wsdl->set_entry(
													$this->modulo->instancia->sesion,
													$this->modulo->nombre_modulo,
													$nvl);

		$this->modificar_campo('id', $resultado['id']);
	
		if ( $resultado['error']['number'] == 0 ) {
			$this->campos_sucios = array();
		}

		return $resultado['error'];
	}

}

?>
