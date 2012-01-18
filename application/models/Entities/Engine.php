<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Engine
 *
 * @Table(name="engines")
 * @Entity
 */
class Engine
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
     * @var string $content
     *
     * @Column(name="content", type="string", length=10)
     */
    private $content;

    /**
     * @var Entities\Network
     *
     * @ManyToMany(targetEntity="Entities\Network")
     * @JoinTable(name="engines_networks",
     *   joinColumns={
     *     @JoinColumn(name="engine_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="network_id", referencedColumnName="id")
     *   }
     * )
     */
    private $networks;

    /**
     * @var Entities\Item
     *
     * @OneToMany(targetEntity="Entities\Item", mappedBy="search")
     */
    private $items;

    public function __construct()
    {
        $this->networks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    

}
