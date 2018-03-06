<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetaRepository")
 */
class Meta extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="MetaLanguage", mappedBy="meta", cascade={"all"})
     */
    protected $lang;

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
    public function setId($id): void
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
    public function setLang($lang): void
    {
        $this->lang = $lang;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
