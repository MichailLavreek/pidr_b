<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     */
    private $alias;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure")
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id", nullable=false)
     */
    private $structure;

    /**
     * @ORM\ManyToMany(targetEntity="Attribute", inversedBy="products", cascade={"persist"})
     * @ORM\JoinTable(name="products_attributes")
     */
    private $attributes;

    /**
     * @ORM\OneToMany(targetEntity="ProductAttributeValue", mappedBy="product")
     */
    private $attributesValues;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="ProductLanguage", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="structure_id")
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $manufacturer;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->attributesValues = new ArrayCollection();
    }

    public function addAttribute(Attribute $attribute)
    {
        $attribute->addProduct($this);
        $this->attributes[] = $attribute;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function addAttributesValue(ProductAttributeValue $attribute)
    {
        $attribute->setProduct($this);
        $this->attributesValues[] = $attribute;
    }

    /**
     * @return mixed
     */
    public function getAttributesValues()
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
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
    public function getisActive()
    {
        return $this->isActive;
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
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
