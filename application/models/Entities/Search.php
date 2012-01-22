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
     * @var boolean $name
     *
     * @Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var boolean $description
     *
     * @Column(name="description", type="string", length=80)
     */
    private $description;

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
     * @OneToMany(targetEntity="Entities\Item", mappedBy="search", cascade={"persist", "remove"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
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
    
    public function getResults()
    {
        return $this->items;
    }
    
    public function addToSearchResults($item)
    {
        $this->items[] = $item;
    }

    /**
     * Add items
     *
     * @param Entities\Item $items
     */
    public function addItem(\Entities\Item $items)
    {
        $this->items[] = $items;
    }

    /**
     * Get is_temp
     *
     * @return boolean 
     */
    public function getIsTemp()
    {
        return $this->is_temp;
    }

    public function save_results($items_arr){
        //$this->load->library('doctrine');
        foreach ($items_arr as $item){
            $item->setSeen(false);
            $item->setDeleted(false);
            //echo "<pre>"; var_dump($this->doctrine->em); echo "</pre>";
            $this->addItem($item);
            //$this->doctrine->em->persist($item);
        }

        //$this->doctrine->em->persist($this);
        //$this->doctrine->em->flush();
    }

}
