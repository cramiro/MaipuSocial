<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ('application/libraries/SugarTalker.php');

class Social extends CI_Controller {
	var $items;
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public function save_search()
    {
		$this->load->helper('url');
        //$this->load->helper('form');
        //$this->load->library('form_validation');

        $search = new Entities\Search;
        $search->setIsTemp($this->input->post('is_temp'));
        $search->setKeywords($this->input->post('keywords'));
        $search->setExcludeWords($this->input->post('exclude_words'));
        $search->setUpdated(new Datetime());

        $this->doctrine->em->persist($search);
        $this->doctrine->em->flush();

        //echo "Success!";
		redirect('social/admin');
    }

    public function test()
    {
        // Busco Search con id 2
        $search = $this->doctrine->em->find('Entities\Search', 2);

        // Le asocio la red pownce (id 2)
        $network = $this->doctrine->em->find('Entities\Network', 2);
        
        $search->addNetwork($network);
        $this->doctrine->em->persist($search);
        $this->doctrine->em->flush();
        
    }

    public function test2()
    {
        // Busco Search con id 1 e item con id 1
        $search = $this->doctrine->em->find('Entities\Search', 1);
        //$item = $this->doctrine->em->find('Entities\Item', 1);
		echo "Inside test2";

        // listo sus resultados
        $l = $search->getItems();
        foreach ($l as $item){
            echo $item->getDescription()."<br>";
            //echo "blah";
        }
    }

    public function test3()
    {
        // Busco Search con id 1 y creo item
        $search = $this->doctrine->em->find('Entities\Search', 1);
        $network = $this->doctrine->em->find('Entities\Network', 1);
        $item = new Entities\Item;
        $item->setNetwork($network);
        $item->setSearch($search);
        $item->setDescription("Resultado pownce 3");
        $item->setDomain("pownce.com");
        $item->setEmbed("");

        $this->doctrine->em->persist($item);
        $this->doctrine->em->flush();
    }

    private function check_expired_searches()
    {

    }

    private function delete_temp_search()
    {

    }

    private function perform_search($search)
    {
        // Populate dict plugin_networks[engine][network] so in the
        // first level we got an engine name, and in the second level
        // every network that engine has to search.
        $plugin_networks = array();
        foreach($search->getNetworks() as $network){
            //echo "Searching network: ".$network->getName()."<br>";
            $engine = $network->getDefaultEngine();
            //echo "Search using engine: ".$engine->getName()."<br>";

            if(array_key_exists($engine->getName(), $plugin_networks)){
                // Add network to the engine
                $plugin_networks[$engine->getName()][] = $network->getName();
            }else{
                // Add key to the array
                $plugin_networks[$engine->getName()] = array($network->getName());
            }
        }
        //echo "<pre>";var_dump($plugin_networks);echo "</pre>";


        // We know now about which plugin has to search which network
        // Load plugin
        /*$this->load->driver('Engine');
        echo "Ok load driver";
        $this->engine->socialmention->twitter('Hola');*/
        require_once('application/libraries/iEngine.php');
		$motor = 'Socialmention';
        //require_once('application/libraries/'.$engine->getName().'.php');
		require_once('application/libraries/'.$motor.'.php');
        //echo "Ok load library<br>";

        $engine_name = $engine->getName();
        $engine = new $engine_name();
        //echo "<pre>";var_dump($engine);echo "</pre><br>";

        //echo "Search networks: <pre>";var_dump($plugin_networks[$engine_name]);echo "</pre><br>";
       
        // Call plugin
		$result = $engine->search($this->doctrine->em, $search->getKeywords(), array(), $plugin_networks[$engine_name]);
		return $result;
    }

	public function send(){
		$this->load->helper('url');
        $this->load->helper('form');


		$sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );

        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

		$navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'TRUE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'FALSE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';


		$url = 'http://sugarcrmcopy.amaipu.com.ar:81/soap.php?wsdl';
        $user = 'williweb';
        $clave = 'w14fr3d4';
        $modules = array('Contacts', 'Opportunities', 'Cases');

