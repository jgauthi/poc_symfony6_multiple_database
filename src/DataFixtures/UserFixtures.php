<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture
{
    public const USERS = [
        'admin' => ['enabled' => true, 'roles' => [User::ROLE_ADMIN]],
        'editor' => ['enabled' => false, 'roles' => [User::ROLE_EDITOR]],
        'writer' => ['enabled' => true, 'roles' => [User::ROLE_WRITER]],
        'commentator' => ['enabled' => true, 'roles' => [User::ROLE_COMMENTATOR]],
    ];
    public const PASSWORD = 'local';

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
}
