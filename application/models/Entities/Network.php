<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Network
 *
 * @Table(name="networks")
 * @Entity
 */
class Network
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string $type
     *
     * @Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * @var Entities\Engine
     *
     * @ManyToOne(targetEntity="Entities\Engine")
     */
    private $default_engine;

    /**
     * @var Entities\Item
     *
     * @OneToMany(targetEntity="Entities\Item", mappedBy="network")
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

    public function getDefaultEngine()
    {
        return $this->default_engine;
    }

    public function setDefaultEngine($engine)
    {
        $this->engine = $default_engine;
    }

    
}
