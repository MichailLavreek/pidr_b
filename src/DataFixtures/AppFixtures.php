<?php

namespace App\DataFixtures;

use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use App\Entity\StructureType;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Variable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $structures;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('Admin');
        $role->setSystemName('ROLE_ADMIN');
        $manager->persist($role);

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('soft12@sidstudio.com.ua');

        $user->setRole($role);

        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        $this->loadLanguages($manager);
        $this->loadStructure($manager);
        $this->loadContent($manager);
        $this->loadVariables($manager);
    }

    private function loadLanguages(ObjectManager $manager)
    {
        $languages = [
            ['ua', 'українська'],
            ['ru', 'русский'],
            ['en', 'english'],
        ];

        foreach ($languages as $item) {
            $language = new Language();
            $language->setIso2($item[0]);
            $language->setName($item[1]);
            $manager->persist($language);
        }

        $manager->flush();
    }

    private function loadStructure(ObjectManager $manager)
    {
        $typePage = new StructureType();
        $typePage->setName('Page');
        $manager->persist($typePage);

        $typeCategory = new StructureType();
        $typeCategory->setName('Category');
        $manager->persist($typeCategory);

        $typeContacts = new StructureType();
        $typeContacts->setName('Contacts');
        $manager->persist($typeContacts);

        $langUa = $manager->getRepository(Language::class)->find('ua');

        $structures = [
            ['about-us', $typePage, 'Про нас'],
            ['catalog', $typeCategory, 'Каталог товарів', [
                    ['laminate', $typeCategory, 'ламінат'],
                    ['furniture', $typeCategory, 'меблі'],
                    ['siding', $typeCategory, 'сайдінг'],
                ]
            ],
            ['montage', $typePage, 'Графік монтажу'],
            ['actions', $typePage, 'Акції'],
            ['portfolio', $typePage, 'Портфоліо'],
            ['contacts', $typeContacts, 'Контакти'],
        ];
        $this->structures = $structures;

        foreach ($structures as $item) {
            $structure = new Structure();
            $structure
                ->setAlias($item[0])
                ->setIsActive(1)
                ->setType($item[1]);

            $structureLang = new StructureLanguage();
            $structureLang
                ->setName($item[2])
                ->setLanguage($langUa)
                ->setStructure($structure);

            if (!empty($item[3])) {
                foreach ($item[3] as $childItem) {
                    $child = new Structure();
                    $child
                        ->setAlias($childItem[0])
                        ->setIsActive(1)
                        ->setType($childItem[1])
                        ->setParent($structure);

                    $childLang = new StructureLanguage();
                    $childLang
                        ->setName($childItem[2])
                        ->setLanguage($langUa)
                        ->setStructure($child);

                    $manager->persist($child);
                    $manager->persist($childLang);
                }
            }

            $manager->persist($structure);
            $manager->persist($structureLang);

            $manager->flush();
        }
    }

    private function loadContent(ObjectManager $manager)
    {
        $contents = [
            ['about-us', 'Про нас']
        ];

        $structureRepository = $manager->getRepository(Structure::class);
        $contentRepository = $manager->getRepository(Content::class);
        $languageRepository = $manager->getRepository(Language::class);
        $languageUa = $languageRepository->find('ua');

        foreach ($contents as $item) {
            $structure = $structureRepository->findBy(['alias'=>$item[0]])[0];
            $content = new Content();
            $content->setStructure($structure);
            $content->setIsActive(1);
            $contentLang = new ContentLanguage();
            $contentLang->setLanguage($languageUa);
            $contentLang->setName($item[1]);
            $body = file_get_contents(__DIR__ . '/data/' . $item[0]);
            $contentLang->setBody($body);
            $contentLang->setContent($content);

            $manager->persist($content);
            $manager->persist($contentLang);
        }

        $manager->flush();
    }

    public function loadVariables(ObjectManager $manager)
    {
        $variables = [
            ['phone_1', '(097)466-8228'],
            ['phone_2', '(066)852-1355'],
            ['phone_3', '(093)909-3888'],
            ['email', 'info@pidrahui.com.ua'],
        ];

        foreach ($variables as $item) {
            $variable = new Variable();
            $variable->setName($item[0]);
            $variable->setValue($item[1]);

            $manager->persist($variable);
        }

        $manager->flush();
    }
}