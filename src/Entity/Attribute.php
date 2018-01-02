<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttributeRepository")
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="AttributeLanguage", mappedBy="attribute", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="attribute_id")
     */
    private $lang;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="attributes")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="AttributeValue", mappedBy="attribute", cascade={"persist"})
     */
    private $values;

    /**
     * @ORM\OneToMany(targetEntity="ProductAttributeValue", mappedBy="attribute")
     */
    private $attributeValues;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->values = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues($values)
    {
        $this->values = $values;
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
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function __toString()
    {
        if (!empty($this->getLang())) {
            /**
             * @var AttributeLanguage $lang
             */
            foreach ($this->getLang() as $lang) {
                if ( ((string) $lang->getLanguage()) === 'ua') {
                    return $lang->getName();
                }
            }
        }

        return $this->getCode();
    }
}
