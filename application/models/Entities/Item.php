<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Item
 *
 * @Table(name="items")
 * @Entity
 */
class Item
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
     * @var string $description
     *
     * @Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var string $domain
     *
     * @Column(name="domain", type="string", length=50)
     */
    private $domain;

    /**
     * @var string $embed
     *
     * @Column(name="embed", type="string", length=400, nullable=true)
     */
    private $embed;

    /**
     * @var string $favicon
     *
     * @Column(name="favicon", type="string", length=40, nullable=true)
     */
    private $favicon;

    /**
     * @var string $geo
     *
     * @Column(name="geo", type="string", length=12, nullable=true)
     */
    private $geo;

    /**
     * @var string $image
     *
     * @Column(name="image", type="string", length=100, nullable=true)
     */
    private $image;

    /**
     * @var string $language
     *
     * @Column(name="language", type="string", length=2, nullable=true)
     */
    private $language;

    /**
     * @var string $link
     *
     * @Column(name="link", type="string", length=120, nullable=true)
     */
    private $link;

    /**
     * @var string $hash
     *
     * @Column(name="hash", type="string", length=40, unique=true)
     */
    private $hash;

    /**
     * @var string $timestamp
     *
     * @Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var string $title
     *
     * @Column(name="title", type="string", length=160)
     */
    private $title;

    /**
     * @var string $type
     *
     * @Column(name="type", type="string", length=15)
     */
    private $type;

    /**
     * @var string $user
     *
     * @Column(name="user", type="string", length=20, nullable=true)
     */
    private $user;

    /**
     * @var string $user_id
     *
     * @Column(name="user_id", type="string", length=20, nullable=true)
     */
    private $user_id;

    /**
     * @var string $user_image
     *
     * @Column(name="user_image", type="string", length=80, nullable=true)
     */
    private $user_image;

    /**
     * @var string $user_link
     *
     * @Column(name="user_link", type="string", length=50, nullable=true)
     */
    private $user_link;

    /**
     * @var boolean $seen
     *
     * @Column(name="seen", type="boolean")
     */
    private $seen;

    /**
     * @var boolean $deleted
     *
     * @Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var string $network
     *
     * @Column(name="network", type="string", length=20)
     */
    private $network;

    /**
     * @ManyToOne(targetEntity="Entities\Search", inversedBy="items")
     */
    private $search;


    public function getId()
    {
        return $this->id;
    }
    public function getNetwork()
    {
        return $this->network;
    }

    public function setNetwork($network)
    {
        $this->network = $network;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $search->addToSearchResults($this);
        $this->search = $search;
    }

    public function unsetSearch($search)
    {
        $search->removeFromSearchResults($this);
        $this->search = null;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getEmbed()
    {
        return $this->embed;
    }

    public function setEmbed($embed)
    {
        $this->embed = $embed;
    }

    public function getFavicon()
    {
        return $this->favicon;
    }

    public function setFavicon($favicon)
    {
        $this->favicon = $favicon;
    }

    public function getGeo()
    {
        return $this->geo;
    }

    public function setGeo($geo)
    {
        $this->geo = $geo;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserImage()
    {
        return $this->user_image;
    }

    public function setUserImage($user_image)
    {
        $this->user_image = $user_image;
    }

    public function getUserLink()
    {
        return $this->user_link;
    }

    public function setUserLink($user_link)
    {
        $this->user_link = $user_link;
    }

    public function getSeen()
    {
        return $this->seen;
    }

    public function setSeen($seen)
    {
        $this->seen = $seen;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function __toString(){
        return urlencode($this->getLink());
    }

}
