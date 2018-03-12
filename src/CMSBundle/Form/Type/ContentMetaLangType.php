<?php

namespace App\CMSBundle\Form\Type;

use App\CMSBundle\Form\Type\CustomCkeditorType;
use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use App\Entity\Meta;
use App\Entity\MetaLanguage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\Configurator\IvoryCKEditorTypeConfigurator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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

class ContentMetaLangType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MetaLanguage::class,
            'compound' => true,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) return;

        $content = $this->em->getRepository(Content::class)->find($_GET['id']);
        $meta = $content->getMeta();

        /** @var MetaLanguage $metaLang */
//        $metaLang = $options['data'];

//        var_dump( $options);
        $builder
            ->add('id', HiddenType::class, [])
            ->add('meta', EntityType::class, [
                'class' => Meta::class,
                'query_builder' => function (EntityRepository $er) use ($meta) {
                    return $er->createQueryBuilder('s')
                        ->where('s.id = :id')->setParameter('id', $meta->getId())
                        ;
                },
                'attr' => array('class' => 'forced-hidden-data-field')
            ])
            ->add('language', EntityType::class, ['class' => Language::class, 'attr' => ['class' => 'forced-disabled-data-field']])
            ->add('title', TextType::class, [])
            ->add('description', TextareaType::class, [])
            ->add('keywords', TextType::class, [])
        ;
    }

    public function onPostSetData(FormEvent $event)
    {

    }

    public function getParent()
    {
        return FormType::class;
    }
}