		$instancia = new InstanciaSugar( $url, $user, $clave, $modules);

		//echo "<pre>";
        //print_r($instancia);
        //echo "</pre>";

        // Genero consulta para ver obtener encuesta
		$tipo_objeto = $this->input->post('objecttype');
		if ( $tipo_objeto == 'case' ){
			$objeto = new ObjetoSugar($instancia->modulos['Cases']);
			$objeto->modificar_campo('name', 'Caso - Redes Sociales');
			$objeto->modificar_campo('via_ingreso_c', 'Redes sociales internet');

		}elseif( $tipo_objeto == 'opportunity'){
			$rcastro = '9142b140-98b0-9ab4-0f55-4bbb1cd12d31';
			$objeto = new ObjetoSugar($instancia->modulos['Opportunities']);
			$objeto->modificar_campo('name', 'Oportunidad - Redes Sociales');
			$objeto->modificar_campo('lead_source', 'Redes sociales internet');
			$objeto->modificar_campo('assigned_user_id', $rcastro);
		}

		$objeto->modificar_campo('description', $this->input->post('objectdescription') );
		

		echo "<pre>";
		print_r( $objeto->grabar());
		echo "</pre>";
        $i = 0;
        foreach ($result as $objeto){
            echo "OBJETO {$i}<pre>";
            echo ($objeto->obtener_campo('last_name'));
            echo "</pre>";
            $i++;
        }

