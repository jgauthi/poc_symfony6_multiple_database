<?php
namespace App\DataFixtures;

use App\Entity\Main\Client;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $client = (new Client)
                ->setName($this->faker->unique()->company())
                ->setEmail($this->faker->unique()->safeEmail())
                ->setAddress($this->faker->unique()->optional(0.8)->address())
                ->setCity($this->faker->city())
                ->setCountry($this->faker->country())
            ;

            $manager->persist($client);
            $this->setReference("client_$i", $client);
        }

        $manager->flush();
    }
}
