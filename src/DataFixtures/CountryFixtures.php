<?php

namespace App\DataFixtures;

use App\Entity\Countries;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $country = new Countries();
            $country
                ->setName($faker->name())
                ->setIso($faker->countryCode());
            $manager->persist($country);
        }
        $manager->flush();
    }
}