		$this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_search_panel', $data);
        //$this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);

	}

	public function index()
	{
       	/*$search = $this->doctrine->em->find('Entities\Search', 1);
		//echo "<pre>"; print_r($search);	echo "</pre>";
        $this->perform_search($search);
		return;*/
		$this->load->helper('url');
        $this->load->helper('form');

		$url = 'http://sugarcrm.amaipu.com.ar/soap.php?wsdl';
		$user = 'williweb';
		$clave = 'w14fr3d4';
		$modules = array('Contacts');
		$ldap_key = 'abc123';
		$ldap_iv = 'password';
		//$instancia = new InstanciaSugar( $url, $user, $clave, $modules);
		// Genero consulta para ver obtener encuesta
		//echo "<pre>";
		//print_r($instancia);
		//echo "</pre>";
	    $modulo = 'Contacts';
    	$query = " contacts.deleted = 0 and contacts.last_name like 'castro%' ";
    	$limit = 5;
    	/*$result = $instancia->modulos['Contacts']->buscar(0, $limit, $query);

		$i = 0;
		foreach ($result as $objeto){
			echo "OBJETO {$i}<pre>";
			echo ($objeto->obtener_campo('last_name'));
			echo "</pre>";
			$i++;
		}*/

		$sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );

        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

		$navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'TRUE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'FALSE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

        //$this->load->view('templates/bootstrap/fluid', $data);
        $this->load->view('templates/bootstrap/fluid_header', $data);
		$this->load->view('templates/bootstrap/fluid_search_panel', $data);
        //$this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);
	}

	public function toSugar()
    {
        $this->load->helper('url');

        $data['title'] = 'News archive';
		$sidebar1['item1'] = array(
				'item_name' 	=> 		'Facebook',
				'item_link'  	=>		'http://facebook.com'
				);
		$sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
		$sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );

		$data['sidebar'] = array(
				'Sidebar 1'		=> 		$sidebar1,
				'Sidebar 2'		=>		$sidebar1,
                'Sidebar 3'     =>      $sidebar1,
				);

		$navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'FALSE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'TRUE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

        $this->load->helper('form');
		$item = $this->input->post('item');
		$data['item'] = $item;
		//echo "<pre>"; print_r($this->input->post()); echo "</pre>";
        if ( $item !== FALSE){
            $this->load->view('templates/bootstrap/fluid_header', $data);
            $this->load->view('templates/bootstrap/fluid_toSugar', $data);
            $this->load->view('templates/bootstrap/fluid_footer', $data);
        }else{
            redirect('social');
        }

    }

	public function search(){

		// Cargo los helpers que voy a necesitar
		$this->load->helper('url');		// Para .css y .js de los templates
		$this->load->helper('form'); 	// Para formulario de busqueda

		// Seteo variables que voy a usar en los templates
		// Hacer un get de las primeras 3 networks
		// Hacer un get de las primeras 3 busquedas guardas
		// Crear links a SugarCRM y Zimbra:q

		$sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );

        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

		$navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'FALSE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'TRUE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

		// Obtengo los valores de la busqueda
        $search = $this->input->post('keywords');

        $opciones = array(
            'facebook'	=> 	$this->input->post('facebook'),
            'blogs' 	=> 	$this->input->post('blogs'),
            'foros' 	=> 	$this->input->post('foros'),
            );
		
		// Obtengo el id de los networks
		// Armo un array con las networks seleccionadas

		/*
		$networks = array();
		foreach ($checkbox as $key => $val){
			$id = $val;
			$networks[] = $this->doctrine->em->find('Entities\Network', $id);
		}

        echo "<pre>";
		echo $search;
        print_r($opciones);
        echo "</pre>";
		*/

		// Creo y guardo la busqueda
		$search = new Entities\Search;
        $search->setIsTemp($this->input->post('is_temp'));
        $search->setKeywords($this->input->post('keywords'));
        $search->setExcludeWords($this->input->post('exclude_words'));
        $search->setUpdated(new Datetime());

		// Guarda busqueda en la DB solo si no HOT-SEARCH
        //$this->doctrine->em->persist($search);
        //$this->doctrine->em->flush();

        //echo "Success!";

		// Relaciono la busqueda recien creada con networks
		// no es necesario por ahora

		// Pido al modelo que haga la busqueda
		//$result = perform_search($search, $networks);

		// test search
		$search = $this->doctrine->em->find('Entities\Search', 2);
        $result = $this->perform_search($search);
        echo "<pre>"; Doctrine\Common\Util\Debug::dump($result); echo "</pre>";
		try {
			$result = $result->items;
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
			echo "<pre>"; print_r($result);echo "</pre>";
		}

		//echo "<pre>"; print_r($result);echo "</pre>";

		// Recupero resultados y los paso a las vistas 
		$items = array();
		foreach ($result as $key => $val){
				// $val es stdClass Object con campos
				// title - description - link - timestamp - image - embed - language - user
				// user_image - user_link - user_id - geo - source - favicon - type - domain - id
			$item = array();
			$item['source'] = $val->source;

			if ( $item['source'] == 'twitter'){
				$item['title'] = 'Twitt';
				$item['description'] = $val->title;
				$item['user'] = '@'.$val->user;
			}else{
				$item['title'] = $val->title;
				$item['description'] = $val->description;
			}
			$item['link'] = $val->link;
            $item['user_link'] = $val->user_link;
            $item['domain'] = $val->domain;
            $item['user_image'] = $val->user_image;
			$item['timestamp'] = $val->timestamp;

			$items[]=$item;
		}
		$data['items'] = $items;
		$this->items = $items;
		// Cargo las vistas
        $this->load->view('templates/bootstrap/fluid_header', $data);
		$this->load->view('templates/bootstrap/fluid_search_panel', $data);
        $this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);
        //$data['social_items']= $this->social_model->get_search();
    }

	public function admin(){
		$busquedas = array();
        $busquedas[] = array(
                'db_name'       =>      'Mundo Maipu',
                'label'         =>      'Mundo Maipu',
				'keywords'		=>		'mundo maipu gm verano carlos paz',
				'exclude_words'	=>		'san cristobal seguros',
                );
        $busquedas[] = array(
                'db_name'       =>      'Concesionarias',
                'label'         =>      'Concesionarias',
				'keywords'      =>      'concesionaria grande ubicacion donde',
                'exclude_words' =>      'motos barcos',
                );
        $busquedas[] = array(
                'db_name'       =>      'Oportunidades',
                'label'         =>      'Oportunidades',
				'keywords'      =>      'comprar auto nuevo 0km',
                'exclude_words' =>      'juguete ojala',
                );


        // Cargo los helpers que voy a necesitar
        $this->load->helper('url');
        $this->load->helper('form');

        // Seteo variables que voy a usar en los templates
        $sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );

		$data['options'] = $busquedas;
        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

        $navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'FALSE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'FALSE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'TRUE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

        // Cargo las vistas
        $this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_edit_search', $data);

		$busqueda= $this->input->post('lista-busqueda');

		if ($this->input->post('lista-busqueda') != '' ){
			echo "<pre>";
			print_r($this->input->post());
			echo "</pre>";
			// si seleccionaron una busqueda, muestro formulario edit
			$this->load->view('templates/bootstrap/fluid_edit_search_form', $data);
		}
        $this->load->view('templates/bootstrap/fluid_footer', $data);

	}


	public function guardadas(){
		/* Esta funcion se encarga de cargar todas las busquedas
		guardas y mostrarlas en forma de lista o con desplegable
		o con un buscador que autocomplete.

		Tambien puede permitir crear y guardar nuevas busquedas.

		*/
		$busquedas = array();
		$busquedas[] = array(
				'db_name'		=>		'Mundo Maipu',
				'label'			=>		'Mundo Maipu'
				);
		$busquedas[] = array(
				'db_name'		=>		'Concesionarias',
				'label'			=>		'Concesionarias'
				);
		$busquedas[] = array(
				'db_name'		=>		'Oportunidades',
				'label'			=>		'Oportunidades'
				);


		// Cargo los helpers que voy a necesitar
		$this->load->helper('url');
		$this->load->helper('form');

		// Seteo variables que voy a usar en los templates
        $sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );
		$data['options'] = $busquedas;
        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

		$navigator = array(
				'Home'		=>	array(
								'link'		=>	site_url('social'),
								'active'	=> 	'FALSE',
								),
				'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'TRUE',
                                ),
				'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
				);
		
		$data['navigator'] = $navigator;
		$data['brand'] = 'MaipuSocial';
		$data['username'] = 'user';
		// Cargo las vistas
		$this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_saved_searches', $data);
        //$this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);
	}

    public function search_form()
    {
//        $this->load->view('blogview');
		$this->load->helper('url');
        $this->load->helper('form');

        // Seteo variables que voy a usar en los templates
        $sidebar1['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      'Yahoo!',
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );
        $data['sidebar'] = array(
                'Sidebar 1'     =>      $sidebar1,
                'Sidebar 2'     =>      $sidebar1,
                'Sidebar 3'     =>      $sidebar1,
                );

		$navigator = array(
                'Home'      =>  array(
                                'link'      =>  site_url('social'),
                                'active'    =>  'FALSE',
                                ),
                'Guardadas'      =>  array(
                                'link'      =>  site_url('social/guardadas'),
                                'active'    =>  'TRUE',
                                ),
                'Admin'      =>  array(
                                'link'      =>  site_url('social/admin'),
                                'active'    =>  'FALSE',
                                ),
                );

        $data['navigator'] = $navigator;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';
/*        echo form_open('social/save_search');

        $datas = array(
              'name'        => 'keywords',
              'id'          => 'keywords',
              'value'       => '',
              'maxlength'   => '80',
              'size'        => '80',
        );
        echo form_input($data);

        $datas = array(
              'name'        => 'exclude_words',
              'id'          => 'exclude_words',
              'value'       => '',
              'maxlength'   => '80',
              'size'        => '80',
        );
        echo form_input($datas);

        echo form_checkbox('is_temp', 'dunno', TRUE);
        echo form_submit('submit', 'Submit');
        echo form_close("");*/
		
		$this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_edit_search', $data);
        //$this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);
    }

	public function cron()
	{
		echo "This will be called periodically.";
	}

	public function crear_item()
	{
		$item = new Entities\Item;
        $item->setId(3);
        $item->setDescription('Mundo');
        $item->setDomain('reddit.com');
        $item->setEmbed('nada');

        $this->doctrine->em->persist($item);
        $this->doctrine->em->flush();

        echo "Success!";
	}
}

