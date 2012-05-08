<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ('application/libraries/SugarTalker.php');

//Clase principal
class Social extends CI_Controller {
    var $items;

    function _networks(){
        $networks = $this->doctrine->em->getRepository('Entities\Network')->findBy(array());
        $result = array();
        foreach ($networks as $network){
            $result[] = $network->getName();
        }
        return $result;
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -  
     *         http://example.com/index.php/welcome/index
     *    - or -
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

        $search_id = $this->input->post('search_id');
        echo "Search id : {$search_id}";

        if ( $search_id == '' ){
            // Si no tiene id => creo nueva busqueda
            $search = new Entities\Search;
        }else{
            // Si tiene id => lo busco para actualizar
            $search = $this->doctrine->em->find('Entities\Search', $search_id);
        }
        $search->setIsTemp($this->input->post('is_temp'));
        $search->setName($this->input->post('search-name'));
        $search->setKeywords($this->input->post('keywords'));
        $search->setExcludeWords($this->input->post('exclude_words'));
        $search->setUpdated(new Datetime());

        $this->doctrine->em->persist($search);
        $this->doctrine->em->flush();

        //echo "Success!";
        redirect('social/admin');
    }

    public function temp_to_saved_search($id)
    {
        $this->load->helper('url');

        if ( $id != '' ){
            // Si tiene id => lo busco para actualizar
            $search = $this->doctrine->em->find('Entities\Search', $id);

            $search->setIsTemp(false);
            $this->doctrine->em->persist($search);
            $this->doctrine->em->flush();

            //echo "Success!";
        }
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

    public function send(){
        $this->load->helper('form');

        $data['sidebar'] = $this->sidebar();

        $data['here'] = 'Home';
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';


        $url = 'http://sugarcrmcopy.amaipu.com.ar:81/soap.php?wsdl';
        $user = 'williweb';
        $clave = 'w14fr3d4';
        $modules = array('Contacts', 'Opportunities', 'Cases');

        $instancia = new InstanciaSugar( $url, $user, $clave, $modules);

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

        $data['sources'] = $this->_networks();

        $this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_search_panel', $data);
        //$this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);

    }

    public function index()
    {
           /*$search = $this->doctrine->em->find('Entities\Search', 1);
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

        $data['sidebar'] = $this->sidebar();

        $data['here'] = 'Home';
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

        $data['sources_sess'] = $options = $this->session->userdata('source');
        $data['sources'] = $this->_networks();
        $data['input_value'] = '';

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
        $data['sidebar'] = $this->sidebar();

        $data['here'] = 'Guardadas';
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
//echo "<pre>"; var_dump($this->session->userdata('source')); echo "</pre>";
//echo "<pre>"; var_dump($this->session->userdata('keywords')); echo "</pre>";
        // Cargo los helpers que voy a necesitar
        $this->load->helper('form');     // Para formulario de busqueda
        
        // Cantidad de item por pagina
        $slice = 15;		

        // Seteo variables que voy a usar en los templates
        // Hacer un get de las primeras 3 networks
        // Hacer un get de las primeras 3 busquedas guardas
        // Crear links a SugarCRM y Zimbra:q

        $data['sidebar'] = $this->sidebar();

        $esGuardada = FALSE;
        $here = 'Home';
        if ($this->input->post('lista-busqueda') OR $this->uri->segment(3) ){
            $esGuardada = TRUE;
        }
        
        $data['here'] = $here;
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';
		
        if ( $this->uri->segment(3) === FALSE){
            // Obtengo los valores de la busqueda
            $options = $this->input->post('source');
            // La guardo en la sesion
            $this->session->set_userdata('source', $options);
            $this->session->set_userdata('keywords', $this->input->post('keywords'));

            if ($this->input->post('lista-busqueda')){
                $search = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                    array("id" => $this->input->post('lista-busqueda'))  );
                echo "keyword -> ".$search->getKeywords();
            
            }else{
                // Me fijo si hay una busqueda con esas keywords. Sino, la creo y la guardo
                $search = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                    array("keywords" => $this->input->post('keywords'))
                    );
            }
            if(!$search){
                echo "Nueva busqueda";
                $search = new Entities\Search;
                $search->setIsTemp(true);
                $search->setKeywords($this->input->post('keywords'));
                $name = preg_replace('/\W.*/','',$this->input->post('keywords'));
                $search->setName($name);
                $search->setDescription('descripcion');
                $search->setAdded(new DateTime('now'));
                $search->setExcludeWords($this->input->post('exclude_words'));
    
                $this->doctrine->em->persist($search);
    
                // Pido a la libreria que realice la busqueda
                $this->load->library('search');
                $this->search->perform_search($this->doctrine->em, $search);
                
                $search = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                    array("keywords" => $this->input->post('keywords'))
                    );
            }

