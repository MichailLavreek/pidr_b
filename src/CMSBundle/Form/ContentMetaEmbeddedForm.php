<?php

namespace App\CMSBundle\Form;

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

class ContentMetaEmbeddedForm extends AbstractType
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
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) return;

        $languages = $this->em->getRepository(Language::class)->findAll();
        $content = $this->em->getRepository(Content::class)->find($_GET['id']);
        $meta = $content->getMeta();

        if (empty($meta)) {
            return;
        }

        $mqb = $this->em->getRepository(Meta::class)->createQueryBuilder('m')->where('m.id = :id')->setParameter('id', $meta->getId());

        $builder
            ->add('language', ChoiceType::class, [
                'choices' => $languages,
                'choice_label' => function($language, $key, $index) {
                    /** @var Language $language */
                    return $language->getName();
                }
            ])
            ->add('title', TextType::class, [])
            ->add('description', TextareaType::class, [])
            ->add('keywords', TextType::class, [])
    ;
    }

    public function onPostSetData(FormEvent $event)
    {

    }
}