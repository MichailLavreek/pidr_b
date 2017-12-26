<?php

namespace App\CMSBundle\Form;

use App\CMSBundle\Form\Type\CustomCkeditorType;
use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\Configurator\IvoryCKEditorTypeConfigurator;
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

class ContentLangEmbeddedForm extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentLanguage::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languages = $this->em->getRepository(Language::class)->findAll();
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $content = $this->em->getRepository(Content::class)->find($_GET['id']);
            if (!empty($content)) {
                $builder
                    ->add('content', HiddenType::class, array(
                        'data' => $content,
                    ));
            }
        }

        $builder
            ->add('language', ChoiceType::class, [
                'choices' => $languages,
                'choice_label' => function($language, $key, $index) {
                    /** @var Language $language */
                    return $language->getName();
                }
            ])
            ->add('name', TextType::class, [])
//            ->add('body', CustomCkeditorType::class, [])
    ;
    }

    public function onPostSetData(FormEvent $event)
    {

    }
}