            $offset = 0;
            $page = 0;
            $searchID = $search->getID();
            $this->session->set_userdata('search_id', $searchID);


        }else{
            $searchID = $this->uri->segment(3);
            $page = $this->uri->segment(4);
            $search = $this->doctrine->em->find('Entities\Search', $searchID);
            $offset = $page;

        }
        $options = $this->session->userdata('source');

        /* Guardo en la sesion las ultimas 3 busquedas utilizadas */
        if($this->session->userdata('last1') != $searchID){
            /* Debo rotar las otras busquedas */
            $temp_search_id = $this->session->userdata('last2');
            $this->session->set_userdata('last3', $temp_search_id);

            $temp_search_id = $this->session->userdata('last1');
            $this->session->set_userdata('last2', $temp_search_id);

            $this->session->set_userdata('last1', $searchID);
        }
/*echo "<pre>"; var_dump($this->session->userdata('last1')); echo "</pre>";
echo "<pre>"; var_dump($this->session->userdata('last2')); echo "</pre>";
echo "<pre>"; var_dump($this->session->userdata('last3')); echo "</pre>";*/

        $this->session->set_userdata('source', $options);


        $this->load->library('pagination');
        $config['base_url'] = base_url()."index.php/social/search/{$searchID}/";
        $total = count($search->getResults($this->session->userdata('source')));
        $config['total_rows'] = $total;
        $config['per_page'] = 15;
        $config['uri_segment'] = '4';
        
        // Primera pagina
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_link'] = 'Primera';

        // Siguiente
        $config['next_link'] = '&rarr;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
    

        // Anterior
        $config['prev_link'] = '&larr;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';

        // Ultima pagina
        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li class="next">';
        $config['last_tag_close'] = '</li>';

        // Pagina actual
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        // Digitos de pagina
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['full_tag_open'] = "<div class='container'><div class='pagination center'><ul>";
        $config['full_tag_close'] = '</ul></div></div>';

        $this->pagination->initialize($config);
        $pagelinks = $this->pagination->create_links();


        // Recupero resultados y los paso a las vistas
        $result = $search->getSliceResults($offset, $slice, $this->session->userdata('source'));

        $items = array();
        foreach ($result as $key => $val){
            $item = array();
            $item['source'] = $val->getNetwork();

            if ( $item['source'] == 'twitter'){
                $item['title'] = 'Twit';
                $item['description'] = $val->getTitle();
                $item['user'] = '@'.$val->getUser();
            }else{
                $item['title'] = $val->getTitle();
                $item['description'] = $val->getDescription();
                $item['user'] = $val->getUser();
            }
            $item['link'] = $val->getLink();
            $item['user_link'] = $val->getUserLink();
            $item['domain'] = $val->getDomain();
            $item['user_image'] = $val->getUserImage();
            $item['timestamp'] = $val->getTimestamp()->format('d/m/Y h:i:s A');
            $item['has_been_seen'] = $val->getSeen();

            // Marco los items como vistos
            $val->setSeen(true);

            $items[]=$item;
        }
        // Actualizo los cambios (seen) en la base de datos
        $this->doctrine->em->flush();
        $data['items'] = $items;
        $data['pagelinks'] = $pagelinks;
        //echo "Search ID -> ".$search->getID();
        $data['idSearch'] = $search->getID();
        $data['page'] = $page;
        $this->items = $items;

        $data['sources'] = $this->_networks();
        $data['sources_sess'] = $options;
        $data['input_value'] = $this->session->userdata('keywords');
        // Cargo las vistas
        $this->load->view('templates/bootstrap/fluid_header', $data);
        if ($esGuardada){
        	$here = 'Guardadas';
            $data['options'] = $this->getSavedSearches();
            $data['searchID'] = $search->getID();
            $this->load->view('templates/bootstrap/fluid_saved_searches', $data);
        }else{
            $this->load->view('templates/bootstrap/fluid_search_panel', $data);
        }
        $this->load->view('templates/bootstrap/fluid_items', $data);
        $this->load->view('templates/bootstrap/fluid_footer', $data);
        //$data['social_items']= $this->social_model->get_search();
    }

    public function admin(){
        
        $searches = $this->doctrine->em->getRepository('Entities\Search')->findAll();
        $busquedas = array();
        foreach ($searches as $search)
        {
            $new = array(
                'id'     => $search->getId(),
                'name'    => $search->getName(),
                );
            $busquedas[] = $new;
        
        }

        // Cargo los helpers que voy a necesitar
        $this->load->helper('url');
        $this->load->helper('form');

        $data['options'] = $busquedas;
        $data['sidebar'] = $this->sidebar();

        $data['here'] = 'Admin';
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';

        // Cargo las vistas
        $this->load->view('templates/bootstrap/fluid_header', $data);
        $this->load->view('templates/bootstrap/fluid_edit_search', $data);

        $busqueda = $this->input->post('lista-busqueda');

        if ($this->input->post('lista-busqueda') != '' ){

            $search = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                    array("id" => $this->input->post('lista-busqueda')) );
            $data['busqueda'] = $search;
            $data['searchID'] = $this->input->post('lista-busqueda');
            // si seleccionaron una busqueda, muestro formulario edit
            $this->load->view('templates/bootstrap/fluid_edit_search_form', $data);
        }
        $this->load->view('templates/bootstrap/fluid_footer', $data);

    }

    private function sidebar(){
        //$searches = $this->getSavedSearches();
        
        if($this->session->userdata('last1')){
            $search1_name = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                array("id" => $this->session->userdata('last1')))->getName();
        }
        if($this->session->userdata('last2')){
            $search1_name = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                array("id" => $this->session->userdata('last2')))->getName();
        }
        if($this->session->userdata('last3')){
            $search1_name = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                array("id" => $this->session->userdata('last3')))->getName();
        }
        
        // Seteo variables que voy a usar en los templates
        $sidebar1['item1'] = array(
                'item_name'     =>      $search1_name,
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar1['item2'] = array(
                'item_name'     =>      $search2_name,
                'item_link'     =>      'http://yahoo.com'
                );
        $sidebar1['item3'] = array(
                'item_name'     =>      $search3_name,
                'item_link'     =>      'http://twitter.com'
                );

        // Seteo variables que voy a usar en los templates
        $sidebar2['item1'] = array(
                'item_name'     =>      'Facebook',
                'item_link'     =>      'http://facebook.com'
                );
        $sidebar2['item2'] = array(
                'item_name'     =>      'Twitter',
                'item_link'     =>      'http://twitter.com'
                );
        $sidebar2['item3'] = array(
                'item_name'     =>      'YouTube',
                'item_link'     =>      'http://youtube.com'
                );

        // Seteo variables que voy a usar en los templates
        $sidebar3['item1'] = array(
                'item_name'     =>      'SugarCRM',
                'item_link'     =>      'http://sugarcrm.amaipu.com.ar'
                );
        $sidebar3['item2'] = array(
                'item_name'     =>      'Zimbra',
                'item_link'     =>      'http://zimbra.amaipu.com.ar'
                );
        
        return array(
                'Búsquedas Guardadas'     =>      $sidebar1,
                'Redes Sociales'     =>      $sidebar2,
                'Mundo Maipú'     =>      $sidebar3,
                );
    }
    
    private function getSavedSearches(){
        $searches = $this->doctrine->em->getRepository('Entities\Search')->findAll();
        $busquedas = array();
        foreach ($searches as $search)
        {
            $new = array(
                'id'     => $search->getId(),
                'name'    => $search->getName(),
                );
            $busquedas[] = $new;
        
        }
        return $busquedas;
    
    }
    public function delete( $searchID = NULL){
        if (NULL == $searchID){
            redirect ('/social');
            return;
        }
        
        $search = $this->doctrine->em->getRepository('Entities\Search')->findOneBy(
                    array("id" => $searchID) );
        $this->doctrine->em->remove($search);
        $this->doctrine->em->flush();
        
        redirect ('/social/admin');
    }
    
    public function guardadas(){
        /* Esta funcion se encarga de cargar todas las busquedas
        guardas y mostrarlas en forma de lista o con desplegable
        o con un buscador que autocomplete.

        Tambien puede permitir crear y guardar nuevas busquedas.

        */

        $data['options'] = $this->getSavedSearches();
        // Cargo los helpers que voy a necesitar
        $this->load->helper('url');
        $this->load->helper('form');
        
        $data['sidebar'] = $this->sidebar();
        $data['here'] = 'Guardadas';
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';
        $data['sources'] = $this->_networks();
        
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

        $data['sidebar'] = $this->sidebar();


        $data['here'] = 'Guardadas';
        $data['brand'] = 'MaipuSocial';
        $data['username'] = 'user';
        
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

