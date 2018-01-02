<?php

namespace App\CMSBundle\Form;

use App\CMSBundle\Form\Type\CustomCkeditorType;
use App\Entity\Attribute;
use App\Entity\AttributeLanguage;
use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use App\Entity\Product;
use App\Entity\ProductLanguage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\Configurator\IvoryCKEditorTypeConfigurator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Util\LegacyFormHelper;

class AttributeLangEmbeddedForm extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AttributeLanguage::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) return;

        $languages = $this->em->getRepository(Language::class)->findAll();

        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $attribute = $this->em->getRepository(Attribute::class)->find($_GET['id']);
            if (!empty($attribute)) {
                $builder
                    ->add('attribute', HiddenType::class, array(
                        'data' => $attribute,
                    ));
            }
        }

        $builder
            ->add('attribute', EntityType::class, [
                'class' => Attribute::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.id = :id')->setParameter('id', $_GET['id'])
                        ;
                },
                'attr' => array('class' => 'forced-hidden-data-field')
            ])
            ->add('language', ChoiceType::class, [
                'choices' => $languages,
                'choice_label' => function($language, $key, $index) {
                    /** @var Language $language */
                    return $language->getName();
                }
            ])
            ->add('name', TextType::class, [])
    ;
    }

    public function onPostSetData(FormEvent $event)
    {

    }
}