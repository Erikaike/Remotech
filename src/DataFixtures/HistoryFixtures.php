<?php

namespace App\DataFixtures;

use App\Entity\History;
use App\DataFixtures\DeviceFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use App\Service\GetCurrentWeather;

class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private GetCurrentWeather $weather)
    {
        $this->weather = $weather;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (DeviceFixtures::DEVICES as $deviceFixture) {
            $device = $this->getReference($deviceFixture['name']);
            
            for ($i = 0; $i < 6; $i++) {
                $history = new History();

                $history->setDevice($device);
                $history->setCreatedAt($faker->dateTimeThisYear());
                if ($deviceFixture['type'] == "Thermometer") {
                    $history->setValue($this->weather->getWeather());
                } else {
                    $history->setValue(rand(0, 10));
                }
                $manager->persist($history);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DeviceFixtures::class,
        ];
    }
}
