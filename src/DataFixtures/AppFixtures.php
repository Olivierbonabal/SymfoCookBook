<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Contact;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var \Faker\Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];

        $admin = new User();
        $admin->setFullName('Administrateur')
              ->setPseudo(\null)
              ->setEmail('admin@cookbook.com')
              ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
              ->setPlainPassword('admin');
        $users[] = $admin;
        $manager->persist($admin);

        for ($u = 0; $u < 10; $u++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(\mt_rand(0, 1) === 1 ? $this->faker->firstName() : \null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(\mt_rand(0, 100))
                ->setUser($users[\mt_rand(0, count($users) - 1)]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        $recipes = [];
        for ($r = 0; $r < 25; $r++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                //random entre 0&1 si c 1 jlui donne 1 temps entre 1&1440 sinon c nul
                ->setTime(\mt_rand(0, 1) == 1 ? \mt_rand(1, 1441) : null)
                ->setNbPeople(\mt_rand(0, 1) == 1 ? \mt_rand(1, 50) : null)
                ->setDifficulty(\mt_rand(0, 1) == 1 ? \mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(\mt_rand(0, 1) == 1 ? \mt_rand(1, 1000) : null)
                ->setIsFavorite(\mt_rand(0, 1) == 1 ? true : false)
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[\mt_rand(0, count($users) - 1)]);

            for ($k = 0; $k < \mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[\mt_rand(0, count($ingredients) - 1)]);
            }

            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        //Marks(notes)
        foreach ($recipes as $recipe) {
            for ($i = 0; $i < mt_rand(0, 4); $i++) {
                $mark = new Mark();
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users) - 1)])
                    ->setRecipe($recipe);

                $manager->persist($mark);
            }
        }

        //Contact
        for ($i=0; $i < 5; $i++) { 
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                    ->setEmail($this->faker->email())
                    ->setSubject('Demande n°' . ($i + 1))
                    ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
