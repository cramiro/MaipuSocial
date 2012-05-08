<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

    function _expire_temp_searches()
    {
        /* Find in the database every temp search older than 1 day */
        $query = $this->doctrine->em->createQuery('SELECT s FROM Entities\Search s WHERE s.is_temp = 1 AND s.added <= ?1');
        $when = new DateTime('now');
        $interval = new DateInterval('P0Y0M1DT0H0M0S');
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
//        echo "<pre>"; var_dump($query); echo "</pre>";
//        echo "<pre>"; var_dump($interval); echo "</pre>";
//        echo $when->format('Y-m-d H:i:s');

    }

    function _update_searches()
    {
        /* Find in the database every search that hasn't been updated
           in the last 5 minutes and check for new results.
        */
        $query = $this->doctrine->em->createQuery('SELECT s FROM Entities\Search s WHERE s.updated <= ?1');
        $when = new DateTime('now');
        $interval = new DateInterval('P0Y0M0DT0H1M0S');
        $interval->invert = 1;
        $when->add($interval);
        $query->setParameter(1, $when->format('Y-m-d H:i:s'));
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

    public function index(){
        /* Primero borro las busquedas temporales que no hayan sido guardadas 
         * en 1 dia */
        $this->_expire_temp_searches();

        /* Luego actualizo todas las busquedas que necesiten ser actualizadas */
        $this->_update_searches();
    
    }

}
