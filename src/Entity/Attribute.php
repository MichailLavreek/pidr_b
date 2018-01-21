<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\EntityListeners({"App\EventListener\AttributeEntityListener"})
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
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="AttributeLanguage", mappedBy="attribute", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="attribute_id")
     */
    private $lang;

    /**
     * @ORM\OneToMany(targetEntity="ProductAttributeValue", mappedBy="attribute", cascade={"all"})
     */
    private $attributeValues;

    public function __construct()
    {
        $this->lang = new ArrayCollection();
    }

    /**
     * @Assert\IsTrue(message="Lang items is not unique")
     */
    public function isLangUnique()
    {
        $isOk = true;
        $langs = [];

        /**
         * @var AttributeLanguage $lang
         */
        foreach ($this->getLang() as $lang) {
            if (!empty($langs[$lang->getAttribute()->getId()]) && $langs[$lang->getAttribute()->getId()] === $lang->getLanguage()->getId()) {
                $isOk = false;
                break;
            }
            $langs[$lang->getAttribute()->getId()] = $lang->getLanguage()->getId();
        }

        return $isOk;
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
     * @param mixed $lang
     */
    public function addLang($lang)
    {
        $this->lang[] = $lang;
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
