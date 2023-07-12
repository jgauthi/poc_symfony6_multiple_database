<?php
namespace App\DataFixtures;

use App\Entity2\Todolist;
use Doctrine\Persistence\ObjectManager;

class TodolistFixtures extends AbstractFixture
{
    public const NB_FIXTURE = 5;

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
    static public function getGroups(): array
    {
        return ['second'];
    }
}
