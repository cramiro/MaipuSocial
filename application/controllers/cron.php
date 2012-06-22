<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

    private $hot_search_freq = 5; // Frequency of hot search updates (in minutes)
    private $saved_search_freq = 15; // Frequency of saved search updates (in minutes)

    private $hot_search_exp = 24; // Expiration time for hot searches (in hours)

    private $items_exp = 30; // Expiration time for items (in days)

    function _expire_temp_searches()
    {
        /* Find in the database every temp search older than 1 day */
        $query = $this->doctrine->em->createQuery('SELECT s FROM Entities\Search s WHERE s.is_temp = 1 AND s.added <= ?1');
        $when = new DateTime('now');
        $interval = new DateInterval('P0Y0M0DT'.$this->hot_search_exp.'H0M0S');
        $interval->invert = 1;
        $when->add($interval);
        $query->setParameter(1, $when->format('Y-m-d H:i:s'));
        $searches = $query->getResult();
        $this->load->library('search');
        foreach ($searches as $search){
            echo "Deleting temp search: ".$search->getKeywords(). "...<br />";
            $this->search->delete_search($this->doctrine->em, $search);
            echo "Done.<br />";
        }
        $this->doctrine->em->flush();
//        echo "<pre>"; var_dump($query); echo "</pre>";
//        echo "<pre>"; var_dump($interval); echo "</pre>";
//        echo $when->format('Y-m-d H:i:s');

    }

    function _update_searches($is_temp, $search_freq)
    {
        /* Find in the database every search that hasn't been updated
           in the last 5 minutes and check for new results.
        */
        $query = $this->doctrine->em->createQuery('SELECT s FROM Entities\Search s WHERE s.updated <= ?1 AND s.is_temp = ?2');
        $when = new DateTime('now');
        $interval = new DateInterval('P0Y0M0DT0H'.$search_freq.'M0S');
        $interval->invert = 1;
        $when->add($interval);
        $query->setParameter(1, $when->format('Y-m-d H:i:s'));
        $query->setParameter(2, $is_temp);
        $searches = $query->getResult();
        foreach ($searches as $search){
            echo "Updating ".$search->getKeywords(). "...<br>";
            $this->load->library('search');
            $this->search->perform_search($this->doctrine->em, $search);
            echo "Done.";
        }
//        echo "<pre>"; var_dump($query); echo "</pre>";
//        echo "<pre>"; var_dump($interval); echo "</pre>";
//        echo $when->format('Y-m-d H:i:s');

    }

    function _expire_items(){
        /* Find in the database every temp search older than 1 month */
        $query = $this->doctrine->em->createQuery('SELECT i FROM Entities\Item i WHERE i.timestamp <= ?1');
        $when = new DateTime('now');
        $interval = new DateInterval('P0Y0M'.$this->items_exp.'DT0H0M0S');
        $interval->invert = 1;
        $when->add($interval);
        $query->setParameter(1, $when->format('Y-m-d H:i:s'));
        $items = $query->getResult();
        foreach ($items as $item){
            $item->setDeleted(true);
        }
        $this->doctrine->em->flush();
    }

    public function index(){
        /* Primero borro las busquedas temporales que no hayan sido guardadas 
         * en 1 dia */
        $this->_expire_temp_searches();

        /* Borro los items que tengan mas de $this->items_exp dias */
        $this->_expire_items();

        /* Luego actualizo todas las busquedas que necesiten ser actualizadas */
        $this->_update_searches(true, $this->hot_search_freq);
        $this->_update_searches(false, $this->saved_search_freq);
    
    }

}
