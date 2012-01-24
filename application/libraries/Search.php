<?php

class Search {

    public function perform_search($em, $search){
        // Populate dict plugin_networks[engine][network] so in the
        // first level we got an engine name, and in the second level
        // every network that engine has to search.
        $plugin_networks = array();
        foreach($em->getRepository('Entities\Network')->findBy(array()) as $network){
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

        // We now know what plugin has to search which network


        require_once('application/libraries/iEngine.php');
        $items_arr = array();
        foreach ($plugin_networks as $engine_name => $value){
            require_once('application/libraries/'.$engine_name.'.php');

            $engine = new $engine_name();
            // Call plugin
            $items_arr = array_merge($items_arr, $engine->search($em, $search, $plugin_networks[$engine_name]));
        }

        // Save each new item into the database if new
        $new_results = array();
        foreach ($items_arr as $item){
            if (!$em->getRepository('Entities\Item')->findOneBy(array("hash" => sha1($item->getLink())))){
                $em->persist($item);
                $new_results[] = $item;
            }else{
                $item->unsetSearch($search);
            }
        }

        // Save results in database (the search model does this)
        $search->save_results($new_results);
        $search->setUpdated(new DateTime('now'));
        $em->persist($search);
        $em->flush();

        // Return status code, 0 means it's OK
        return 0;

    }

}
