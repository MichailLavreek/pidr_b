<?php
namespace App\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LangType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'Standard Lang' => 'standard',
                'Expedited Lang' => 'expedited',
                'Priority Lang' => 'priority',
            ),
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}