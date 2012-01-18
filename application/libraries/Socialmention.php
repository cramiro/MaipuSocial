<?php

class Socialmention implements iEngine {

    private $url = 'http://api2.socialmention.com/search';

    public function search($em, $keywords, $exclude_words, $networks){
        // Make request to Socialmention API service
/*        require_once('HTTP/Request2.php');
        $r = new HTTP_Request2($this->url, HTTP_Request2::METHOD_GET);

        // Make network names lowercase, otherwise Socialmention complains
        foreach ($networks as &$network){
            $network = strtolower($network);
        }

        $url = $r->getUrl();
//        echo "***:"; var_dump($url);
        $url->setQueryVariables(array(
            'q' => str_replace(' ', '+', $keywords),
            'src' => $networks,
            'f' => 'json'
        ));

        try {
            $response = $r->send();
            if ($response->getStatus() == 200) {
                $body = $response->getBody();
            }
        } catch (HTTP_Request2_Exception $ex) {
            echo "Error: ".$ex->getMessage();
        }
*/
$body = '{"count":2,"items":[{"title":"Ay mundo detente pronto. http:\/\/t.co\/WpYyPIfJ","description":"","link":"http:\/\/twitter.com\/jpsanmartinr\/statuses\/154564541169143808","timestamp":1325686032,"image":null,"embed":null,"language":"es","user":"jpsanmartinr","user_image":"http:\/\/a1.twimg.com\/profile_images\/1370074918\/2_Ico_300_normal.jpg","user_link":"http:\/\/twitter.com\/jpsanmartinr","user_id":28816658,"geo":null,"source":"twitter","favicon":"http:\/\/twitter.com\/favicon.ico","type":"microblogs","domain":"twitter.com","id":"13204452089901836808"},{"title":"I\'m at Mundo Maipu (Av. Colon 4065, Cordoba) http:\/\/t.co\/GnSGyXEj","description":"","link":"http:\/\/twitter.com\/FelipeSeia\/statuses\/154524981345267712","timestamp":1325676601,"image":null,"embed":null,"language":"en","user":"FelipeSeia","user_image":"http:\/\/a0.twimg.com\/profile_images\/1237939387\/IMG00134_normal.jpg","user_link":"http:\/\/twitter.com\/FelipeSeia","user_id":119023071,"geo":null,"source":"twitter","favicon":"http:\/\/twitter.com\/favicon.ico","type":"microblogs","domain":"twitter.com","id":"5826854578362027222"}]}';
        // Body is a JSON encoded string of results

        //echo "<pre>";var_dump($body);echo "</pre>";
        $results_json = json_decode($body);
        //echo "<pre>";var_dump($results_json);echo "</pre>";

		return $results_json;

/*        // Return an array of Items
        $results = array();
        foreach ($results_json->items as $object){
            $network = $em->getRepository('Entities\Network')->findOneBy(array('name' => $object->source));

            $item = new Entities\Item();
            $item->setNetwork($network);
            
        }*/

    }

    public function remaining_api_calls(){
        return 10;
    }

}
