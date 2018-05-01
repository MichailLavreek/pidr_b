<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 */
class Content extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure")
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id", nullable=false)
     */
    private $structure;

    /**
     * @ORM\OneToOne(targetEntity="Meta", cascade={"all"})
     * @ORM\JoinColumn(name="meta_id", referencedColumnName="id", nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="ContentLanguage", mappedBy="content", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="content_id")
     */
    protected $lang;

    public function __construct()
    {
        $this->lang = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param mixed $structure
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
