<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\{Fixture, FixtureGroupInterface};
use Faker\Factory as FakerFactory;

abstract class AbstractFixture extends Fixture implements FixtureGroupInterface
{
    public const NB_FIXTURE = 10;
    protected \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * @return string[]
     */
    static public function getGroups(): array
    {
        return ['default'];
    }
}
