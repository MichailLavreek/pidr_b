<?php

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\AttributeLanguage;
use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use App\Entity\Product;
use App\Entity\ProductAttributeValue;
use App\Entity\ProductLanguage;
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

    /**
     * @var ObjectManager $manager
     */
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

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

        $this->loadAttributes();

        $this->loadProducts();
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
        $langRu = $manager->getRepository(Language::class)->find('ru');
        $langEn = $manager->getRepository(Language::class)->find('en');
        $langs = [$langUa, $langRu, $langEn];

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

            foreach ($langs as $lang) {
                $structureLang = new StructureLanguage();
                $structureLang
                    ->setName($item[2])
                    ->setLanguage($lang)
                    ->setStructure($structure);

                $manager->persist($structureLang);
            }


            if (!empty($item[3])) {
                foreach ($item[3] as $childItem) {
                    $child = new Structure();
                    $child
                        ->setAlias($childItem[0])
                        ->setIsActive(1)
                        ->setType($childItem[1])
                        ->setParent($structure);

                    foreach ($langs as $lang) {
                        $childLang = new StructureLanguage();
                        $childLang
                            ->setName($childItem[2])
                            ->setLanguage($lang)
                            ->setStructure($child);

                        $manager->persist($childLang);
                    }

                    $manager->persist($child);
                }
            }

            $manager->persist($structure);

            $manager->flush();
        }
    }

    private function loadContent(ObjectManager $manager)
    {
        $contents = [
            ['about-us', 'Про нас'],
            ['catalog', 'Каталог товарів']
        ];

        $structureRepository = $manager->getRepository(Structure::class);
        $contentRepository = $manager->getRepository(Content::class);
        $languageRepository = $manager->getRepository(Language::class);

        $langUa = $manager->getRepository(Language::class)->find('ua');
        $langRu = $manager->getRepository(Language::class)->find('ru');
        $langEn = $manager->getRepository(Language::class)->find('en');
        $langs = [$langUa, $langRu, $langEn];

        foreach ($contents as $item) {
            $structure = $structureRepository->findBy(['alias'=>$item[0]])[0];
            $content = new Content();
            $content->setStructure($structure);
            $content->setIsActive(1);

            foreach ($langs as $lang) {
                $contentLang = new ContentLanguage();
                $contentLang->setLanguage($lang);
                $contentLang->setName($item[1]);
                $body = file_get_contents(__DIR__ . '/data/' . $item[0]);
                $contentLang->setBody($body);
                $contentLang->setContent($content);
                $manager->persist($contentLang);
            }

            $manager->persist($content);
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

    public function loadAttributes()
    {
        $attributes = [
            ['class', ['Клас навантаження', 'Класс сопротивления', 'Class']],
            ['width', ['Товщина, мм', 'Толщина, мм', 'Width, mm']],
            ['chamfer', ['Фаска', 'Фаска', 'Chamfer']],
            ['manufacturer', ['Виробник', 'Производитель', 'Manufacturer']],
        ];

        $langRepository = $this->manager->getRepository(Language::class);
        $langUa = $langRepository->find('ua');
        $langRu = $langRepository->find('ru');
        $langEn = $langRepository->find('en');

        foreach ($attributes as $attribute) {
            $newAttribute = new Attribute();
            $newAttributeLangUa = new AttributeLanguage();
            $newAttributeLangRu = new AttributeLanguage();
            $newAttributeLangEn = new AttributeLanguage();

            $newAttributeLangUa->setLanguage($langUa);
            $newAttributeLangUa->setName($attribute[1][0]);
            $newAttributeLangUa->setAttribute($newAttribute);

            $newAttributeLangRu->setLanguage($langRu);
            $newAttributeLangRu->setName($attribute[1][1]);
            $newAttributeLangRu->setAttribute($newAttribute);

            $newAttributeLangEn->setLanguage($langEn);
            $newAttributeLangEn->setName($attribute[1][2]);
            $newAttributeLangEn->setAttribute($newAttribute);

            $newAttribute->setLang($newAttributeLangUa);
            $newAttribute->setLang($newAttributeLangRu);
            $newAttribute->setLang($newAttributeLangEn);

            $newAttribute->setCode($attribute[0]);

            $this->manager->persist($newAttribute);
            $this->manager->persist($newAttributeLangUa);
            $this->manager->persist($newAttributeLangRu);
            $this->manager->persist($newAttributeLangEn);
        }

        $this->manager->flush();
    }

    public function loadProducts()
    {
        $laminateStructure = $this->manager->getRepository(Structure::class)->findOneBy(['alias'=>'laminate']);
        $langUA = $this->manager->getRepository(Language::class)->find('ua');
        $langRU = $this->manager->getRepository(Language::class)->find('ru');
        $langEN = $this->manager->getRepository(Language::class)->find('en');

        $class = $this->manager->getRepository(Attribute::class)->findOneBy(['code'=>'class']);
        $width = $this->manager->getRepository(Attribute::class)->findOneBy(['code'=>'width']);
        $chamfer = $this->manager->getRepository(Attribute::class)->findOneBy(['code'=>'chamfer']);
        $manufacturer = $this->manager->getRepository(Attribute::class)->findOneBy(['code'=>'manufacturer']);

        $attributes = [
            [$class, [31, 32, 33]],
            [$width, [7, 8, 10, 12]],
            [$chamfer, ['none', '2V', '4V']],
            [$manufacturer, ['Україна', 'Польша', 'Бельгія', 'Білорусія', 'Німеччина', 'Австрія']],
        ];

        for ($count = 1; $count < 100; $count++) {
            $product = new Product();

            $productLangUa = new ProductLanguage();
            $productLangUa->setLanguage($langUA);
            $productLangUa->setName('Ламiнат - ' . $count);
            $productLangUa->setParent($product);
            $product->setLang($productLangUa);

            $productLangRu = new ProductLanguage();
            $productLangRu->setLanguage($langRU);
            $productLangRu->setName('Ламинат - ' . $count);
            $productLangRu->setParent($product);

            $productLangEn = new ProductLanguage();
            $productLangEn->setLanguage($langEN);
            $productLangEn->setName('Laminate - ' . $count);
            $productLangEn->setParent($product);

            $product->setAlias('laminate-' . $count);
            $product->setImage('product-list-item.jpg');
            $product->setCode('art-' . $count);
            $product->setIsActive(true);
            $product->setPrice(rand(100, 500));
            $product->setStructure($laminateStructure);

            $this->manager->persist($product);
            $this->manager->persist($productLangUa);
            $this->manager->persist($productLangRu);
            $this->manager->persist($productLangEn);

            foreach ($attributes as $attribute) {
                $attributeValue = new ProductAttributeValue();
                $attributeValue->setAttribute($attribute[0]);
                $attributeValue->setProduct($product);
                $attributeValue->setValue($attribute[1][array_rand($attribute[1])]);
                $this->manager->persist($attributeValue);
            }
        }

        $this->manager->flush();
    }
}