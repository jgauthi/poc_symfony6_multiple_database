<?php
namespace App\DataFixtures;

use App\Entity\Second\Todolist;
use Doctrine\Persistence\ObjectManager;

class TodolistFixtures extends AbstractFixture
{
    public const NB_FIXTURE = 5;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $todolist = (new Todolist)
                ->setTitle($this->faker->unique()->text(20))
                ->setDescription($this->faker->text(50))
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
