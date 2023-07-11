<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const USERS = [
        'admin' => ['enabled' => true, 'roles' => [User::ROLE_ADMIN]],
        'editor' => ['enabled' => false, 'roles' => [User::ROLE_EDITOR]],
        'writer' => ['enabled' => true, 'roles' => [User::ROLE_WRITER]],
        'commentator' => ['enabled' => true, 'roles' => [User::ROLE_COMMENTATOR]],
    ];
    public const PASSWORD = 'local';
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $username => $userStatus) {
            $user = (new User)
                ->setUsername($username)
                ->setName($this->faker->unique()->name())
                ->setEmail($this->faker->unique()->safeEmail())
                ->setRoles($userStatus['roles'])
                ->setEnabled($userStatus['enabled'])
                ->setPlainPassword(self::PASSWORD)
            ;

            $manager->persist($user);
            $this->setReference("user_{$username}", $user);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['default'];
    }
}
