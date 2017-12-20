<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentLanguageRepository")
 */
class ContentLanguage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $contentId;

    // add your own fields
}
