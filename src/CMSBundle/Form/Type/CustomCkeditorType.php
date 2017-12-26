<?php
namespace App\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CustomCkeditorType extends AbstractType
{


    public function getParent()
    {
        return TextareaType::class;
    }
}