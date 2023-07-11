<?php

namespace App\DataFixtures;

use App\Entity2\Todolist;
use Doctrine\Bundle\FixturesBundle\{Fixture, FixtureGroupInterface};
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class TodolistFixtures extends Fixture implements FixtureGroupInterface
{
    public const NB_FIXTURE = 5;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $todolist = (new Todolist)
                ->setTitle($this->faker->unique()->title())
                ->setDescription($this->faker->text())
            ;

            $manager->persist($todolist);
            $this->setReference("todolist_$i", $todolist);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['second'];
    }
}
