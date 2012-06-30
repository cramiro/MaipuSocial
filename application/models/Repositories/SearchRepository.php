<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities;

class SearchRepository extends EntityRepository
{
    public function getAllResults()
    {
        return $this->_em->createQuery('SELECT i
                                        FROM Entities\Item i
                                        WHERE i.deleted = 0 
                                            AND i.search in (SELECT s.id FROM Entities\Search s WHERE s.is_temp = 0)
                                        ORDER BY i.timestamp DESC')
                         ->getResult();
    }

    public function getSlicedResults($offset = 0, $limit = 15)
    {
        return $this->_em->createQuery('SELECT i 
                                        FROM Entities\Item i
                                        WHERE i.deleted = 0 
                                            AND i.search in (SELECT s.id FROM Entities\Search s WHERE s.is_temp = 0)
                                        ORDER BY i.timestamp DESC')
                         ->setMaxResults($limit)->setFirstResult($offset)->getResult();
    }
}

