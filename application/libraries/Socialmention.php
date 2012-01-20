<?php

class Socialmention implements iEngine {

    private $url = 'http://api2.socialmention.com/search';

    public function search($em, $search, $networks){
        // Make request to Socialmention API service
        require_once('HTTP/Request2.php');
        $r = new HTTP_Request2($this->url, HTTP_Request2::METHOD_GET);

        // Make network names lowercase, otherwise Socialmention complains
        foreach ($networks as &$network){
            $network = strtolower($network);
        }

        $url = $r->getUrl();
//        echo "***:"; var_dump($url);

        $keywords_qstr = str_replace(' ', '+', $search->getKeywords());
        $keywords_qstr .= ($search->getExcludeWords() != '')? '-'.str_replace(' ', '-', $search->getExcludewords()) : '';
        $url->setQueryVariables(array(
            'q' => $keywords_qstr,
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

//$body = '{"count":2,"items":[{"title":"Ay mundo detente pronto. http:\/\/t.co\/WpYyPIfJ","description":"","link":"http:\/\/twitter.com\/jpsanmartinr\/statuses\/154564541169143808","timestamp":1325686032,"image":null,"embed":null,"language":"es","user":"jpsanmartinr","user_image":"http:\/\/a1.twimg.com\/profile_images\/1370074918\/2_Ico_300_normal.jpg","user_link":"http:\/\/twitter.com\/jpsanmartinr","user_id":28816658,"geo":null,"source":"twitter","favicon":"http:\/\/twitter.com\/favicon.ico","type":"microblogs","domain":"twitter.com","id":"13204452089901836808"},{"title":"I\'m at Mundo Maipu (Av. Colon 4065, Cordoba) http:\/\/t.co\/GnSGyXEj","description":"","link":"http:\/\/twitter.com\/FelipeSeia\/statuses\/154524981345267712","timestamp":1325676601,"image":null,"embed":null,"language":"en","user":"FelipeSeia","user_image":"http:\/\/a0.twimg.com\/profile_images\/1237939387\/IMG00134_normal.jpg","user_link":"http:\/\/twitter.com\/FelipeSeia","user_id":119023071,"geo":null,"source":"twitter","favicon":"http:\/\/twitter.com\/favicon.ico","type":"microblogs","domain":"twitter.com","id":"5826854578362027222"}]}';
        // Body is a JSON encoded string of results

        //echo "<pre>";var_dump($body);echo "</pre>";
        $results_json = json_decode($body);
        //echo "RESULTS_JSON: <pre>";var_dump($results_json);echo "</pre>";

        // Create the Entities\Items array and return it
        $results = array();
        foreach ($results_json->items as $item_raw){
            $item = new Entities\Item;
            $item->setNetwork($item_raw->source);
            $item->setSearch($search);
            $item->setDescription($item_raw->description);
            $item->setDomain($item_raw->domain);
            $item->setEmbed($item_raw->embed);
            $item->setFavicon($item_raw->favicon);
            $item->setImage($item_raw->image);
            $item->setLanguage($item_raw->language);
            $item->setLink($item_raw->link);
            $item->setTimestamp(new Datetime("now"));
            $item->setTitle($item_raw->title);
            $item->setType($item_raw->type);
            $item->setUser($item_raw->user);
            $item->setUserId($item_raw->user_id);
            $item->setUserImage($item_raw->user_image);
            $item->setUserLink($item_raw->user_link);

            $results[] = $item;
            //echo "<pre>"; Doctrine\Common\Util\Debug::dump($item); echo "</pre>";
        }
		return $results;
    }

    public function remaining_api_calls(){
        return 10;
    }

}
