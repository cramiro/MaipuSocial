<?php

/*

description -> title
title -> verb
domain -> 'plus.google.com'
embed -> object.content
favicon -> 'https://ssl.gstatic.com/s2/oz/images/faviconr2.ico'
geo -> geocode
image -> object.attachments[].image.url (si hay una imagen en la lista 'attachments')
language -> NULL
link -> url
timestamp -> published
type -> object.attachments[].objectType (si hay foto, video o articulo); sino microblogs
user -> actor.displayName
user_id -> actor.id
user_image -> actor.image.url
user_link -> actor.url

*/

class GooglePlus implements iEngine {

    private $url = 'https://www.googleapis.com/plus/v1/activities';
    private $api_key = 'AIzaSyB6ZL_zobudZKbT0d7fLWLkW-VQItBZVFg';
    private $fields = 'items(title,verb,object(content,attachments/url,attachments/image/url,attachments/objectType),url,published,actor(displayName,id,image/url,url))';
    private $network = 'Google+';
    private $domain = 'plus.google.com';
    private $favicon = 'https://ssl.gstatic.com/s2/oz/images/faviconr2.ico';

    public function search($em, $search, $networks){
        // Make request to Socialmention API service
        require_once('HTTP/Request2.php');
        $r = new HTTP_Request2($this->url, HTTP_Request2::METHOD_GET, array('ssl_verify_peer' => false));

        $url = $r->getUrl();
//        echo "***:"; var_dump($url);

        $keywords_qstr = str_replace(' ', '+', $search->getKeywords());
        $keywords_qstr .= ($search->getExcludeWords() != '')? '-'.str_replace(' ', '-', $search->getExcludewords()) : '';
        $url->setQueryVariables(array(
            'query' => $keywords_qstr,
            'key' => $this->api_key,
            'fields' => $this->fields,
        ));

        try {
            $response = $r->send();
//echo "<pre>"; var_dump($response); echo "</pre>";
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
            $item->setNetwork($this->network);
            $item->setSearch($search);
            $item->setDescription($item_raw->title);
            $item->setDomain($this->domain);
            $item->setEmbed($item_raw->object->content);
            $item->setFavicon($this->favicon);
            //$item->setLanguage(null);
            $item->setLink($item_raw->url);
            $item->setTitle($item_raw->verb);

            //$item->setImage(null);
            $item->setType('microblogs');
//echo "<pre>"; var_dump($item_raw);echo "<pre>";
            if(isset($item_raw->object->attachments)){
                foreach($item_raw->object->attachments as $attachment){
                    if($attachment->objectType == 'photo' or $attachment->objectType == 'video'){
                        $item->setImage($attachment->image->url);
                        $item->setType($attachment->objectType);
                        break;
                    }
                }
            }
            
            $item->setUser($item_raw->actor->displayName);
            $item->setUserId($item_raw->actor->id);
            $item->setUserImage($item_raw->actor->image->url);
            $item->setUserLink($item_raw->actor->url);

            $item->setHash(sha1($item_raw->url));

/*            $t = new DateTime();
            $t->setTimestamp($item_raw->published);*/
            $t = new DateTime($item_raw->published);
            $item->setTimestamp($t);

            $results[] = $item;
            //echo "<pre>"; Doctrine\Common\Util\Debug::dump($item); echo "</pre>";
        }
		return $results;
    }

    public function calls_per_hour(){
        return 40;
    }

}
