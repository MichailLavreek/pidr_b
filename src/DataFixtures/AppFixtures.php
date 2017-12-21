<?php

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use App\Entity\StructureType;
use App\Entity\User;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
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
            ['contacts', $typePage, 'Контакти'],
        ];

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
}