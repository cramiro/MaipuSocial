<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Search
 *
 * @Table(name="searches")
 * @Entity
 */
class Search
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @var boolean $is_temp
     *
     * @Column(name="is_temp", type="boolean")
     */
    private $is_temp;

    /**
     * @var string $keywords
     *
     * @Column(name="keywords", type="string", length=80)
     */
    private $keywords;

    /**
     * @var string $exclude_words
     *
     * @Column(name="exclude_words", type="string", length=80)
     */
    private $exclude_words;

    /**
     * @var datetime $updated
     *
     * @Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @OneToMany(targetEntity="Entities\Item", mappedBy="search")
     */
    private $items;

    /**
     * @ManyToMany(targetEntity="Entities\Network")
     */
    private $networks;

    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->networks = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getIsTemp()
    {
        return $this->is_temp;
    }
    
    public function setIsTemp($is_temp)
    {
        $this->is_temp = $is_temp;
    }
    
    public function getKeywords()
    {
        return $this->keywords;
    }
    
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
    
    public function getExcludeWords()
    {
        return $this->exclude_words;
    }
    
    public function setExcludeWords($exclude_words)
    {
        $this->exclude_words = $exclude_words;
    }
    
    public function getUpdated()
    {
        return $this->updated;
    }
    
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }
    
    public function getNetworks()
    {
        return $this->networks;
    }
    
    public function addNetwork($network)
    {
        $this->networks[] = $network;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function addToSearchResults($item)
    {
        $this->items[] = $item;
    }
}
