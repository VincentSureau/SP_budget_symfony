<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Operation;
use App\Entity\PaymentMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        /**
         * Category fixtures
         */

        $categoriesData = [
            "Salaire",
            "Transport",
            "Alimentation",
            "Loisirs",
            "Culture",
            "Logement",
            "Santé",
            "Habillement",
            "Impôts",
            "Autres"
        ];

        $categories = [];

        foreach ($categoriesData as $categoryData)
        {
            $category = new Category();
            $category
                ->setName($categoryData)
                ->setColor($faker->hexcolor)    
            ;

            $manager->persist($category);

            $categories[] = $category;
        }

        /**
         * PaymentMethod fixtures
         */

        $paymentMethodsData = [
            "CB",
            "Chèque",
            "Espèce",
            "Virement"
        ];

        $paymentMethods = [];

        foreach ($paymentMethodsData as $paymentMethodData)
        {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setName($paymentMethodData);

            $manager->persist($paymentMethod);
            $paymentMethods[] = $paymentMethod;
        }

        /**
         * User fixtures
         */

        $admin = new User();
        $admin
            ->setEmail("admin@admin.com")
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(
                $this->passwordEncoder->encodePassword(
                    $admin,
                    'password'
                )
            )
        ;
        
        $manager->persist($admin);

        for($userIndex = 0; $userIndex < 30; $userIndex++){
            $user = new User();
            $user
                ->setEmail($faker->unique()->safeEmail)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'password'
                    )
                )
            ;
            $manager->persist($user);
        }

        /**
         * Operations
         */

        for ($i=0; $i < 50; $i++) { 
            $operation = new Operation;
            $operation
                ->setAmount($faker->randomFloat(2, 10, 1200))
                ->setType($faker->randomElement(["expense", "income"]))
                ->setCategory($faker->randomElement($categories))
                ->setPaymentMethod($faker->randomElement($paymentMethods))
                ->setDate($faker->dateTimeThisYear())
                ->setUser($admin)
            ;
            $manager->persist($operation);
        }

        $manager->flush();
    }
}
