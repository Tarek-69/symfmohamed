<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Animals;
use App\Entity\Countries;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnimalsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $countries = $manager->getRepository(Countries::class)->findAll();
        dump($countries);

        for ($i = 0; $i < 1000; $i++) {
            $animal = new Animals();
            $animal
                ->setName($faker->name())
                ->setSize($faker->numberBetween(10, 200))
                ->setLifetime($faker->numberBetween(1, 200))
                ->setPhone($faker->phoneNumber())
                ->setMartialArt($faker->name())
                ->setCountry($faker->randomElement($countries));
            $manager->persist($animal);
        }
        $manager->flush();
    }
    
    public function getDependencies(): array
    {
        return [CountryFixtures::class];
    }
        
}

