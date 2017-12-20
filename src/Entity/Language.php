<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true, length=2, columnDefinition="CHAR(2) NOT NULL")
     */
    private $iso2;

    /**
     * @ORM\Column(type="text", length=30)
     */
    private $name;


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->iso2;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->iso2 = $id;
    }

    /**
     * @return mixed
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * @param mixed $iso2
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
    }

    public function __toString()
    {
        return $this->iso2;
    }
}
