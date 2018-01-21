<?php

namespace App\EventListener;


use App\Entity\Attribute;
use App\Entity\AttributeLanguage;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AttributeEntityListener
{

    public $logger;
    public function __construct()
    {
    }

    public function preUpdate(Attribute $attribute, LifecycleEventArgs $event)
    {

    }

    public function prePersist(Attribute $attribute, LifecycleEventArgs $event)
    {
//        var_dump('p');die;
    }
}
